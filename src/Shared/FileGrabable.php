<?php
namespace TianRosandhy\Autocrud\Shared;

trait FileGrabable
{
    public function outputFile($field, $config = [])
    {
        $container_class = $config['container_class'] ?? 'file-container';
        $template = $config['template'] ?? '<a href="|FILE_URL|" download="|FILE_NAME|" class="badge badge-primary">|FILE_NAME|</a> ';

        $filedata = $this->getFileData($field);
        if ($filedata) {
            $out = '<div class="' . $container_class . '">';
            foreach ($filedata as $file) {
                if (isset($file['url']) && isset($file['filename'])) {
                    $tpl = $template;
                    $tpl = str_replace('|FILE_URL|', $file['url'], $tpl);
                    $tpl = str_replace('|FILE_NAME|', $file['filename'], $tpl);
                    $out .= $tpl;
                }
            }
            $out .= '</div>';
            return $out;
        }
        return null;
    }

    protected function getFileData($field)
    {
        $filedata = explode('|', $this->{$field});
        if (count($filedata) == 0) {
            return null;
        }
        $out = [];
        foreach ($filedata as $fdata) {
            $fdata = json_decode($fdata, true);
            if (isset($fdata['filename']) && isset($fdata['url'])) {
                $out[] = $fdata;
            }
        }

        return $out;
    }
}
