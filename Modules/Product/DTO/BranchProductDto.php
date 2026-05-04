<?php

namespace Modules\Product\DTO;

class BranchProductDto
{
    public $branch_id;

    public $products;

    public $custom_price_egp;

    public $custom_price_sar;

    public $is_active;

    public $product_id;

    public function __construct($request)
    {
        if ($request->get('products')) {
            $this->products = $request->get('products');
        }
        if ($request->get('product_id')) {
            $this->product_id = $request->get('product_id');
        }
        if ($request->get('branch_id')) {
            $this->branch_id = $request->get('branch_id');
        }
        if ($request->get('custom_price_egp')) {
            $this->custom_price_egp = $request->get('custom_price_egp');
        }
        if ($request->get('custom_price_sar')) {
            $this->custom_price_sar = $request->get('custom_price_sar');
        }
        if ($request->get('is_active')) {
            $this->is_active = $request->get('is_active');
        }
    }

    public function dataFromRequest()
    {
        $data = json_decode(json_encode($this), true);

        if ($this->products == null) {
            unset($data['products']);
        }

        if ($this->product_id == null) {
            unset($data['product_id']);
        }

        if ($this->branch_id == null) {
            unset($data['branch_id']);
        }
        if ($this->custom_price_egp == null) {
            unset($data['custom_price_egp']);
        }
        if ($this->custom_price_sar == null) {
            unset($data['custom_price_sar']);
        }
        if ($this->is_active == null) {
            unset($data['is_active']);
        }

        return $data;
    }
}
