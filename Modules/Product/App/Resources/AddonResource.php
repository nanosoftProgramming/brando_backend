<?php

namespace Modules\Product\App\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AddonResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name ?? null,
            'price_egp' => $this->price_egp ?? null,
            'price_sar' => $this->price_sar ?? null,
            'image' => $this->image ?? null,
            'is_active' => $this->is_active ?? null,
            'created_at' => $this->created_at ? $this->created_at->format('Y-m-d h:i A') : null,
            'updated_at' => $this->updated_at ? $this->updated_at->format('Y-m-d h:i A') : null,
            'restaurant' => $this->restaurant ?? null,
        ];
    }
}
