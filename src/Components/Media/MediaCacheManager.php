<?php
namespace TianRosandhy\Autocrud\Components\Media;

use Cache;

class MediaCacheManager
{
    public $cache_prefix = 'MEDIA';

    public function __construct()
    {

    }

    public function createMediaCache($instance)
    {
        Cache::put($this->getCacheId($instance->id), $instance);
    }

    public function buildAllMediaCache()
    {
        $data = Model::get();
        foreach ($data as $row) {
            $this->createMediaCache($row);
        }
    }

    public function getMediaCacheById($id)
    {
        $cache = Cache::get($this->getCacheId($id));
        if (empty($cache)) {
            $instance = Model::find($id);
            if ($instance) {
                $this->createMediaCache($instance);
            } else {
                $instance = new Model;
            }
            return $instance;
        }
        return $cache;

    }

    public function getMediaCacheByJson($json)
    {
        $data = json_decode($json, true);
        if (isset($data['id'])) {
            return $this->getMediaCacheById($data['id']);
        }

        //fallback : return blank instance
        $instance = new Model;
        return $instance;
    }

    protected function getCacheId($instance_id)
    {
        return $this->cache_prefix . '-' . $instance_id;
    }
}
