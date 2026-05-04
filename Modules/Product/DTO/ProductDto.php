<?php

namespace Modules\Product\DTO;

class ProductDto
{
    public $restaurant_id;

    public $category_id;

    public $code;

    public $name;

    public $description;

    public $price_egp;

    public $price_sar;

    public $addons;

    public $attributes;

    public $discounted_price_egp;

    public $discounted_price_sar;

    public $is_active;

    public function __construct($request)
    {
        $admin = auth('admin')->user();
        if ($admin && !$admin->hasRole('Super Admin')) {
            $this->restaurant_id = $admin->restaurant_id;
        }else{
            $this->restaurant_id = $request->get('restaurant_id');
        }
        if ($request->get('category_id')) {
            $this->category_id = $request->get('category_id');
        }
        if ($request->get('code')) {
            $this->code = $request->get('code');
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
        if ($request->get('discounted_price_egp')) {
            $this->discounted_price_egp = $request->get('discounted_price_egp');
        }
        if ($request->get('discounted_price_sar')) {
            $this->discounted_price_sar = $request->get('discounted_price_sar');
        }
        if (!is_null($request->get('is_active'))) {
            $this->is_active = $request->get('is_active');
        }
        if ($request->get('addons')) {
            $this->addons = $request->get('addons');
        }
        if ($request->get('attributes')) {
            $this->attributes = $request->get('attributes');
        }
        if (!$request->route('product')) {
            $this->is_active = 0;
        }
    }

    public function dataFromRequest()
    {
        $data = json_decode(json_encode($this), true);
        if ($this->category_id == null) {
            unset($data['category_id']);
        }
        if ($this->code == null) {
            unset($data['code']);
        }
        if ($this->name == null) {
            unset($data['name']);
        }
        if ($this->description == null) {
            unset($data['description']);
        }
        if ($this->price_egp == null) {
            unset($data['price_egp']);
        }
        if ($this->price_sar == null) {
            unset($data['price_sar']);
        }
        if ($this->addons == null) {
            unset($data['addons']);
        }
        if ($this->attributes == null) {
            unset($data['attributes']);
        }
        if ($this->discounted_price_egp == null) {
            unset($data['discounted_price_egp']);
        }
        if ($this->discounted_price_sar == null) {
            unset($data['discounted_price_sar']);
        }
        if($this->is_active == null) {
            unset($data['is_active']);
        }

        return $data;
    }
}
