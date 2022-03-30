<?php
namespace TianRosandhy\Autocrud\Resources\Components;

class Number extends BaseViewComponent
{
    /*
     * Component initialization :
     * <x-core::input.number name="input_name" />
     */
    public string $view = 'autocrud::input.number';

    // This component will generate a default <input type="number"> with its configuration

    public function __construct(
        public string $name,
        public $value = null,
        public array $attr = [],
        public bool $multiLanguage = false,
        public $data = null,
    ) {}

}
