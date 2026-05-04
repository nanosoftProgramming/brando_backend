<?php

use Illuminate\Support\Facades\Route;
use Modules\Product\App\Http\Controllers\Api\AddonAdminController;
use Modules\Product\App\Http\Controllers\Api\AttributeAdminController;
use Modules\Product\App\Http\Controllers\Api\BranchProductAdminController;
use Modules\Product\App\Http\Controllers\Api\BranchProductController;
use Modules\Product\App\Http\Controllers\Api\ProductAdminController;
use Modules\Product\App\Http\Controllers\Api\ProductController;
use Modules\Product\App\Http\Controllers\Api\UsedProductController;

Route::prefix('admin')->group(function () {
    Route::get('products', [ProductAdminController::class, 'index']);
    Route::post('products', [ProductAdminController::class, 'store']);
    Route::get('products/{product}', [ProductAdminController::class, 'show']);
    Route::post('products/{product}', [ProductAdminController::class, 'update']);
    Route::delete('products/{product}', [ProductAdminController::class, 'destroy']);
    Route::put('products/{product}/toggle-activate', [ProductAdminController::class, 'activate']);

    Route::apiResource('attributes', AttributeAdminController::class)->only(['index', 'store', 'destroy']);
    Route::post('attributes/{attribute}', [AttributeAdminController::class, 'update']);
    Route::post('attributes/{attribute}/toggle-activate', [AttributeAdminController::class, 'activate']);

    Route::apiResource('addons', AddonAdminController::class)->only(['index', 'store', 'destroy']);
    Route::post('addons/{addon}', [AddonAdminController::class, 'update']);
    Route::post('addons/{addon}/toggle-activate', [AddonAdminController::class, 'activate']);
});

Route::prefix('admin/branch-products')->middleware(['auth:admin', 'role:Super Admin|Restaurant Manager'])->group(function () {
    Route::get('/{branchId}', [BranchProductAdminController::class, 'index']);
    Route::post('/', [BranchProductAdminController::class, 'store']);
    Route::put('/{branchProduct}', [BranchProductAdminController::class, 'update']);
    Route::delete('/{branchProduct}', [BranchProductAdminController::class, 'destroy']);
});

Route::prefix('branch-products')->middleware('auth:client')->group(function () {
    Route::get('/{branchId}', [BranchProductController::class, 'index']);
});

Route::get('products', [ProductController::class, 'index']);
Route::get('restaurants/{restaurant}/products', [ProductController::class, 'getByRestaurant']);

Route::get('sale-products', [ProductController::class, 'saleProducts']);
Route::get('recently-products', [ProductController::class, 'recentlyProducts']);
Route::get('best-selling-products', [ProductController::class, 'bestSellingProducts']);
Route::get('highest-rated-products', [ProductController::class, 'highestRatedProducts']);
Route::get('related-products/{productId}', [ProductController::class, 'relatedProducts']);

Route::get('used-products', [UsedProductController::class, 'index']);
Route::post('used-products', [UsedProductController::class, 'store']);
Route::get('used-products/my-listings', [UsedProductController::class, 'myListings']);
Route::get('used-products/{id}', [UsedProductController::class, 'show']);
Route::post('used-products/{id}', [UsedProductController::class, 'update']);
Route::delete('used-products/{id}', [UsedProductController::class, 'destroy']);
