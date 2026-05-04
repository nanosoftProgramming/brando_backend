<?php

namespace Modules\Order\DTO;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class OrderDto
{
    public $payment_method_id;

    public $client_id;

    public $address_id;

    public $city_id;

    public $zone_id;

    public $sub_zone_id;

    public $coupon;

    public $order_method_id;

    public $items;

    public $notes;

    public $delivery;

    public $points_discount;

    public $image;

    public function __construct($request)
    {
        $this->client_id = Auth::id();
        if ($request->get('payment_method_id')) {
            $this->payment_method_id = $request->get('payment_method_id');
        }
        if ($request->get('coupon')) {
            $this->coupon = $request->get('coupon');
        }
        if ($request->get('notes')) {
            $this->notes = $request->get('notes');
        }
        if ($request->get('address_id')) {
            $this->address_id = $request->get('address_id');
        }
        if ($request->get('city_id')) {
            $this->city_id = $request->get('city_id');
        }
        if ($request->get('zone_id')) {
            $this->zone_id = $request->get('zone_id');
        }
        if ($request->get('sub_zone_id')) {
            $this->sub_zone_id = $request->get('sub_zone_id');
        }
        $this->order_method_id = $request->get('order_method_id');
        $this->delivery = $request->get('delivery');
        $this->points_discount = $request->get('points_discount');
        $this->items = $request->get('items');
        if ($request->hasFile('image')) {
            $this->image = $request->file('image');
        }
    }

    public function dataFromRequest()
    {
        $data = json_decode(json_encode($this), true);
        $data['link_code'] = $this->generateLinkCode();
        $data['order_status_id'] = 1;
        if ($data['delivery'] == 'today') {
            $data['delivery_date'] = Carbon::now()->toDateString();
        } else {
            $data['delivery_date'] = Carbon::tomorrow()->toDateString();
        }
        if ($data['image'] == null) {
            unset($data['image']);
        }

        return array_filter($data);
    }

    //    private function getOrderNumber()
    //    {
    //        $latestOrder = Order::orderBy('created_at','DESC')->first();
    //        $lastId = $latestOrder != null ? $latestOrder->id+1 : 1;
    // return '#' . str_pad($lastId, 6, "0", STR_PAD_LEFT);
    //        return '#0' . $lastId;
    //    }

    private function generateLinkCode()
    {
        $serial = 'PH-';
        $today = date('Ymd');
        $rand = strtoupper(substr(uniqid(sha1(time())), 0, 4));

        return $serial.$today.$rand;
    }
}
