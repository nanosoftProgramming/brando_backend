<?php

use Illuminate\Support\Facades\Route;
use Modules\Country\App\Http\Controllers\Api\CountryController;

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

Route::get('countries', [CountryController::class, 'countries']);
Route::get('countries/{country}/cities', [CountryController::class, 'cities']);
