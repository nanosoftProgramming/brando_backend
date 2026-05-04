<?php

namespace Modules\Common\DTO;

class SliderDto
{
    public $restaurant_id;

    public $admin_id;

    public function __construct($request)
    {
        $admin = auth('admin')->user();
        if ($admin->hasRole('Super Admin')) {
            $this->restaurant_id = $request->get('restaurant_id') ? $request->get('restaurant_id') : null;
        } else {
            $this->restaurant_id = $admin->restaurant_id;
        }
        $this->admin_id = $admin->hasRole('Super Admin') ? null : $admin->id;
    }

    public function dataFromRequest()
    {
        $data = json_decode(json_encode($this), true);
        if ($this->restaurant_id == null) {
            unset($data['restaurant_id']);
        }
        if ($this->admin_id == null) {
            unset($data['admin_id']);
        }

        return $data;
    }
}
