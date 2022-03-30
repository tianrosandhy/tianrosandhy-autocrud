<?php
namespace TianRosandhy\Autocrud\Generator\Datatable;

use TianRosandhy\Autocrud\Generator\BaseGeneratorContract;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Support\Collection;

interface DatatableCollectionContract extends BaseGeneratorContract
{
    public function dataTableRoute(): string;

    public function queryBuilder(): QueryBuilder|EloquentBuilder;

    public function transformer($raw_data): array;

    public function exportRow($raw_data): array;
}