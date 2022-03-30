<?php
namespace TianRosandhy\Autocrud\Generator\Form;

use Exception;

class CrudResponse
{
    public $data;

    public bool $ok = true;

    public string $message;

    public array $errors = [];

    // setter
    public function handleException(Exception $e, string $message=null)
    {
        $this->errors = [$e->getMessage()];
        $this->ok = false;
        if ($message) {
            $this->message = $message;
        }
        return $this;
    }

    public function setError(string $error, $message=null) 
    {
        $this->errors = [$error];
        $this->ok = false;
        if ($message) {
            $this->message = $message;
        }
        return $this;
    }

    public function setErrors(array $errors, $message=null) 
    {
        $this->errors = $errors;
        $this->ok = false;
        if ($message) {
            $this->message = $message;
        }
        return $this;
    }

    public function setData($data, string $message=null) 
    {
        $this->data = $data;
        $this->ok = true;
        if ($message) {
            $this->message = $message;
        }
        return $this;
    }

    public function setMessage(string $message)
    {
        $this->message = $message;
        return $this;
    }

    // getter
    public function ok()
    {
        return $this->ok;
    }

    public function errors()
    {
        return $this->errors;
    }
    
    public function errorFirst()
    {
        return $this->error[0] ?? null;
    }
    
}