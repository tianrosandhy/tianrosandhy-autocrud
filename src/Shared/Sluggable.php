<?php
namespace TianRosandhy\Autocrud\Shared;

trait Sluggable
{

    public function slugmaster()
    {
        return $this->hasMany('App\Core\Models\SlugMaster', 'primary_key')->where('table', $this->getTable());
    }

    // default. can be overriden
    public function slugTarget()
    {
        return 'title';
    }

    public function getCurrentSlug()
    {
        if (!$this->slug) {
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
