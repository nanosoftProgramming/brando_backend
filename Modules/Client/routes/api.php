<?php

use Illuminate\Support\Facades\Route;
use Modules\Client\App\Http\Controllers\Api\AddressController;
use Modules\Client\App\Http\Controllers\Api\ClientAdminController;
use Modules\Client\App\Http\Controllers\Api\ClientAuthController;
use Modules\Client\App\Http\Controllers\Api\ClientController;
use Modules\Client\App\Http\Controllers\Api\PhoneVerificationController;

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

Route::group([
    'prefix' => 'client',
], function ($router) {
    Route::group(['prefix' => 'auth'], function ($router) {
        Route::post('login', [ClientAuthController::class, 'login']);
        Route::post('logout', [ClientAuthController::class, 'logout']);
        Route::post('register', [ClientAuthController::class, 'register']);
        Route::post('verify', [ClientAuthController::class, 'verifyOtp']);
        Route::post('refresh', [ClientAuthController::class, 'refresh']);
        Route::get('me', [ClientAuthController::class, 'me']);
        Route::post('check-phone-exists', [ClientAuthController::class, 'checkPhoneExists']);
        Route::post('social-login', [ClientAuthController::class, 'socialLogin']);
    });
    Route::post('change-password', [ClientController::class, 'changePassword']);
    Route::post('update-profile', [ClientController::class, 'updateProfile']);
});

Route::prefix('client')->middleware(['auth:client'])->group(function () {

    Route::prefix('addresses')->group(function () {
        Route::get('/', [AddressController::class, 'index']);
        Route::post('/', [AddressController::class, 'store']);
        Route::get('/{address}', [AddressController::class, 'show']);
        // Some clients send updates as POST instead of PUT/PATCH
        Route::post('/{address}', [AddressController::class, 'update']);
        Route::put('/{address}', [AddressController::class, 'update']);
        Route::delete('/{address}', [AddressController::class, 'destroy']);
    });

});

Route::prefix('client/phone')->middleware('auth:client')->group(function () {
    Route::post('send-otp', [PhoneVerificationController::class, 'send']);
    Route::post('verify-otp', [PhoneVerificationController::class, 'verify']);
});

Route::group(['prefix' => 'admin'], function () {
    Route::get('clients', [ClientAdminController::class, 'index']);
    Route::post('clients/{client}/toggle-activate', [ClientAdminController::class, 'toggleActivate']);
});
