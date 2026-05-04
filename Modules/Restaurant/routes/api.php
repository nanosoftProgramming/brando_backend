<?php

use Illuminate\Support\Facades\Route;
use Modules\Restaurant\App\Http\Controllers\Api\RestaurantAdminController;
use Modules\Restaurant\App\Http\Controllers\Api\RestaurantController;

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
    Route::apiResource('restaurants', RestaurantAdminController::class)->except('update');
    Route::post('restaurants/{restaurant}', [RestaurantAdminController::class, 'update']);
    Route::post('restaurants/{restaurant}/toggle-activate', [RestaurantAdminController::class, 'toggleActivate']);
});

Route::get('restaurants', [RestaurantController::class, 'index']);
Route::get('categories/{category}/restaurants', [RestaurantController::class, 'getByCategory']);
