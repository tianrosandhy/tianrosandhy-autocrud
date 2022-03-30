<?php
namespace TianRosandhy\Autocrud\Components\Input;

class DateRange extends BaseViewComponent
{
    /*
     * Component initialization :
     * <x-core::input.date-range name="input_name" />
     */
    public string $view = 'autocrud::input.daterange';

    // This component will generate a default input currency component with its configuration

    public function __construct(
        public string $name,
        public $value = null, // value must be in ["start_date", "end_date"] format.
        public array $attr = [],
        public bool $monthly = false,
        public bool $multiLanguage = false,
        public $data = null,
    ) {}
}
