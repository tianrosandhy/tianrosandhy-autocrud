<?php
namespace TianRosandhy\Autocrud\Shared;

use TianRosandhy\Autocrud\Models\SlugMaster;

trait Sluggable
{

    public function slugmaster()
    {
        return $this->hasMany(SlugMaster::class, 'primary_key')->where('table', $this->getTable());
    }

    // default. can be overriden
    public function slugTarget()
    {
        return 'title';
    }

    public function getCurrentSlug()
    {
        if (!$this->slugmaster) {
            return null;
        }
        $slugs = $this->slugmaster->first();
        return $slugs->slug ?? null;
    }

    public function hasSavedSlug()
    {
        return !empty($this->getCurrentSlug());
    }

}
