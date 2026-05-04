<?php

namespace Modules\Client\DTO;

use Illuminate\Http\Request;

class PhoneVerificationDto
{
    public $phone;

    public $code;

    public function __construct(Request $request)
    {

        if ($request->get('phone')) {
            $this->phone = $request->get('phone');
        }
        if ($request->get('code')) {
            $this->code = $request->get('code');
        }

    }

    public function dataFromRequest()
    {
        $data = json_decode(json_encode($this), true);

        if ($this->phone == null) {
            unset($data['phone']);
        }
        if ($this->code == null) {
            unset($data['code']);
        }

        return $data;
    }
}
