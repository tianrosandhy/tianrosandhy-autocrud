<?php
namespace TianRosandhy\Autocrud\Components\Input;

class Richtext extends BaseViewComponent
{
    /*
     * Component initialization :
     * <x-core::input.richtext name="input_name" />
     */
    public string $view = 'autocrud::input.richtext';

    // This component will generate a default <textarea data-tinymce> with its configuration

    public function __construct(
        public string $name,
        public $value = null,
        public array $attr = [],
        public bool $multiLanguage = false,
        public $data = null,
    ) {}

}
