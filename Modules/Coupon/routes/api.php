<?php

use Illuminate\Support\Facades\Route;
use Modules\Coupon\App\Http\Controllers\Api\CouponAdminController;
use Modules\Coupon\App\Http\Controllers\Api\CouponController;

/*
    |--------------------------------------------------------------------------
    | API Routes
    |--------------------------------------------------------------------------
    |
    | Here is where you can register API routes for your application. These
    | routes are loaded by the RouteServiceProvider within a group which
    | is assigned the "api" middleware group. Enjoy building your API!
    |
*/

Route::group(['prefix' => 'admin'], function () {
    Route::apiResource('coupons', CouponAdminController::class)->except('update');
    Route::post('coupons/{coupon}', [CouponAdminController::class, 'update']);
    Route::post('coupons/{coupon}/toggle-activate', [CouponAdminController::class, 'activate']);
});

Route::get('coupons/check', [CouponController::class, 'check']);
