<?php
namespace TianRosandhy\Autocrud\Facades;

class DatatableStructure extends RefreshableFacade
{
    protected static function getFacadeAccessor()
    {
        return \TianRosandhy\Autocrud\DataStructure\DatatableStructure::class;
    }
}
