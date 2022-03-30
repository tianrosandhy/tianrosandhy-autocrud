<?php
namespace TianRosandhy\Autocrud\Generator\Form;

use Exception;
use Input;
use Storage;
use Validator;
use SlugMaster;
use Illuminate\Http\UploadedFile;

/**
 * FormStructureCollection AutoCRUD Processor logic collections
 */
trait Processor
{
    public function autoCrud()
    {
        $this->response = new CrudResponse;
        $this->fillables = $this->data->getFillable();
        if (empty($this->fillables)) {
            $this->response->setError("You must setup the fillable property in model, so AutoCRUD can run");
            return $this->response;
        }

        $this->validateAutocrudRequest();

        if (!$this->response->ok()) {
            return $this->response;
        }

        // handle custom validation
        if (method_exists($this, 'afterValidation')) {
            try {
                $this->afterValidation();
            } catch (Exception $e) {
                $this->response->handleException($e);
                return $this->response;
            }
        }

        // run auto crud
        $post = $this->generateStoredData();
        foreach ($post as $field => $value) {
            if ($value instanceof UploadedFile) {
                //upload dulu filenya, return file path
                $this->data->{$field} = $this->handleDataImageUpload($value);
            } else {
                $this->data->{$field} = $value;
            }
        }
        $this->data->save();

        // if model have a Translateable attribute :
        if (method_exists($this->data, 'translatorInstance')) {
            #clear translate data setiap kali insert/update data
            $this->data->clearTranslate();
            foreach (config('autocrud.lang.available') as $lang => $langname) {
                $trans = $this->data->translatorInstance();
                $trans->lang = $lang;
                $postTrans = $this->generateStoredData($lang);
                $added = 0;
                foreach ($postTrans as $field => $value) {
                    if ($value instanceof UploadedFile) {
                        //jika berupa file upload, ambil value dari hasil upload di awal saja
                        $trans->{$field} = $this->data->{$field};
                    } else {
                        $trans->{$field} = $value;
                    }
                    $added++;
                }
                //kalau gaada field yg ditambah, gausa save data translate
                if ($added > 0) {
                    $trans->save();
                }
            }
        }

        // run after auto crud
        if (method_exists($this, 'afterCrud')) {
            try {
                $this->afterCrud($this->data);
            } catch (Exception $e) {
                $this->response->handleException($e);
                return $this->response;
            }
        }

        if (method_exists($this->data, 'slugTarget') && $this->request->slug_master) {
            //store current slug master to
            $slug_instance = SlugMaster::insert($this->data, $this->request->slug_master);
        }

        $this->response->setData($this->data);
        return $this->response;
    }

    public function validateAutocrudRequest()
    {
        $this->mode = $this->data->getKey() ? 'edit' : 'create';
        $validations = $this->generateValidationRules();
        $validation_translations = $this->generateValidationTranslations();
        $validator = Validator::make($this->request->all(), $validations, $validation_translations);
        if ($validator->fails()) {
            $this->response->setErrors($validator->errors()->all());
        }
    }

    protected function generateValidationRules()
    {
        $id = $this->data->getKey() ?? null;
        if (empty($id)) {
            $id = 0;
        }

        $validations = [];
        foreach ($this->structure as $struct) {
            $field = $struct->field();
            $svalidation = $struct->validation();
            $screateValidation = $struct->createValidation();
            $supdateValidation = $struct->updateValidation();
            $multi_language = $struct->translateable();

            if (strpos($field, '[]') !== false) {
                $field = str_replace('[]', '', $field);
            }

            if ($multi_language) {
                $field = $field . '.' . config('autocrud.lang.default');
            }

            if ($svalidation) {
                if (is_string($svalidation)) {
                    $svalidation = str_replace('[id]', $id, $svalidation);
                    $validations[$field] = explode('|', $svalidation);
                } else if (is_array($svalidation)) {
                    $svalidation = array_map(function($item) use($id){
                        return str_replace('[id]', $id, $item);
                    }, $svalidation);
                    $validations[$field] = $svalidation;
                }
            }

            if ($this->mode == 'create' && $screateValidation) {
                if (!isset($validations[$field])) {
                    $validations[$field] = [];
                }

                if (is_string($screateValidation)) {
                    $screateValidation = str_replace('[id]', $id, $screateValidation);
                    $validations[$field] = array_merge($validations[$field], explode('|', $screateValidation));
                } else if (is_array($screateValidation)) {
                    $screateValidation = array_map(function($item) use($id){
                        return str_replace('[id]', $id, $item);
                    }, $screateValidation);
                    $validations[$field] = $screateValidation;
                }
            } else if ($this->mode == 'edit' && $supdateValidation) {
                if (!isset($validations[$field])) {
                    $validations[$field] = [];
                }

                if (is_string($supdateValidation)) {
                    $supdateValidation = str_replace('[id]', $id, $supdateValidation);
                    $validations[$field] = array_merge($validations[$field], explode('|', $supdateValidation));
                } else if (is_array($supdateValidation)) {
                    $supdateValidation = array_map(function($item) use($id){
                        return str_replace('[id]', $id, $item);
                    }, $supdateValidation);
                    $validations[$field] = $supdateValidation;
                }
            }
        }
        return $validations;
    }

