<?php

namespace Modules\Order\DTO;

class OrderDto
{
    public $payment_method;

    public $client_id;

    public $address_id;

    public $coupon_id;

    public $note;

    public $currency;

    public function __construct($request)
    {
        $this->client_id = auth('client')->id();

        if ($request->get('payment_type')) {
            $this->payment_method = $request->get('payment_type');
        }

        if ($request->get('coupon_id')) {
            $this->coupon_id = $request->get('coupon_id');
        }

        if ($request->get('note')) {
            $this->note = $request->get('note');
        }

        if ($request->get('address_id')) {
            $this->address_id = $request->get('address_id');
        }
        $this->currency = strtolower($request->header('Currency', 'egp'));

        if (! in_array($this->currency, ['egp', 'sar'])) {
            $this->currency = 'egp';
        }
    }

    public function dataFromRequest(): array
    {
        $data = [
            'address_id' => $this->address_id,
            'payment_method' => $this->payment_method,
            'coupon_id' => $this->coupon_id,
            'note' => $this->note,
            'currency' => $this->currency,
        ];

        return array_filter($data, fn ($value) => $value !== null);
    }
}
