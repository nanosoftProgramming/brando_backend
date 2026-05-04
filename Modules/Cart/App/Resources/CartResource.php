<?php

namespace Modules\Cart\App\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CartResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'item_count' => $this->item_count,
            'branch_name' => $this->branch_name,
            'products' => CartItemResource::collection($this->products),

        ];
    }
}
