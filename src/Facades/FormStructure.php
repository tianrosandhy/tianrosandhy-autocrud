<?php
namespace TianRosandhy\Autocrud\Facades;

class FormStructure extends RefreshableFacade
{
    protected static function getFacadeAccessor()
    {
        return \TianRosandhy\Autocrud\DataStructure\FormStructure::class;
    }
}
