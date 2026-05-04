<?php

use Illuminate\Support\Facades\Route;
use Modules\Wishlist\App\Http\Controllers\Api\WishlistController;

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

Route::middleware(['auth:client'])->group(function () {
    Route::post('wishlist/toggle/{productId}', [WishlistController::class, 'toggle']);
    Route::get('wishlist/products', [WishlistController::class, 'getAvailableProducts']);
    Route::get('wishlist', [WishlistController::class, 'index']);
});
