<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Branch\App\Http\Controllers\Api\BranchAdminController;

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
//     Route::get('branch', fn (Request $request) => $request->user())->name('branch');
// });

Route::prefix('admin/branches')->group(function () {
    Route::get('/', [BranchAdminController::class, 'index']);
    Route::get('{branch}', [BranchAdminController::class, 'show']);
    Route::post('/', [BranchAdminController::class, 'store']);
    Route::post('{branch}', [BranchAdminController::class, 'update']);
    Route::delete('{branch}', [BranchAdminController::class, 'destroy']);
    Route::post('{branch}/toggle-activate', [BranchAdminController::class, 'toggleActivate']);
});
