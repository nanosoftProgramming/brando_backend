<?php

namespace Modules\Product\DTO;

class AttributeDto
{
    public $name;

    public $required;

    public $multi_select;

    public $override_price;

    public $values;

    public function __construct($request)
    {
        if ($request->get('name')) {
            $this->name = $request->get('name');
        }
        if (isset($request['required'])) {
            $this->required = $request['required'] == true ? 1 : 0;
        }
        if (isset($request['multi_select'])) {
            $this->multi_select = $request['multi_select'] == true ? 1 : 0;
        }
        if (isset($request['override_price'])) {
            $this->override_price = $request->get('override_price') == true ? 1 : 0;
        }
        if ($request->get('values')) {
            foreach ($request->get('values') as $value) {
                $this->values[] = new AttributeValueDto($value);
            }
        }
    }

    public function dataFromRequest()
    {
        $data = json_decode(json_encode($this), true);

        return $data;
    }
}
