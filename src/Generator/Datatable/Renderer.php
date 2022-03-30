<?php
namespace TianRosandhy\Autocrud\Generator\Datatable;

use Input;

/**
 * DatatableCollection Renderer logic collections
 */
trait Renderer
{
    public function render()
    {
        $pass = get_object_vars($this);
        $pass['context'] = $this;
        return view(
            config('autocrud.renderer.table'), $pass 
        )->render();
    }
    
    public function renderAsset()
    {
        $pass = get_object_vars($this);
        $pass['context'] = $this;
        return view(
            config('autocrud.renderer.table_asset'), $pass 
        )->render();
    }
    

    // datatable context ajax method helpers
    public function generateSearchQuery()
    {
        $out = 'data.keywords = new Object; ';
        $i = $this->beforeRendererCount(); // if there is new before table append, $i will adapt
        foreach ($this->structure as $row) {
            if ($row->isVisible() && !$row->hideOnDatatable()) {
                $fld = str_replace('[]', '', $row->getField());
                if (in_array($row->inputType(), [Input::TYPE_DATE, Input::TYPE_DATERANGE, Input::TYPE_DATERANGE])) {
                    $out .= 'data.keywords["'.$fld.'"] = new Array;';
                    $out .= 'data.keywords["'.$fld.'"][0] = $("#searchBox-'.$this->hash.' [data-id=\'datatable-filter-' . $fld . '\'][data-start-range]").val(); ';    
                    $out .= 'data.keywords["'.$fld.'"][1] = $("#searchBox-'.$this->hash.' [data-id=\'datatable-filter-' . $fld . '\'][data-end-range]").val(); ';    
                } else {
                    $out .= 'data.keywords["'.$fld.'"] = $("#searchBox-'.$this->hash.' [data-id=\'datatable-filter-' . $fld . '\']").val(); ';
                }
                $i++;
            }
        }
        return $out;
    }

    public function datatableOrderable()
    {
        $out = '';
        $i = $this->beforeRendererCount(); // if there is new before table append, $i will adapt
        if ($i > 0) {
            // force disable orderable on appended before renderer
            for ($x=0; $x<$i; $x++) {
                $out .= "{'targets' : ".$x.", 'orderable' : false}, ";
            }
        }

        foreach ($this->structure as $row) {
            if ($row->isVisible() && !$row->hideOnDatatable()) {
                if (!$row->getOrderable()) {
                    $out .= "{'targets' : " . $i . ", 'orderable' : false}, ";
                }
                $i++;
            }
        }
        $out .= "{'targets' : ".$i.", 'orderable' : false} ";
        return $out;
    }

    public function datatableDefaultOrder()
    {
        $order_data = ''; //fallback
        $i = $this->beforeRendererCount(); // if there is new before table append, $i will adapt
        foreach ($this->structure as $row) {
            if ($row->isVisible() && !$row->hideOnDatatable()) {
                if (strlen($row->getDefaultOrder()) > 0) {
                    //kalau ada salah satu field yang set default order, langsung hentikan loop
                    $order_data = '[' . $i . ', "' . $row->getDefaultOrder() . '"]';
                    break;
                }
                $i++;
            }
        }

        return $order_data;
    }

    public function datatableColumns()
    {
        $i = 0;
        $out = '';
        if ($this->beforeRendererCount() > 0) {
            foreach ($this->beforeTableHead() as $row) {
                $out .= "{data : '".($row['field'] ?? 'unknown_field_' . substr(sha1(rand(1, 9999) . uniqid()), 0, 8) )."'}, ";
            }
        }

        foreach ($this->structure as $row) {
            if ($row->isVisible() && !$row->hideOnDatatable()) {
                $fld = str_replace('[]', '', $row->field());
                $out .= "{data : '" . $fld . "'}, ";
            }
        }
        $out .= "{data : 'action'}";
        return $out;
    }

    protected function beforeRendererCount()
    {
        if (method_exists($this, 'beforeTableHead')) {
            $before_table = $this->beforeTableHead();
            return count($before_table);
        }
        return 0;
    }
}