<?php

namespace Modules\Wishlist\App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Wishlist\App\resources\ProductWishlistResource;
use Modules\Wishlist\Service\WishlistService;

class WishlistController extends Controller
{
    private $wishlistService;

    public function __construct(WishlistService $wishlistService)
    {
        $this->middleware('auth:client');
        $this->wishlistService = $wishlistService;
    }

    public function toggle($productId)
    {
        try {
            DB::beginTransaction();
            $user = auth('client')->user();
            $result = $this->wishlistService->toggleProduct($user, $productId);
            DB::commit();

            return returnMessage(true, $result['message'], null);
        } catch (Exception $e) {
            DB::rollBack();

            return returnMessage(false, $e->getMessage(), null, 500);
        }
    }

    public function index(Request $request)
    {
        try {
            $user = auth('client')->user();
            $wishlistProducts = $this->wishlistService->getUserWishlist($user);

            return returnMessage(
                true,
                'Wishlist fetched successfully',
                ProductWishlistResource::collection($wishlistProducts)->response()->getData(true)
            );

        } catch (Exception $e) {
            return returnMessage(false, $e->getMessage(), null, 500);
        }
    }

    public function getAvailableProducts(Request $request)
    {
        try {
            $availableProducts = $this->wishlistService->getAvailableProducts();

            return returnMessage(
                true,
                'Available products fetched successfully',
                $availableProducts->response()->getData(true)
            );

        } catch (Exception $e) {
            return returnMessage(false, $e->getMessage(), null, 500);
        }
    }
}
