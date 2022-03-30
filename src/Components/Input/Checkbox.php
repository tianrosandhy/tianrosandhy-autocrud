<?php
namespace TianRosandhy\Autocrud\Components\Input;

class Checkbox extends BaseViewComponent
{
    /*
     * Component initialization :
     * <x-core::input.number name="input_name" />
     */

    public string $type = 'checkbox';
    public string $view = 'autocrud::input.radio';

    // This component will generate a default <input type="number"> with its configuration

    public function __construct(
        public string $name,
        public $source,
        public $value = null,
        public array $attr = [],
        public bool $multiLanguage = false,
        public $data = null,
    ) {}

}
