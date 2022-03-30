<?php
namespace TianRosandhy\Autocrud\Components\Input;

class Email extends BaseViewComponent
{
    /*
     * Component initialization :
     * <x-core::input.email name="input_name" />
     */

    public string $type = 'email';
    public string $view = 'autocrud::input.text';

    // This component will generate a default <input type="email"> with its configuration

    public function __construct(
        public string $name,
        public $value = null,
        public array $attr = [],
        public bool $multiLanguage = false,
        public $data = null,
    ) {}

}
