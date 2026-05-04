<?php

namespace Modules\Cart\App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Product\App\Models\Addon;

class CartItemAddon extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ['cart_item_id', 'addon_id', 'quantity'];

    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->format('Y-m-d h:i A');
    }

    public function cartItem()
    {
        return $this->belongsTo(CartItem::class);
    }

    public function addon()
    {
        return $this->belongsTo(Addon::class);
    }

    /**
     * Calculate unit price in EGP based on current addon price
     */
    public function getUnitPriceEgpAttribute()
    {
        return $this->addon ? $this->addon->price_egp : 0;
    }

    /**
     * Calculate unit price in SAR based on current addon price
     */
    public function getUnitPriceSarAttribute()
    {
        return $this->addon ? $this->addon->price_sar : 0;
    }

    /**
     * Calculate total price in EGP
     */
    public function getTotalPriceEgpAttribute()
    {
        return $this->unit_price_egp * $this->quantity;
    }

    /**
     * Calculate total price in SAR
     */
    public function getTotalPriceSarAttribute()
    {
        return $this->unit_price_sar * $this->quantity;
    }
}
