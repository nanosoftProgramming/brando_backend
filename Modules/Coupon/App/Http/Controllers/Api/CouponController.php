<?php

namespace Modules\Coupon\App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Coupon\Service\CouponService;

class CouponController extends Controller
{
    private $couponService;

    public function __construct(CouponService $couponService)
    {
        $this->middleware(['auth:client'])->only('check');
        $this->couponService = $couponService;
    }

    public function check(Request $request)
    {
        $response = $this->couponService->checkCoupon($request['code'], auth('client')->id());
        if (! is_int($response)) {
            return $response;
        }
        $data = $this->couponService->findById($response);

        return returnMessage(true, 'Coupon Data', $data);
    }

    public function index()
    {
        $data = $this->couponService->findAll([]);

        return returnMessage(true, 'Coupons', $data);
    }
}
