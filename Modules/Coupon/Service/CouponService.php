<?php

namespace Modules\Coupon\Service;

use Carbon\Carbon;
use Modules\Coupon\App\Models\Coupon;
use Modules\Order\App\Models\Order;

class CouponService
{
    public function findAll($data = [], $relation = [])
    {
        $coupons = Coupon::with($relation)->latest();

        return getCaseCollection($coupons, $data);
    }

    public function active()
    {
        return Coupon::active()->get();
    }

    public function findById($id, $relation = [])
    {
        return Coupon::with($relation)->findOrFail($id);
    }

    public function findBy($key, $value)
    {
        return Coupon::where($key, $value)->get();
    }

    public function save($data)
    {
        $coupon = Coupon::create($data);

        // $coupon->branches()->sync($data['branches']);
        return $coupon;
    }

    public function update($coupon, $data)
    {
        $coupon->update($data);

        return $coupon;
    }

    public function activate($coupon)
    {
        $coupon->is_active = ! $coupon->is_active;
        $coupon->save();
    }

    public function delete($coupon)
    {
        $coupon->delete();
    }

    public function checkCoupon($code, $client_id)
    {
        $coupon = Coupon::active()->where('code', $code)->first();
        if (! $coupon) {
            return returnMessage(false, 'Coupon Not Found', null, 'not_found');
        }
        // if ($coupon['counter'] >= $coupon['num_of_uses'])
        //     return returnMessage(false, 'Coupon Has been ended', null, 'not_acceptable');
        // if (!$coupon->branches()->wherePivot('branch_id', $branch_id)->first()) return returnMessage(false, 'Coupon Not Available to this branch', null, 'not_acceptable');
        // if(Order::whereClientId($client_id)->whereCouponId($coupon->id)->count() >= $coupon['client_uses'] ) return returnMessage(false,'Coupon is no longer used for this client', null, 'not_acceptable');
        if ($coupon['date_from'] ?? null && $coupon['date_to'] ?? null) {
            if (! ($coupon['date_from'] <= Carbon::today()->toDateString() && $coupon['date_to'] >= Carbon::today()->toDateString())) {
                return returnMessage(false, 'Coupon Not Available in this date', null, 'not_acceptable');
            }
        }
        if ($coupon['time_from'] ?? null && $coupon['time_to'] ?? null) {
            if (! ($coupon['time_from'] <= Carbon::now()->toTimeString() && $coupon['time_to'] >= Carbon::now()->toTimeString())) {
                return returnMessage(false, 'Coupon Not Available in this time', null, 'not_acceptable');
            }
        }

        return $coupon['id'];
    }
}
