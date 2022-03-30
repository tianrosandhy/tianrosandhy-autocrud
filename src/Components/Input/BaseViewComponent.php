<?php
namespace TianRosandhy\Autocrud\Components\Input;

use Illuminate\View\Component;

/**
 * This class will help component registration can be accessed as html renderer or as normal view renderer.
 */
class BaseViewComponent extends Component
{
    // default component rendering
    public function render()
    {
        return view($this->view);
    }

    // custom html rendering
    public function htmlRender()
    {
        return view($this->view, $this->data())->render();
    }
}
