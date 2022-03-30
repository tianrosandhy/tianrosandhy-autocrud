<?php
namespace TianRosandhy\Autocrud\Components\Input;

class Slug extends BaseViewComponent
{
    /*
     * Component initialization :
     * <x-core::input.slug name="input_name" />
     */
    public string $view = 'autocrud::input.slug';

    // This component will generate a default <input type="slug"> with its configuration

    public function __construct(
        public string $name,
        public string $slugTarget,
        public $value = null,
        public string $type = 'text',
        public array $attr = [],
        public bool $multiLanguage = false,
        public $data = null,
    ) {}

}
