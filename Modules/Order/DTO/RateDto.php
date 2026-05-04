<?php

namespace Modules\Order\DTO;

class RateDto
{
    public $order_id;

    public $rate;

    public $restaurant_rate;

    public $comment;

    public $restaurant_comment;

    public $products;

    public $client_id;

    public function __construct($request, $order)
    {
        $this->client_id = auth('client')->id();
        $this->order_id = $order->id;
        if ($request->get('rate')) {
            $this->rate = $request->get('rate');
        }
        if ($request->get('restaurant_rate')) {
            $this->restaurant_rate = $request->get('restaurant_rate');
        }
        if ($request->get('comment')) {
            $this->comment = $request->get('comment');
        }
        if ($request->get('restaurant_comment')) {
            $this->restaurant_comment = $request->get('restaurant_comment');
        }
        if ($request->get('products')) {
            $this->products = $request->get('products');
        }
    }

    public function dataFromRequest(): array
    {
        $data = json_decode(json_encode($this), true);
        if ($this->products == null) {
            unset($data['products']);
        }
        if ($this->comment == null) {
            unset($data['comment']);
        }
        if ($this->restaurant_comment == null) {
            unset($data['restaurant_comment']);
        }
        if ($this->restaurant_rate == null) {
            unset($data['restaurant_rate']);
        }

        return $data;
    }
}
