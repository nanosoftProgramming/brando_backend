<?php

namespace Modules\Order\App\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderAdminResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,

            'status' => $this->status?->name,

            'payment_type' => $this->payment_type,

            'total_items' => $this->items?->count() ?? 0,

            'total_price' => $this->total,
            'sub_total' => $this->sub_total,
            'taxes' => $this->taxes,

            'created_at' => optional($this->created_at)->format('Y-m-d h:i A'),

            'client' => [
                'id' => $this->client?->id,
                'name' => $this->client?->name,
                'email' => $this->client?->email,
                'phone' => $this->client?->phone,
            ],

            'address' => [
                'id' => $this->address?->id,
                'title' => $this->address?->title,
                'block' => $this->address?->block,
                'street' => $this->address?->street,
                'house_number' => $this->address?->house_number,
                'notes' => $this->address?->notes,
                'city' => $this->address?->city?->name,
                'phone' => $this->address?->phone?->phone,
            ],

            'branch' => [
                'id' => $this->branch?->id,
                'name' => $this->branch?->name,
            ],

            'items' => $this->items?->map(function ($item) {
                return [
                    'id' => $item->id,
                    'quantity' => $item->quantity,
                    'price' => $item->price,
                    'total' => $item->total,
                    'product' => [
                        'id' => $item->branch_product?->product?->id,
                        'name' => $item->branch_product?->product?->name,
                        'image' => $item->branch_product?->product?->image,
                    ],
                ];
            }) ?? [],

            'rate' => $this->whenLoaded('rate'),
        ];
    }
}