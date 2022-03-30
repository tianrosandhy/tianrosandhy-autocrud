<?php
namespace TianRosandhy\Autocrud\Resources\Components;

class ImageMultiple extends BaseViewComponent
{
    /*
     * Component initialization :
     * <x-core::input.image-multiple name="input_name" />
     */
    public string $view = 'autocrud::input.image_multiple';

    // This component will generate a default <input type="text"> with its configuration

    public function __construct(
        public string $name,
        public $value = null,
        public array $attr = [],
        public bool $multiLanguage = false,
        public $data = null,
    ) {}

}
