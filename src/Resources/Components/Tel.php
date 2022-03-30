<?php
namespace TianRosandhy\Autocrud\Resources\Components;

class Tel extends BaseViewComponent
{
    /*
     * Component initialization :
     * <x-core::input.tel name="input_name" />
     */

    public string $type = 'tel';
    public string $view = 'autocrud::input.text';

    // This component will generate a default <input type="tel"> with its configuration

    public function __construct(
        public string $name,
        public $value = null,
        public array $attr = [],
        public bool $multiLanguage = false,
        public $data = null,
    ) {}

}
