<?php

namespace Modules\Client\App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Client\DTO\PhoneVerificationDto;
use Modules\Client\Service\PhoneVerificationService;

class PhoneVerificationController extends Controller
{
    protected $phoneService;

    public function __construct(PhoneVerificationService $phoneService)
    {
        $this->middleware('auth:client');
        $this->phoneService = $phoneService;
    }

    // Step 1: Send OTP
    public function send(Request $request)
    {
        $data = (new PhoneVerificationDto($request))->dataFromRequest();
        $this->phoneService->send($data);

        return returnMessage(true, 'OTP sent successfully');
    }

    // Step 2: Verify OTP
    public function verify(Request $request)
    {
        $data = (new PhoneVerificationDto($request))->dataFromRequest();
        $this->phoneService->verify($data);

        return returnMessage(true, 'Phone verified successfully');
    }
}
