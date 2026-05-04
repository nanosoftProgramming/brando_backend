<?php

namespace Modules\Wishlist\Service;

use Modules\Client\App\Models\Client;
use Modules\Product\App\Models\Product;
use Modules\Wishlist\App\resources\ProductWishlistResource;

class WishlistService
{
    public function toggleProduct(Client $user, int $productId): array
    {
        if ($user->wishlistProducts()->where('product_id', $productId)->exists()) {
            $user->wishlistProducts()->detach($productId);

            return ['status' => true, 'message' => 'Removed from wishlist'];
        }

        $user->wishlistProducts()->attach($productId);

        return ['status' => true, 'message' => 'Added to wishlist'];
    }

    public function getUserWishlist(Client $user)
    {
        return $user->wishlistProducts()->with(['category', 'restaurant'])->get();
    }

    public function getAvailableProducts()
    {
        $user = auth('client')->user();

        $builder = Product::with(['category', 'restaurant'])
            ->active()
            ->filter(request()->all());

        $products = getCaseCollection($builder, request()->all());

        return ProductWishlistResource::collection($products->map(function ($product) use ($user) {
            $product->is_favorite = $user->wishlistProducts->contains($product->id);

            return $product;
        }));
    }
}
