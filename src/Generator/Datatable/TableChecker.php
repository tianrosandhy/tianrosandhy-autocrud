<?php
namespace TianRosandhy\Autocrud\Generator\Datatable;

/**
 * use this trait if you want your datatable to have a checker feature
 */
trait TableChecker
{
    public function usedHash(): string
    {
        return request()->table_hash ?? $this->hash;
    }

    public function beforeTableHead(): array
    {
        $primary_key = null;
        if (method_exists($this->queryBuilder(), 'getModel')) {
            $model = $this->queryBuilder()->getModel();
            $primary_key_field = $model->getKeyName();
        }

        if (isset($model) && isset($primary_key_field)) {
            return [
                [
                    'field' => '_checker',
                    'label' => '<input type="checkbox" class="checker-all-'.$this->usedHash().'">',
                ],
            ];
        }
        return [];
    }

    public function beforeTableBody($data, $primary_key_field='id')
    {
        $key = null;
        if (is_array($data)) {
            $key = $data[$primary_key_field] ?? null;
        } else {
            $key = $data->{$primary_key_field} ?? null;
            if (empty($key) && method_exists($data, 'getKey')) {
                $key = $data->getKey();
            }
        }

        return [
            '_checker' => '<input type="checkbox" class="checker-'.$this->usedHash().'" data-id="'.$key.'" value="1">'
        ];
    }

    public function afterTable()
    {

    }
}