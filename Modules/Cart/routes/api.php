<?php

use Illuminate\Support\Facades\Route;
use Modules\Cart\App\Http\Controllers\Api\CartController;
use Modules\Cart\App\Http\Controllers\Api\CartPaymentController;

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

Route::prefix('cart')->group(function () {
    Route::get('/', [CartController::class, 'index']);
    Route::post('/', [CartController::class, 'store']);
    Route::post('/{cartItem}', [CartController::class, 'update']);
    Route::delete('/{cartItem}', [CartController::class, 'destroy']);
    Route::delete('/', [CartController::class, 'clear']);

    Route::post('/{cartItem}/increment', [CartController::class, 'increment']);
    Route::post('/{cartItem}/decrement', [CartController::class, 'decrement']);

    Route::post('/addon/{cartItemAddon}/increment', [CartController::class, 'incrementAddon']);
    Route::post('/addon/{cartItemAddon}/decrement', [CartController::class, 'decrementAddon']);

    Route::get('/summary', [CartController::class, 'summary']);
});

Route::prefix('cart/payment')->group(function () {
    Route::get('/summary', [CartPaymentController::class, 'getPaymentSummary']);
    Route::post('/initiate', [CartPaymentController::class, 'initiatePayment']);
    Route::get('/payments/verify/{payment?}', [CartPaymentController::class, 'paymentCallback'])
        ->name('verify-payment');
});
