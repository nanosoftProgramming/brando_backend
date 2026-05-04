<?php

namespace Modules\Client\Service;

use Modules\Client\App\Models\Phone;

class PhoneVerificationService
{
    public function send(array $data)
    {
        $clientId = auth()->user()->id;

        $phone = Phone::firstOrCreate(
            ['phone' => $data['phone']],
            [
                'verified' => false,
                'client_id' => $clientId,
            ]
        );

        $code = 9999;
        $phone->verify_code = $code;
        $phone->save();
    }

    public function verify(array $data)
    {
        $phone = Phone::where('phone', $data['phone'])->first();

        if (! $phone || $phone->verify_code !== $data['code']) {
            throw new \Exception('Invalid phone phone or code');
        }

        $phone->is_verified = 1;
        $phone->verify_code = null;
        $phone->save();
    }
}
