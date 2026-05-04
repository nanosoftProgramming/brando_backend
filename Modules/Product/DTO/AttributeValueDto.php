<?php

namespace Modules\Product\DTO;

class AttributeValueDto
{
    public $attribute_value;

    public function __construct($value)
    {
        $this->attribute_value = $value['attribute_value'];
    }

    public function dataFromRequest()
    {
        $data = json_decode(json_encode($this), true);

        return $data;
    }
}
