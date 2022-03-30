<?php
namespace TianRosandhy\Autocrud\Generator\Form;

use TianRosandhy\Autocrud\Generator\BaseGenerator;
use TianRosandhy\Autocrud\DataStructure\FormStructure;
use Illuminate\Database\Eloquent\Model;

class FormCollection extends BaseGenerator
{
    use Renderer;
    use Processor;

    public Model $data;

    public function __construct()
    {
        $this->struct_type = 'form';
        parent::__construct();
    }

    public function register(FormStructure $item)
    {
        $this->structure[] = $item;
        return $this;
    }

    public function registers(array $arr)
    {
        foreach ($arr as $item) {
            if ($item instanceof FormStructure) {
                $this->structure[] = $item;
            }
        }
        return $this;
    }    
}