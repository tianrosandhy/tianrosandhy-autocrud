<?php
namespace TianRosandhy\Autocrud\Shared;

use Media;
use Storage;

trait ImageGrabable
{

    public function outputImage($field = 'image', $thumb = null, $config = [])
    {
        $container_class = $config['container_class'] ?? 'image-container';
        $template = $config['template'] ?? '<img src="|IMAGE_URL|" style="width:60px;">';

        $out = '<div class="' . $container_class . '">';
        $lists = $this->getImageUrl($field, $thumb, true, defaultImage());
        foreach ($lists as $img_url) {
            $img_template = $template;
            $img_template = str_replace('|IMAGE_URL|', $img_url, $img_template);
            $out .= $img_template;
        }
        $out .= '</div>';
        return $out;
    }

    public function getImageUrl($field = 'image', $thumb = null, $as_multiple_input = false, $fallback = null)
    {
        $image_data = $this->{$field};
        return $this->handleImageRequest($image_data, $thumb, $as_multiple_input, 'url', $fallback);
    }

    public function getImagePath($field = 'image', $thumb = null, $as_multiple_input = false, $fallback = null)
    {
        $image_data = $this->{$field};
        return $this->handleImageRequest($image_data, $thumb, $as_multiple_input, 'path', $fallback);
    }

    protected function handleImageRequest($image_data, $thumb, $as_multiple_input, $type = 'url', $fallback = null)
    {
        $split = explode("|", $image_data);
        if (count($split) > 0) {
            $image_data = $split;
        } else if (is_string($image_data)) {
            $image_data = [$image_data];
        }

        $out = [];
        foreach ($image_data as $image_json) {
            // try decode via json. if failed, then load the string path via storage
            $try_decode = json_decode($image_json, true);
            if ($try_decode) {
                $img = Media::getSelectedImage($image_json, $thumb, $type);
                $out[] = $img ?? $fallback;
            } else if (Storage::exists($image_json) && strlen($image_json) > 0) {
                if ($type == 'url') {
                    $out[] = Storage::url($image_json);
                } else {
                    $out[] = $image_json;
                }
            } else {
                $out[] = $fallback;
            }
        }

        if (!$as_multiple_input) {
            return $out[0] ?? $fallback;
        }
        return $out;
    }

}
