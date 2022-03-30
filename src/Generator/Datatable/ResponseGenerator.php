<?php
namespace TianRosandhy\Autocrud\Generator\Datatable;

use Exception;
use Input;
use Excel;
use Storage;

trait ResponseGenerator
{
    public string $datatable_error = '';

    public int $page = 1;
    public $raw_data = null;
    public $data = [];
    public int $data_count = 0;

    public function datatableResponse()
    {
        try {
            $this->validateDatatableRequest();
            $this->prepareDataTableVariables();

            // generate builder with filter parameter if exists
            $this->generateDataByRequest();

            if (method_exists($this, 'transformer')) {
                $this->data = [];
                foreach ($this->raw_data as $item) {
                    $this->data[] = $this->transformer($item);
                }
            }
            if (empty($this->data)) {
                $this->data = $this->raw_data;
            }

            if (method_exists($this, 'beforeTableBody')) {
                if (method_exists($this, 'toArray')) {
                    $this->data = $this->data->toArray();
                }

                $pk = null;
                if (method_exists($this, 'tableKey')) {
                    $pk = $this->tableKey();
                }

                $final = [];
                foreach ($this->data as $row) {
                    $append = $this->beforeTableBody($row, $pk);
                    $row = array_merge($append, $row);
                    $final[] = $row;
                }
                $this->data = $final;
            }
        } catch (Exception $e) {
            $this->datatable_error = $e->getMessage();
        }

        return $this->renderDatatableResponse();
    }

    public function validateDatatableRequest()
    {
        //prepare variabel disini aja sekalian
        $this->columns = $this->request->columns;
        $this->start = $this->request->start ?? 0;
        $this->length = $this->request->length ?? 10;

        $validator = $this->request->validate([
            'columns' => 'required|array',
            'order' => 'array',
            'start' => 'required',
            'length' => 'required',
        ]);
    }

    public function prepareDataTableVariables()
    {
        $this->filter = [];

        foreach ($this->request->keywords ?? [] as $field => $value) {
            $this->filter[str_replace('[]', '', $field)] = $value;
        }

        $order_by = null;
        if (isset($this->request->order[0]['column'])) {
            $cindex = $this->request->order[0]['column'];
            $order_by = $this->columns[$cindex]['data'] ?? null;
        }
        $order_dir = $this->request->order[0]['dir'] ?? 'desc';
        $this->order_by = $order_by;
        $this->order_dir = $order_dir;
    }

    protected function renderDatatableResponse()
    {
        if ($this->datatable_error) {
            return [
                'draw' => $this->request->draw ?? 0,
                'error' => $this->datatable_error
            ];
        } else {
            return [
                'draw' => $this->request->draw ?? 0,
                'page' => $this->page,
                'data' => $this->data,
                'recordsFiltered' => $this->data_count,
                'recordsTotal' => $this->data_count,
            ];
        }
    }


    public function exportResponse()
    {
        try {
            $savepath = $this->getExportResponsePath();
            return redirect(Storage::url($savepath));
        } catch (Exception $e) {
            abort(500);
        }
    }

    public function getExportResponsePath()
    {
        $this->prepareExportVariables();
        $this->getExportableFields();
        $this->generateDataByRequest(false);

        if (method_exists($this, 'exportRow')) {
            $this->data = [];
            foreach ($this->raw_data as $item) {
                $this->data[] = $this->exportRow($item);
            }
        }
        if (empty($this->data)) {
            $this->data = $this->raw_data;
        }

        // generate excel stream data
        $savepath = 'export/ExportReport' . date('YmdHis').'.xlsx';
        if (method_exists($this, 'exportFileName')) {
            $savepath = 'export/' . $this->exportFileName() .'-'. date('YmdHis') . '.xlsx';
        }

        Excel::store(new ArrayDataExporter($this->exportHeaders, $this->data), $savepath);
        return $savepath;
    }

