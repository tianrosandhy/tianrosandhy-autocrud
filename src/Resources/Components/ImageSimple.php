<?php
namespace TianRosandhy\Autocrud\Resources\Components;

class ImageSimple extends BaseViewComponent
{
    /*
     * Component initialization :
     * <x-core::input.image-simple name="input_name" />
     */
    public string $view = 'autocrud::input.image_simple';

    // This component will generate a default <input type="file"> for image component with its configuration

    public function __construct(
        public string $name,
        public $value = null,
        public array $attr = [],
        public bool $multiLanguage = false,
        public $data = null,
    ) {}

}
