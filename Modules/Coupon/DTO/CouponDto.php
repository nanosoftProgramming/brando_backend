<?php

namespace Modules\Coupon\DTO;

class CouponDto
{
    public $code;

    public $num_of_uses;

    public $type;

    public $value;

    public $limit;

    public $client_uses;

    public $date_from;

    public $date_to;

    public $time_from;

    public $time_to;

    public $branches;

    public $discount_on;

    public function __construct($request)
    {

        if ($request->get('code')) {
            $this->code = $request->get('code');
        }
        // if ($request->get('num_of_uses'))
        //     $this->num_of_uses = $request->get('num_of_uses');
        $this->num_of_uses = 10;
        // if ($request->get('client_uses'))
        //     $this->client_uses = $request->get('client_uses');
        $this->client_uses = 1;
        if ($request->get('type')) {
            $this->type = $request->get('type');
        }
        // if ($request->get('discount_on'))
        //     $this->discount_on = $request->get('discount_on');
        $this->discount_on = 1;
        if ($request->get('value')) {
            $this->value = $request->get('value');
        }
        // if ($request->get('limit'))
        //     $this->limit = $request->get('limit');
        $this->limit = 0;
        if ($request->get('date_from')) {
            $this->date_from = $request->get('date_from');
        }
        if ($request->get('date_to')) {
            $this->date_to = $request->get('date_to');
        }
        if ($request->get('time_from')) {
            $this->time_from = $request->get('time_from');
        }
        if ($request->get('time_to')) {
            $this->time_to = $request->get('time_to');
        }
    }

    public function dataFromRequest()
    {
        $data = json_decode(json_encode($this), true);
        if ($this->date_from == null) {
            unset($data['date_from']);
        }
        if ($this->date_to == null) {
            unset($data['date_to']);
        }
        if ($this->time_from == null) {
            unset($data['time_from']);
        }
        if ($this->time_to == null) {
            unset($data['time_to']);
        }

        return $data;
    }
}
