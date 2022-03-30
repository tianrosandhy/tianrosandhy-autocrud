<?php
namespace TianRosandhy\Autocrud\Components\Input;

class Image extends BaseViewComponent
{
    /*
     * Component initialization :
     * <x-core::input.image name="input_name" />
     */
    public string $view = 'autocrud::input.image';

    // This component will generate a default <input type="text"> with its configuration

    public function __construct(
        public string $name,
        public $value = null,
        public array $attr = [],
        public bool $multiLanguage = false,
        public $data = null,
    ) {}

}
