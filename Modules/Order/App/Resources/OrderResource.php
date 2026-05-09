<?php

namespace Modules\Order\App\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    public function toArray($request)
    {
      $discountValue = (float) $this->discount; // القيمة المخزنة أصلاً

if ($this->relationLoaded('coupon') && $this->coupon) {
             if ($this->coupon->type == 'fixed') {
                 $discountValue = (float) $this->coupon->value;
             } else {
                 // الخصم بالنسبة المئوية
                 $discountValue = ($this->subtotal * $this->coupon->value) / 100;
             }
        }
      $finalTotal = ($this->subtotal - $discountValue) + $this->tax + $this->delivery_fee;
        return [
            // Order Basic Info
            'id' => $this->id,
            'order_no' => $this->order_no,
            'currency' => $this->currency,
            'subtotal' => (float) $this->subtotal,
            // 'discount' => (float) $this->discount,
            'discount' => $discountValue, // القيمة الجديدة بعد الحساب
            'discount_type' => $this->discount_type,
            'tax' => (float) $this->tax,
            'delivery_fee' => (float) $this->delivery_fee,
            'total' => (float) max($finalTotal, 0), // المجموع النهائي المتأثر بالكوبون
            // 'total' => (float) $this->total,
            'quantity' => (int) $this->quantity,
            'payment_method' => $this->payment_method,
            'note' => $this->note,

            // Relations - Return Full Data
            'status' => $this->whenLoaded('status'),
            'client' => $this->whenLoaded('client'),
            'restaurant' => $this->whenLoaded('restaurant'),
            'address' => $this->whenLoaded('address'),
            'coupon' => $this->whenLoaded('coupon'),

            // Order Details with Attributes & Addons
            'details' => $this->whenLoaded('details', function () {
                return $this->details->map(function ($detail) {
                    $itemType = $detail->item_type ?? 'product';

                    return [
                        'id' => $detail->id,
                        'item_type' => $itemType,
                        'quantity' => $detail->quantity,
                        'price' => (float) $detail->price,
                        'total' => (float) $detail->total,
                        'note' => $detail->note,

                        'product' => $itemType === 'used_product' ? $detail->usedProduct : $detail->product,

                        'attributes' => $itemType === 'product' ? $detail->attributes->map(function ($attr) {
                            return [
                                'attribute_name' => $attr->attributeValue->attribute->name ?? null,
                                'value_name' => $attr->attributeValue->value->attribute_value ?? null,
                                'price' => (float) $attr->price,
                            ];
                        }) : [],

                        'addons' => $itemType === 'product' ? $detail->addons->map(function ($addon) {
                            return [
                                'name' => $addon->addon->name ?? null,
                                'quantity' => $addon->quantity,
                                'unit_price' => (float) $addon->unit_price,
                                'total_price' => (float) $addon->total_price,
                            ];
                        }) : [],
                    ];
                });
            }),
            'rate' => $this->whenLoaded('rate'),

            // Timestamps
            'created_at' => $this->created_at?->format('Y-m-d h:i A'),
            'updated_at' => $this->updated_at?->format('Y-m-d h:i A'),
        ];
    }
}
