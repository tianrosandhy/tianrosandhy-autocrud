<?php
namespace TianRosandhy\Autocrud\DataStructure;

use Input;
use TianRosandhy\Autocrud\Facades\Input as InputHelper;

class FormStructure extends BaseDataStructure
{
    public function __construct()
    {
        $this->struct_type = 'form';
    }

    /**
     * @param (int) set the CRUD field width in bootstrap column format (1-12). (Default = 12)
     */
    public int $form_column = 12;

    /**
     * @param (array) append the input in CRUD with this attribute
     * array format is : ["attr_name" => "value", "another_attr_name" => "another_value"]
     */
    public array $input_attribute = [];

    /**
     * @param (bool) manage the CRUD label visibility (default = true)
     */ 
    public bool $show_label = true;

    /** 
     * @param (string) append string note on below CRUD input
     */
    public string $notes;

    /**
     * We can set the initial / default value of the input by passing the Closure that return the value
     */
    public Closure $value_data;

    /**
     * @param (bool) Mark if the input in this CRUD form is multiple language or not
     */
    public bool $translateable = false;

    /**
     * @param (string) Set the tab group name
     */
    public string $tab_group = 'General';
    
    /**
     * @param (string) Set the custom value path for the input
     */
    public string $view;

    /**
     * @param (string|array) Pass a validation for new data only. string or array to be validated via laravel's Validator class
     */
    public $create_validation;

    /**
     * @param (string|array) Pass a validation for update data only. string or array to be validated via laravel's Validator class
     */
    public $update_validation;

    /**
     * @param (string|array) Pass a general validation string or array to be validated via laravel's Validator class
     */
    public $validation;

    /**
     * @param (array) Pass a custom validation translation to process logic.
     * Array format is : ["field_name.validation_name" => "translation_data"]
     */    
    public array $validation_translation;

    
    public function isMandatory($mode='create')
    {
        $validation = [];
        $validation[] = $this->validation();
        if ($mode == 'create') {
            $validation[] = $this->createValidation();
        } else {
            $validation[] = $this->updateValidation();
        }

        $mandatory = false;
        foreach ($validation as $val) {
            if (is_array($val)) {
                foreach ($val as $item) {
                    if (is_string($item)) {
                        if (strpos(strtolower($item), 'required') !== false) {
                            $mandatory = true;
                        }
                    }
                }
            } else if (is_string($val)) {
                if (strpos(strtolower($val), 'required') !== false) {
                    $mandatory = true;
                }
            }
        }
        return $mandatory;
    }

    public function generateInput($data=null, $is_multi_language=false)
    {
        $config = [
            'type' => $this->input_type,
            'name' => $this->field,
            'attr' => $this->input_attribute,
            'data' => $data,
            'value' => $this->generateStoredValue($data, $is_multi_language),
        ];

        if ($this->input_type == 'slug') {
            $config['slug_target'] = $this->slug_target;
        }
        if (in_array($this->input_type, [Input::TYPE_SELECT, Input::TYPE_SELECTMULTIPLE, Input::TYPE_RADIO, Input::TYPE_CHECKBOX])) {
            $config['source'] = $this->data_source;
        }

        if ($is_multi_language) {
            return InputHelper::multiLanguage(true)->type($this->input_type, $this->field, $config);
        } else {
            return InputHelper::type($this->input_type, $this->field, $config);
        }        
    }

    protected function generateStoredValue($data, $multi_language = false)
    {
        $field_name = $this->field;
        if (strpos($field_name, '[]') !== false) {
            $field_name = str_replace('[]', '', $field_name);
        }
        if (isset($this->value_data)) {
            if ($multi_language) {
                foreach (config('autocrud.lang.available', ['en' => 'English']) as $lang => $langlabel) {
                    $value[$lang] = call_user_func($this->value_data, $data, $lang);
                }
            } else {
                $value = call_user_func($this->value_data, $data);
            }
        } else {
            if ($multi_language) {
                foreach (config('autocrud.lang.available', ['en' => 'English']) as $lang => $langlabel) {
                    $value[$lang] = null;
                    if (isset($data->{$field_name})) {
                        if (method_exists($data, 'outputTranslate')) {
                            $value[$lang] = $data->outputTranslate($field_name, $lang, true);
                        } else {
                            $value[$lang] = $data->{$field_name};
                        }
                    }
                }
            } else {
                $value = isset($data->{$field_name}) ? $data->{$field_name} : null;
            }
        }

        //grab from fallback if empty value
        if (empty($value)) {
            if (is_string($value) && strlen($value) > 0) {
                // anjir lah.. "0" dibaca empty sama php dong -_-
                return $value;
            }
            $value = null;
        }

        return $value;
    }

}