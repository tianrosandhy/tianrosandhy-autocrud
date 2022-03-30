<?php
namespace TianRosandhy\Autocrud\Facades;

use Illuminate\Support\Facades\Facade;

class Media extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \TianRosandhy\Autocrud\Components\Media::class;
    }
}
