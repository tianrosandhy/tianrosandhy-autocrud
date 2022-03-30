<?php
namespace TianRosandhy\Autocrud;

class Autocrud
{
    /**
     * css() method will return a core css used only
     */
    public static function css()
    {
        return view('autocrud::assets-css')->render();
    }

    /**
     * js() method will return a core js used only
     */
    public static function js()
    {
        return view('autocrud::assets-js')->render();
    }

    /**
     * asset() method will return all core css & js used
     */
    public static function assets()
    {
        return view('autocrud::assets')->render();
    }

    public static function slugify(string $text): string
    {
        $input = preg_replace("/[^a-zA-Z0-9- &]/", "", $text);
        $string = strtolower(str_replace(' ', '-', $input));
        if (strpos($string, '&') !== false) {
            $string = str_replace('&', 'and', $string);
        }
        return $string;
    }

    public static function langs()
    {
        return config('autocrud.lang.available', [
            'en' => 'English'
        ]);
    }

    public static function defaultLang()
    {
        return config('autocrud.lang.default', 'en');
    }

    public static function activeLang()
    {
        return count(config('autocrud.lang.available', [
            'en' => 'English'
        ]) > 1);
    }

    public static function currentLang()
    {
        if (session('lang')) {
            return session('lang');
        }
        return config('autocrud.lang.default', 'en');
    }

}