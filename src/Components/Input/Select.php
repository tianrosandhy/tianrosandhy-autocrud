<?php
namespace TianRosandhy\Autocrud\Components\Input;

class Select extends BaseViewComponent
{
    /*
     * Component initialization :
     * <x-core::input.select name="input_name" :source=[array] />
     */
    public string $type = 'select'; //select
    public string $view = 'autocrud::input.select';

    // This component will generate a default <select> with its configuration

    public function __construct(
        public string $name,
        public $source,
        public $value = null,
        public array $attr = [],
        public bool $multiLanguage = false,
        public $data = null,
    ) {}

}