    protected function getExportableFields()
    {
        $this->exportHeaders = [];
        foreach ($this->structure as $struct) {
            if ($struct->hideOnExport()) {
                continue;
            }
            $this->exportHeaders[$struct->field()] = $struct->name();
        }
    }

    public function prepareExportVariables()
    {
        $this->filter = [];

        foreach ($this->request->keywords ?? [] as $field => $value) {
            $this->filter[str_replace('[]', '', $field)] = $value;
        }

        $order_by = null;
        if (isset($this->request->order[0]['column'])) {
            $cindex = $this->request->order[0]['column'];
            $order_by = $this->columns[$cindex]['data'] ?? null;
        }
        $order_dir = $this->request->order[0]['dir'] ?? 'desc';
        $this->order_by = $order_by;
        $this->order_dir = $order_dir;
    }




    public function generateDataByRequest($paging=true)
    {
        $data = $this->queryBuilder();
        $without_filter = $data;

        //data-data yang diluar column listing boleh diabaikan dari filter
        $map_custom_filter = [];
        $map_custom_orderby = [];

        foreach ($this->structure as $struct) {
            if (!empty($struct->order_override)) {
                $map_custom_orderby[$struct->field()] = $struct->order_override;
            }
        }

        if (!empty($this->filter)) {

            // additional logic to check input type
            $map_input_type = [];
            foreach ($this->structure as $struct) {
                // if current DataStructure contain search override, register it first
                if (!empty($struct->search_override)) {
                    $map_custom_filter[$struct->field()] = $struct->search_override;
                } else {
                    $map_input_type[$struct->field()] = $struct->getInputType();
                }
            }

            foreach ($this->filter as $column => $value) {
                if (empty($value)) {
                    continue;
                }
                if (isset($map_custom_filter[$column])) {
                    // handle filter override
                    if (!is_callable($map_custom_filter[$column])) {
                        throw new Exception("Filter '".$column."' search_override is invalid. You need to return a valid callback of query builder");
                    }

                    if (is_array($value) && isset(array_values($value)[0]) || is_string($value)) {
                        $data = $data->when(true, $map_custom_filter[$column]);
                    }
                    continue;
                }

                $input_type = $map_input_type[$column] ?? Input::TYPE_TEXT;

                //jika tipe input adalah dropdown / angka, query tidak perlu menggunakan where LIKE.
                if ((is_numeric($value) && strlen($value) < 6) || in_array($input_type, [
                    Input::TYPE_SELECT,
                    Input::TYPE_RADIO,
                    Input::TYPE_CHECKBOX,
                    Input::TYPE_NUMBER,
                ])) {
                    $data = $data->where($column, trim($value));
                } else if (is_array($value)) {
                    // basic handle datetime filter
                    $value = array_values($value);
                    if (count($value) == 2) {
                        if (strlen($value[0]) > 0) {
                            $data = $data->where($column, '>=', $value[0]);
                        }
                        if (strlen($value[1]) > 0) {
                            $data = $data->where($column, '<=', $value[1]);
                        }
                    }
                } else {
                    $data = $data->where($column, 'like', '%' . trim($value) . '%');
                }
            }
        }

        if (method_exists($this, 'customFilter')) {
            $data = $this->customFilter($data);
        }
        
        // handle orderby
        if (isset($map_custom_orderby[$this->order_by])) {
            $order_by = $map_custom_orderby[$this->order_by];
            $data = $data->orderBy($order_by, $this->order_dir);
        } else if (strlen($this->order_by) > 0) {
            $data = $data->orderBy($this->order_by, $this->order_dir);
        }

        if ($paging) {
            $without_filter = clone $data;
            $datacount = $without_filter->count();

            $data = $data->skip($this->start);
            $data = $data->take($this->length);
            $this->data_count = $datacount;
            $this->data_count = $datacount;
            $this->page = ($this->start / $this->length) + 1;
            $this->raw_data = $data->get();
        } else {
            $data = $data->take(config('autocrud.max_export_row_limit'));
            $this->raw_data = $data->get();
        }
    }

}