<?php
namespace TianRosandhy\Autocrud\Generator\Form;

use Input;

/**
 * FormStructureCollection Renderer logic collections
 */
trait Renderer
{
    public function render()
    {
        $pass = get_object_vars($this);
        $pass['context'] = $this;
        return view(
            config('autocrud.renderer.form'), $pass 
        )->render();
    }
    
    public function isMultipleTabs()
    {
        $tabs = $this->getTabs();
        return count($tabs) > 1;
    }

    public function getTabs(): array
    {
        $tabs = [];
        foreach ($this->structure as $struct) {
            if (!in_array($struct->tabGroup(), $tabs)) {
                $tabs[] = $struct->tabGroup();
            }
        }
        return $tabs;        
    }
}