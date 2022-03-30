<?php
namespace TianRosandhy\Autocrud\Components;

use Storage;
use TianRosandhy\Autocrud\Components\Media\Uploader;
use TianRosandhy\Autocrud\Components\Media\MediaCacheManager;
use TianRosandhy\Autocrud\Components\Media\MediaRequestProcessor;

class Media
{
    use Uploader;
    use MediaRequestProcessor;

    public function __construct()
    {
        $this->request = request();
    }

    public function getById($id)
    {
        return (new MediaCacheManager)->getMediaCacheById($id);
    }

    public function getByJson($json)
    {
        return (new MediaCacheManager)->getMediaCacheByJson($json);
    }

    public function getSelectedImage($json, $grabbed_thumb = null, $mode = 'url')
    {
        $instance = $this->getByJson($json);
        if (isset($instance->id)) {
            $decode = json_decode($json, true);
            if (empty($grabbed_thumb)) {
                $thumb = $decode['thumb'] ?? 'origin';
            } else {
                $thumb = $grabbed_thumb;
            }

            if ($mode == 'url') {
                return $instance->url($thumb);
            } else {
                return $instance->path($thumb);
            }
        }
        return (new Model)->fallback();
    }

    public function single($name, $value = null, $config = [])
    {
        return view('autocrud::media.single', [
            'name' => $name,
            'value' => $value,
            'config' => $config,
        ]);
    }

    public function multiple($name, $value = null, $config = [])
    {
        return view('autocrud::media.multiple', [
            'name' => $name,
            'value' => $value,
            'config' => $config,
        ]);
    }

    // will translate input type file to json filedata
    public function fileTranslate($file_string)
    {
        $parse = json_decode($file_string, true);
        $fileurl = null;
        $filepath = null;
        $filename = null;
        if (!$parse && strlen($file_string) > 0) {
            $split = explode('/', $file_string);
            if (count($split) == 1) {
                // try spliting with backslash
                $split = explode('\\', $file_string);
            }

            $filename = $split[count($split) - 1];
            $fileurl = Storage::url($file_string);
            $filepath = $file_string;
        } else {
            $filepath = $parse['path'] ?? null;
            $filename = $parse['filename'] ?? null;
            $fileurl = $filepath ? Storage::url($filepath) : null;
        }

        return [
            'url' => $fileurl,
            'filename' => $filename,
            'path' => $filepath,
        ];
    }

}
