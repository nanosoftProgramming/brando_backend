<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Driver\App\Http\Controllers\Api\DriverAdminController;
use Modules\Driver\App\Http\Controllers\Api\DriverAuthController;
use Modules\Driver\App\Http\Controllers\Api\DriverProfileController;

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
//    Route::get('driver', fn (Request $request) => $request->user())->name('driver');
// });

Route::group([
    'prefix' => 'driver',
], function ($router) {
    Route::group(['prefix' => 'auth'], function ($router) {
        Route::post('login', [DriverAuthController::class, 'login']);
        Route::post('logout', [DriverAuthController::class, 'logout']);
        Route::post('refresh', [DriverAuthController::class, 'refresh']);
        Route::post('me', [DriverAuthController::class, 'me']);
    });
    Route::post('change-password', [DriverProfileController::class, 'changePassword']);
    Route::post('update-profile', [DriverProfileController::class, 'updateProfile']);
});

Route::group(['prefix' => 'admin'], function () {

    Route::get('drivers', [DriverAdminController::class, 'index'])->name('drivers.index');

    Route::post('drivers', [DriverAdminController::class, 'store'])->name('drivers.store');

    Route::post('drivers/{id}', [DriverAdminController::class, 'update'])->name('drivers.update');

    Route::delete('drivers/{id}', [DriverAdminController::class, 'destroy'])->name('drivers.destroy');

    Route::post('drivers/{driver}/toggle-activate', [DriverAdminController::class, 'toggleActivate']);

});
