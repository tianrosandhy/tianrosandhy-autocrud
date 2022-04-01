<?php
namespace TianRosandhy\Autocrud\Generator\Datatable;

trait ResponseHelper
{
    // Easily generate yes/no switcher in datatable response
    public function switcherFormat($row, $field = 'is_active', $mode = 'toggle')
    {
        if ($mode == 'toggle') {
            $table = $row->getTable();
            return view('autocrud::input.yesno', [
                'value' => $row->{$field},
                'name' => $field,
                'attr' => [
                    'data-id' => encrypt($row->getKey()),
                    'data-pk' => encrypt($row->getKeyName()),
                    'data-conn' => encrypt($row->getConnectionName()),
                    'table' => encrypt($table),
                    'field' => encrypt($field),
                    'data-href' => route(config('autocrud.route_prefix').'.switcher'),
                    'data-table-switch' => 1,
                ],
            ])->render();
        } else {
            return $row->{$field} ? '<span class="p-1 btn btn-success" title="Active"><span class="iconify" data-icon="uim:check"></span>' : '<span class="p-1 btn btn-danger" title="Not Active"><span class="iconify" data-icon="uim:multiply"></span></span>';
        }
    }

}
