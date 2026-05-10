<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Order\App\Http\Controllers\Api\OrderAdminController;
use Modules\Order\App\Http\Controllers\Api\OrderController;
use Modules\Order\App\Http\Controllers\Api\OrderStatusController;
use Modules\Order\App\Http\Controllers\Api\RateController;

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

// Route::middleware(['auth:sanctum'])->prefix('v1')->name('api.')->group(function () {
//     Route::get('order', fn (Request $request) => $request->user())->name('order');
// });
Route::middleware('auth:client')->prefix('orders')->group(function () {
    Route::get('/', [OrderController::class, 'index']);
    Route::post('/', [OrderController::class, 'store']);
    Route::post('/{order}/rate', [RateController::class, 'store']);
});

Route::middleware(['auth:admin', 'role:Super Admin|Restaurant Manager|Branch Manager'])
    ->prefix('admin/orders')
    ->group(function () {

        Route::get('/', [OrderAdminController::class, 'index']);
        Route::get('{id}', [OrderAdminController::class, 'show']);

        Route::put('{order}/status', [OrderAdminController::class, 'updateStatus']);

        Route::delete('{id}', [OrderAdminController::class, 'destroy']);

    });

Route::prefix('admin/order-statuses')->group(function () {
    Route::get('/', [OrderStatusController::class, 'getStatuses']);
});
