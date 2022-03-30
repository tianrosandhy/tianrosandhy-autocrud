<?php
namespace TianRosandhy\Autocrud\Resources\Components;

class FileMultiple extends BaseViewComponent
{
    /*
     * Component initialization :
     * <x-core::input.file-multiple name="input_name" />
     */

    public string $type = 'multiple';
    public string $view = 'autocrud::input.file';

    // This component will generate a default <input type="file"> with its configuration

    public function __construct(
        public string $name,
        public $value = null,
        public array $attr = [],
        public bool $multiLanguage = false,
        public $data = null,
    ) {}

}
