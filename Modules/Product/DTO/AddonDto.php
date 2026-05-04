<?php

namespace Modules\Product\DTO;

class AddonDto
{
    public $restaurant_id;

    public $name;

    public $price_egp;

    public $price_sar;

    public $image;

    public function __construct($request)
    {
        if (auth('admin')->check()) {
            $admin = auth('admin')->user();
            if ($admin->hasRole('Super Admin')) {
                if ($request->get('restaurant_id')) {
                    $this->restaurant_id = $request->get('restaurant_id');
                }
            } elseif ($admin->hasRole('Restaurant Manager') && $admin->restaurant_id) {
                $this->restaurant_id = $admin->restaurant_id;
            }
        }
        if ($request->get('name')) {
            $this->name = $request->get('name');
        }
        if ($request->get('description')) {
            $this->description = $request->get('description');
        }
        if ($request->get('price_egp')) {
            $this->price_egp = $request->get('price_egp');
        }
        if ($request->get('price_sar')) {
            $this->price_sar = $request->get('price_sar');
        }
    }

    public function dataFromRequest()
    {
        $data = json_decode(json_encode($this), true);

        if ($this->name == null) {
            unset($data['name']);
        }
        if ($this->price_egp == null) {
            unset($data['price_egp']);
        }
        if ($this->price_sar == null) {
            unset($data['price_sar']);
        }
        if ($this->image == null) {
            unset($data['image']);
        }

        return $data;
    }
}
