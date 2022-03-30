<?php
if (!function_exists('array_to_html_prop')) {
    function array_to_html_prop($arr = [], $ignore_key = [])
    {
        if (empty($arr)) {
            return '';
        }
        $out = '';
        foreach ($arr as $key => $value) {
            if (is_array($value)) {
                $value = implode(' ', $value);
            } elseif (is_object($value)) {
                $value = json_encode($value);
            }

            if (in_array(strtolower($key), $ignore_key)) {
                continue;
            }

            $out .= $key . '="' . $value . '" ';
        }

        return $out;
    }
}

if (!function_exists('file_upload_max_size')) {
    function file_upload_max_size($mb = 0)
    {
        static $max_size = -1;

        if ($max_size < 0) {
            // Start with post_max_size.
            $post_max_size = parse_size(ini_get('post_max_size'));
            if ($post_max_size > 0) {
                $max_size = $post_max_size;
            }

            // If upload_max_size is less, then reduce. Except if upload_max_size is
            // zero, which indicates no limit.
            $upload_max = parse_size(ini_get('upload_max_filesize'));
            if ($upload_max > 0 && $upload_max < $max_size) {
                $max_size = $upload_max;
            }
        }

        $setting_max_size = config('autocrud.max_filesize.file') * 1024 * 1024;
        $max_size = min($max_size, $setting_max_size);

        if ($mb > 0) {
            return min($max_size, ($mb * 1024 * 1024));
        }
        return $max_size;
    }    
}

if (!function_exists('parse_size')) {
    function parse_size($size)
    {
        $unit = preg_replace('/[^bkmgtpezy]/i', '', $size); // Remove the non-unit characters from the size.
        $size = preg_replace('/[^0-9\.]/', '', $size); // Remove the non-numeric characters from the size.
        if ($unit) {
            // Find the position of the unit in the ordered string which is the power of magnitude to multiply a kilobyte by.
            return round($size * pow(1024, stripos('bkmgtpezy', $unit[0])));
        } else {
            return round($size);
        }
    }    
}

if (!function_exists('defaultImage')) {
    function defaultImage()
    {
        return config('autocrud.asset_url') . 'images/broken-image.jpg';
    }
}

if (!function_exists('def_lang')) {
    function def_lang()
    {
        return Autocrud::defaultLang();
    }
}

if (!function_exists('slugify')) {
    function slugify($input, $delimiter = '-')
    {
        $input = preg_replace("/[^a-zA-Z0-9- &]/", "", $input);
        $string = strtolower(str_replace(' ', $delimiter, $input));
        if (strpos($string, '&') !== false) {
            $string = str_replace('&', 'and', $string);
        }
        return $string;
    }
}

if (!function_exists('prettify')) {
    function prettify($slug, $delimiter = '-')
    {
        return str_replace($delimiter, ' ', $slug);
    }
}
