<?php
namespace TianRosandhy\Autocrud\Shared;

use Autocrud;

trait Translateable
{
    public function translate()
    {
        return $this->hasMany($this->translate_model, 'main_id');
    }

    public function translatorInstance()
    {
        $instance = app($this->translate_model);
        $instance->main_id = $this->getKey();
        return $instance;
    }

    public function clearTranslate()
    {
        return app($this->translate_model)->where('main_id', $this->getKey())->delete();
    }

    public function outputTranslate($field, $lang = null)
    {
        $fallback = $this->{$field};
        if (empty($lang)) {
            $lang = Autocrud::currentLang();
        }
        $grab = $this->translate->where('lang', $lang)->first();
        return $grab->{$field} ?? $fallback;
    }
}
