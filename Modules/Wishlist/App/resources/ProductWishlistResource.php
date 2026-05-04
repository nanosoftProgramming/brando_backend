<?php

namespace Modules\Wishlist\App\resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductWishlistResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'base_price_egp' => $this->base_price_egp,
            'base_price_sar' => $this->base_price_sar,
            'image' => $this->image,
            'is_active' => $this->is_active,
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
            'is_favorite' => $this->is_favorite ?? false,
            'category' => [
                'id' => $this->category->id ?? null,
                'name' => $this->category->name ?? null,
            ],
            'restaurant' => [
                'id' => $this->restaurant->id ?? null,
                'name' => $this->restaurant->name ?? null,
                'image' => $this->restaurant->image ?? null,
            ],
        ];
    }
}
