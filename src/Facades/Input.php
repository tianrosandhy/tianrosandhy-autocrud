<?php
namespace TianRosandhy\Autocrud\Facades;

class Input extends RefreshableFacade
{
    protected static function getFacadeAccessor()
    {
        return \TianRosandhy\Autocrud\Components\Input::class;
    }
}

