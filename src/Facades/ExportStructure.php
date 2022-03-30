<?php
namespace TianRosandhy\Autocrud\Facades;

class ExportStructure extends RefreshableFacade
{
    protected static function getFacadeAccessor()
    {
        return \TianRosandhy\Autocrud\DataStructure\ExportStructure::class;
    }
}
