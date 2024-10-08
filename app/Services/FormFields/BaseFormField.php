<?php

namespace App\Services\FormFields;

class BaseFormField
{
    public $name;
    public $label;
    public $type;
    public $value;
    public $options;
    public $required;
    public $visible;

    public function __construct($name, $label, $type, $value = null, $options = [], $required = false, $visible = null)
    {
        $this->name = $name;
        $this->label = $label;
        $this->type = $type;
        $this->value = $value;
        $this->options = $options;
        $this->required = $required;
        $this->visible = $visible ?? $name;
    }
}
