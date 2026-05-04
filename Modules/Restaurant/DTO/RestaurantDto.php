<?php

namespace Modules\Restaurant\DTO;

class RestaurantDto
{
    public $name;

    public $min_time;

    public $max_time;

    public function __construct($request)
    {
        if ($request->get('name')) {
            $this->name = $request->get('name');
        }
        if ($request->get('min_time')) {
            $this->min_time = $request->get('min_time');
        }
        if ($request->get('max_time')) {
            $this->max_time = $request->get('max_time');
        }
    }

    public function dataFromRequest()
    {
        $data = json_decode(json_encode($this), true);
        if ($this->name == null) {
            unset($data['name']);
        }
        if ($this->min_time == null) {
            unset($data['min_time']);
        }
        if ($this->max_time == null) {
            unset($data['max_time']);
        }

        return $data;
    }
}
