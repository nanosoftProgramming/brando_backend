<?php

namespace Modules\Coupon\App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Coupon\App\Http\Requests\CouponRequest;
use Modules\Coupon\App\Models\Coupon;
use Modules\Coupon\DTO\CouponDto;
use Modules\Coupon\Service\CouponService;

class CouponAdminController extends Controller
{
    private $couponService;

    public function __construct(CouponService $couponService)
    {
        $this->middleware(['auth:admin']);
        $this->couponService = $couponService;
    }

    public function index(Request $request)
    {
        $data = $request->all();
        $coupons = $this->couponService->findAll($data);

        return returnMessage(true, 'Coupons Fetched Successfully', $coupons);
    }

    public function store(CouponRequest $request)
    {
        try {
            DB::beginTransaction();
            $data = (new CouponDto($request))->dataFromRequest();
            $coupon = $this->couponService->save($data);
            DB::commit();

            return returnMessage(true, 'Coupon Created Successfully', $coupon);
        } catch (\Exception $e) {
            DB::rollBack();

            return returnMessage(false, $e->getMessage(), null, 'server_error');
        }
    }

    public function update(CouponRequest $request, Coupon $coupon)
    {
        try {
            DB::beginTransaction();
            $data = (new CouponDto($request))->dataFromRequest();
            $coupon = $this->couponService->update($coupon, $data);
            DB::commit();

            return returnMessage(true, 'Coupon Updated Successfully', $coupon);
        } catch (\Exception $e) {
            DB::rollBack();

            return returnMessage(false, $e->getMessage(), null, 'server_error');
        }
    }

    public function destroy(Coupon $coupon)
    {
        try {
            DB::beginTransaction();
            $this->couponService->delete($coupon);
            DB::commit();

            return returnMessage(true, 'Coupon Deleted Successfully', null);
        } catch (\Exception $e) {
            DB::rollBack();

            return returnMessage(false, $e->getMessage(), null, 'server_error');
        }
    }

    public function activate(Coupon $coupon)
    {
        try {
            DB::beginTransaction();
            $this->couponService->activate($coupon);
            DB::commit();

            return returnMessage(true, 'Coupon Activated Successfully', $coupon);
        } catch (\Exception $e) {
            DB::rollBack();

            return returnMessage(false, $e->getMessage(), null, 'server_error');
        }
    }
}
