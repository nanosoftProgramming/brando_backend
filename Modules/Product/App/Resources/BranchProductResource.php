<?php

namespace Modules\Product\App\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BranchProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {

        return [
            'id' => $this->id,
            'branch_id' => $this->branch_id,
            'branch_name' => $this->branch->name ?? null,
            'product_id' => $this->product_id,
            'product_name' => $this->product->name ?? null,
            'price_egp' => $this->custom_price_egp ? $this->custom_price_egp : $this->product->base_price_egp ?? null,
            'price_sar' => $this->custom_price_sar ? $this->custom_price_sar : $this->product->base_price_sar ?? null,
            'description' => $this->product->description ?? null,
            'image' => $this->product->image ?? null,
            'category' => $this->product->category->name ?? null,
            'sub_category' => $this->product->subCategory->name ?? null,
            'is_active' => $this->is_active,
            'is_best_seller' => $this->is_best_seller ?? false,
            'created_at' => $this->created_at->format('Y-m-d'),
            'updated_at' => $this->updated_at->format('Y-m-d'),
        ];
    }
}
