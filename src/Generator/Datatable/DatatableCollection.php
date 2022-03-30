<?php
namespace TianRosandhy\Autocrud\Generator\Datatable;

use TianRosandhy\Autocrud\DataStructure\DatatableStructure;
use TianRosandhy\Autocrud\Generator\BaseGenerator;

class DatatableCollection extends BaseGenerator
{
    use Renderer;
    use ResponseGenerator;

    public function __construct()
    {
        $this->hash = sha1(rand(1, 99999) . time() . uniqid());
        $this->struct_type = 'datatable';
        parent::__construct();
    }

    public function register(DatatableStructure $item)
    {
        $this->structure[] = $item;
        return $this;
    }

    public function registers(array $items)
    {
        foreach ($items as $item) {
            if ($item instanceof DatatableStructure) {
                $this->structure[] = $item;
            }
        }
        return $this;
    }

}