    protected function generateValidationTranslations()
    {
        $validation_translation = [];
        foreach ($this->structure as $struct) {
            $multi_language = $struct->translateable();
            if ($struct->validationTranslation()) {
                $vt = $struct->validationTranslation();
                foreach ($vt as $key => $val) {
                    if ($multi_language) {
                        $split_key = explode('.', $key);
                        $last = $split_key[count($split_key)-1];
                        unset($split_key[count($split_key)-1]);
                        $fkey = implode('.', $split_key) . '.' . config('autocrud.lang.default') . '.' . $last;
                        $validation_translation[$fkey] = $val;
                    }
                }
            }
        }
        return $validation_translation;
    }

    protected function generateStoredData($lang=null)
    {
        $savedata = [];
        foreach ($this->structure as $struct) {
            $trimmed_field = str_replace('[]', '', $struct->field());
            if (!in_array($trimmed_field, $this->fillables)) {
                // current struct field name is not in fillable 
                continue;
            }

            $savedata[$trimmed_field] = $this->getAutoCrudInputValue($struct, $lang);
        }
        return $savedata;
    }

    protected function getAutoCrudInputValue($struct, $lang=null) 
    {
        $lang = $lang || config('autocrud.lang.default');
        $field = $struct->field();
        if (strpos($field, '[]') !== false) {
            $field = str_replace('[]', '', $field);
        }
        $value_for_saved = $this->request->{$field};

        if ($struct->translateable() && is_array($value_for_saved)) {
            $fallback = $value_for_saved[config('autocrud.lang.default')] ?? null;
            if (isset($value_for_saved[$lang])) {
                $value_for_saved = $value_for_saved[$lang];
            } else if (!empty($fallback)) {
                $value_for_saved = $fallback;
            } 
        }

        if (!is_array($value_for_saved)) {
            if ($struct->inputType() == Input::TYPE_CURRENCY) {
                $value_for_saved = str_replace('.', '', $value_for_saved);
                $value_for_saved = str_replace(',', '.', $value_for_saved);
            }
        }
        if ($struct->inputType() == Input::TYPE_MAP) {
            $value_for_saved = !empty($value_for_saved) ? json_encode($value_for_saved) : null;
        }
        if ($struct->inputType() == Input::TYPE_IMAGEMULTIPLE && is_array($value_for_saved)) {
            $value_for_saved = implode('|', $value_for_saved);
        }
        if ($struct->inputType() == Input::TYPE_IMAGESIMPLE) {
            $old_image = $this->request->{'_old' . $field_name}['_old'] ?? ($this->request->{'_old' . $field_name}[config('autocrud.lang.default')]['_old'] ?? null);
            if (strlen($old_image) > 0 && empty($value_for_saved)) {
                try {
                    // if old_value cannot be decrypt, then this input will be ignored
                    $value_for_saved = decrypt($old_image);
                } catch (Exception $e) {
                    $value_for_saved = null;
                }
            }
        }

        //we cannot save the array value to database. by default, parse the value as json value
        if (is_array($value_for_saved)) {
            $value_for_saved = json_encode($value_for_saved);
        }
        //set fallback non existent string as null
        if (is_string($value_for_saved)) {
            if (strlen($value_for_saved) == 0) {
                $value_for_saved = null;
            }
        }

        return $value_for_saved;
    }

    public function handleDataImageUpload($file)
    {
        $filename = $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension();
        $nameonly = str_replace('.' . $extension, '', $filename);

        //check if file already exists
        $check_exists = Storage::exists('upload' . '/' . $nameonly . '.' . $extension);
        if ($check_exists) {
            $stored_name = $nameonly . '-' . substr(sha1(rand(1, 10000)), 0, 10) . '.' . $extension;
        } else {
            $stored_name = $nameonly . '.' . $extension;
        }

        $file->storeAs('upload', $stored_name);
        return 'upload/' . $stored_name;
    }
}