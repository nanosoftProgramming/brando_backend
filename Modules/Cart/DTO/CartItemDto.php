<?php

namespace Modules\Cart\DTO;

class CartItemDto
{
    public $product_id;

    public $quantity;

    public $notes;

    public $addons;

    public $attributes;

    public function __construct($request)
    {
        $this->product_id = $request->get('product_id');
        $this->quantity = $request->get('quantity', 1);
        $this->notes = $request->get('notes');
        $this->addons = $request->get('addons', []);
        $this->attributes = $request->get('attributes', []);
    }

    public function dataFromRequest()
    {
        $data = [];

        if ($this->product_id !== null) {
            $data['product_id'] = $this->product_id;
        }

        if ($this->quantity !== null) {
            $data['quantity'] = $this->quantity;
        }

        if ($this->notes !== null) {
            $data['notes'] = $this->notes;
        }

        if (! empty($this->addons)) {
            $data['addons'] = $this->addons;
        }

        if (! empty($this->attributes)) {
            $data['attributes'] = $this->attributes;
        }

        return $data;
    }

    public function validate()
    {
        $errors = [];
        if (empty($this->product_id)) {
            $errors[] = 'Product ID is required';
        }
        if ($this->quantity <= 0) {
            $errors[] = 'Quantity must be greater than 0';
        }

        return $errors;
    }
}
