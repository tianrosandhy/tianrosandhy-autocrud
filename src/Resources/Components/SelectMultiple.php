<?php
namespace TianRosandhy\Autocrud\Resources\Components;

class SelectMultiple extends BaseViewComponent
{
    /*
     * Component initialization :
     * <x-core::input.select-multiple name="input_name" :source=[array] />
     */

    public string $type = 'select_multiple'; //select
    public string $view = 'autocrud::input.select';

    // This component will generate a default <select multiple> with its configuration

    public function __construct(
        public string $name,
        public $source,
        public $value = null,
        public array $attr = [],
        public bool $multiLanguage = false,
        public $data = null,
    ) {}

}
