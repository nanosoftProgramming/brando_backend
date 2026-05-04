<?php

use Illuminate\Support\Facades\Route;
use Modules\Common\App\Http\Controllers\Api\CommonController;
use Modules\Common\App\Http\Controllers\Api\HistoryController;
use Modules\Common\App\Http\Controllers\Api\IntroController;
use Modules\Common\App\Http\Controllers\Api\SearchController;
use Modules\Common\App\Http\Controllers\Api\SliderAdminController;
use Modules\Common\App\Http\Controllers\Api\SliderController;

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

Route::post('contact', [CommonController::class, 'contact']);
Route::apiResource('history', HistoryController::class)->only(['index']);
Route::apiResource('intros', IntroController::class);

// Slider
Route::get('sliders', [SliderController::class, 'index']);

Route::group(['prefix' => 'admin'], function () {
    Route::apiResource('sliders', SliderAdminController::class)->except('update');
    Route::post('sliders/{slider}', [SliderAdminController::class, 'update']);
    Route::post('sliders/{slider}/toggle-activate', [SliderAdminController::class, 'toggleActivate']);
});

Route::prefix('search')->group(function () {
    Route::get('/', [SearchController::class, 'search']);
});
