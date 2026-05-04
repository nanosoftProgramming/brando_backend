<?php

namespace Modules\Product\App\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UsedProductResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'client_id' => $this->client_id,
            'category_id' => $this->category_id,
            'code' => $this->code,
            'name' => $this->name,
            'description' => $this->description,
            'price_egp' => (float) $this->price_egp,
            'price_sar' => (float) $this->price_sar,
            'discounted_price_egp' => (float) $this->discounted_price_egp,
            'discounted_price_sar' => (float) $this->discounted_price_sar,
            'image' => $this->image,
            'is_active' => $this->is_active,
            'is_sold' => $this->is_sold,
            'category' => $this->whenLoaded('category'),
            'client' => $this->whenLoaded('client'),
            'created_at' => $this->created_at?->format('Y-m-d h:i A'),
            'updated_at' => $this->updated_at?->format('Y-m-d h:i A'),
        ];
    }
}
