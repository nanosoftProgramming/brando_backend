<?php

namespace Modules\Cart\App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Product\App\Models\ProductAttributeValue;

class CartItemAttribute extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ['cart_item_id', 'product_attribute_value_id'];

    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->format('Y-m-d h:i A');
    }

    public function cartItem()
    {
        return $this->belongsTo(CartItem::class);
    }

    public function attributeValue()
    {
        return $this->belongsTo(ProductAttributeValue::class, 'product_attribute_value_id');
    }
}
