<?php
namespace TianRosandhy\Autocrud\Generator\Form;

use TianRosandhy\Autocrud\Generator\BaseGeneratorContract;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Support\Collection;

interface FormCollectionContract extends BaseGeneratorContract
{
    public function formRoute(): string;

    public function isMultiLanguage(): bool;

    public function isAjax(): bool;
}