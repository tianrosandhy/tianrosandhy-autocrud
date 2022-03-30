<?php
namespace TianRosandhy\Autocrud\Facades;

use Illuminate\Support\Facades\Facade;

class SlugMaster extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \TianRosandhy\Autocrud\Components\SlugMaster::class;
    }
}
