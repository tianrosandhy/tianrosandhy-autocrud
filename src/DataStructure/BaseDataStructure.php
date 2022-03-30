<?php
namespace TianRosandhy\Autocrud\DataStructure;

use Illuminate\Http\Request;
use TianRosandhy\Autocrud\Components\Input;
use Illuminate\Support\Str;
use Closure;

/**
 * BaseDataStructure will contain a generic property that will be used
 * for DatatableStructure, FormStructure, and ExportStructure
 */
class BaseDataStructure
{
    /**
     * $struct_type : "datatable", "form", "export"
     */
    protected string $struct_type;

    /**
     * The field name of the struct cell
     */
    protected string $field;

    /**
     * The user-friendly label name shown to user
     */
    protected string $name;

    /**
     * @source TianRosandhy\Autocrud\Components\Input constants
     */
    protected string $input_type = Input::TYPE_TEXT;

    /**
     * @param array|Closure : must be filled when we use multiple source $input_type like :
     * - Input::TYPE_SELECT
     * - Input::TYPE_SELECTMULTIPLE
     * - Input::TYPE_CHECKBOX
     * - Input::TYPE_RADIO
     */
    protected $data_source;

    /**
     * Handle the struct visibility.
     * Pass the closure that return true / false.
     * Structure will not be shown when the Closure return false.
     */
    protected Closure $visibility;


    // dynamic property setter & getter
    public function __call($name, $arguments)
    {
        $method = substr($name, 0, 3);
        if (in_array($method, ['get', 'set', 'has'])) {
            $prop = substr($name, 3);
            $prop = Str::snake($prop);
        } else {
            // fallback : use setter & getter without "set" / "get" prefix.
            $method = 'get';
            if (isset($arguments[0])) {
                $method = 'set';
            }
            $prop = Str::snake($name);
        }

        if ($method == 'get' && property_exists($this, $prop)) {
            return isset($this->{$prop}) ? $this->{$prop} : null;
        }
        if ($method == 'set' && isset($arguments[0])) {
            $this->{$prop} = $arguments[0];
            return $this;
        }
        if ($method == 'has') {
            $cond = isset($this->{$prop});
            if ($cond) {
                return !empty($cond);
            }
            return false;
        }

        return false;
    }


    // helper methods
    public function isVisible()
    {
        if (isset($this->visibility)) {
            if (is_callable($this->visibility)) {
                $is_visible = call_user_func($this->visibility);
                return (bool)$is_visible;
            }
        }
        // fallback : true
        return true;
    }

}