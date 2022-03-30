<?php
namespace TianRosandhy\Autocrud\Generator;

use Illuminate\Support\Collection;

interface BaseGeneratorContract
{
    public function handle();

    public function registers(array $item);

    public function output(): Collection;
}
