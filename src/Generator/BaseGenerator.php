<?php
namespace TianRosandhy\Autocrud\Generator;

use Illuminate\Http\Request;
use TianRosandhy\Autocrud\DataStructure\BaseDataStructure;
use Illuminate\Support\Collection;

class BaseGenerator implements BaseGeneratorContract
{
    public $structure = [];
    public $struct_type = null;
    public $multi_language = false;
    public $custom_html = null;
    public $request;

    public function __construct()
    {
        $this->request = request();
        $this->handle();
    }

    public function handle()
    {
        // will be overriden from modules
    }

    // dynamic property setter & getter
    public function __call($name, $arguments)
    {
        $method = substr($name, 0, 3);
        if (in_array($method, ['get', 'set', 'has'])) {
            $prop = substr($name, 3);
            $prop = Str::snake($prop);

            if ($method == 'get' && property_exists($this, $prop)) {
                return $this->{$prop};
            }
            if ($method == 'set' && isset($arguments[0])) {
                $this->{$prop} = $arguments[0];
            }
            if ($method == 'has') {
                $cond = isset($this->{$prop});
                if ($cond) {
                    return !empty($cond);
                }
                return false;
            }
        }
        return $this;
    }

    public function registers(array $item)
    {
        foreach ($arr as $item) {
            if ($item instanceof BaseDataStructure) {
                $this->structure[] = $item;
            }
        }
        return $this;
    }

    public function output(): Collection
    {
        return collect($this->structure);
    }

}