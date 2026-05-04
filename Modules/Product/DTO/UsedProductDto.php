<?php

namespace Modules\Product\DTO;

class UsedProductDto
{
    public $client_id;

    public $category_id;

    public $code;

    public $name;

    public $description;

    public $price_egp;

    public $price_sar;

    public $discounted_price_egp;

    public $discounted_price_sar;

    public $image;

    public $is_active;

    public $is_sold;

    public function __construct($request)
    {
        $client = auth('client')->user();
        if ($client) {
            $this->client_id = $client->id;
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

        if (!$request->route('id')) {
            $this->is_active = 1;
            $this->is_sold = 0;
        }
    }

    public function dataFromRequest()
    {
        $data = json_decode(json_encode($this), true);

        if ($this->client_id == null) {
            unset($data['client_id']);
        }
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
        if ($this->discounted_price_egp == null) {
            unset($data['discounted_price_egp']);
        }
        if ($this->discounted_price_sar == null) {
            unset($data['discounted_price_sar']);
        }
        if($this->is_active == null) {
            unset($data['is_active']);
        }
        if($this->is_sold == null) {
            unset($data['is_sold']);
        }
        return $data;
    }
}
