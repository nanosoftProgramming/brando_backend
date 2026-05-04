<?php

namespace Modules\Cart\App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Product\App\Models\Product;
use Spatie\Activitylog\LogOptions;

class CartItem extends Model
{
    use HasFactory;

    protected $fillable = ['cart_id', 'quantity', 'product_id', 'used_product_id', 'item_type', 'notes'];

    protected $appends = [
        'unit_price_egp',
        'unit_price_sar',
        'discounted_price_egp',
        'discounted_price_sar',
        'total_price_egp',
        'total_price_sar',
        'subtotal_egp',
        'subtotal_sar',
        'discounted_subtotal_egp',
        'discounted_subtotal_sar',
        'addons_total_egp',
        'addons_total_sar',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName('CartItem')
            ->dontLogIfAttributesChangedOnly(['updated_at']);
    }

    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->format('Y-m-d h:i A');
    }

    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function usedProduct()
    {
        return $this->belongsTo(\Modules\Product\App\Models\UsedProduct::class, 'used_product_id');
    }

    public function getItem()
    {
        return $this->item_type === 'used_product' ? $this->usedProduct : $this->product;
    }

    public function addons()
    {
        return $this->hasMany(CartItemAddon::class);
    }

    public function attributes()
    {
        return $this->hasMany(CartItemAttribute::class);
    }

    public function getUnitPriceEgpAttribute()
    {
        $item = $this->getItem();
        if (!$item) {
            return 0;
        }

        return $item->price_egp;
    }

    public function getUnitPriceSarAttribute()
    {
        $item = $this->getItem();
        if (!$item) {
            return 0;
        }

        return $item->price_sar;
    }

    /**
     * Calculate subtotal in EGP (product price × quantity + attributes, no addons)
     * ALWAYS uses regular price
     */
    public function getSubtotalEgpAttribute()
    {
        return ($this->unit_price_egp + $this->calculateAttributePrice('egp')) * $this->quantity;
    }

    /**
     * Calculate subtotal in SAR (product price × quantity + attributes, no addons)
     * ALWAYS uses regular price
     */
    public function getSubtotalSarAttribute()
    {
        return ($this->unit_price_sar + $this->calculateAttributePrice('sar')) * $this->quantity;
    }

    public function getAddonsTotalEgpAttribute()
    {
        return $this->addons->sum(function ($addon) {
            return $addon->total_price_egp ?? 0;
        });
    }

    public function getAddonsTotalSarAttribute()
    {
        return $this->addons->sum(function ($addon) {
            return $addon->total_price_sar ?? 0;
        });
    }

    /**
     * Calculate total price in EGP (uses best available price + addons)
     * SMART - uses discounted if available, otherwise regular
     */
    public function getTotalPriceEgpAttribute()
    {
        $effectiveSubtotal = $this->discounted_price_egp > 0
            ? $this->discounted_subtotal_egp
            : $this->subtotal_egp;

        return $effectiveSubtotal + $this->addons_total_egp;
    }

    /**
     * Calculate total price in SAR (uses best available price + addons)
     * SMART - uses discounted if available, otherwise regular
     */
    public function getTotalPriceSarAttribute()
    {
        $effectiveSubtotal = $this->discounted_price_sar > 0
            ? $this->discounted_subtotal_sar
            : $this->subtotal_sar;

        return $effectiveSubtotal + $this->addons_total_sar;
    }

    public function getDiscountedPriceEgpAttribute()
    {
        $item = $this->getItem();
        if (!$item) {
            return 0;
        }

        return $item->discounted_price_egp ?? 0;
    }

    public function getDiscountedPriceSarAttribute()
    {
        $item = $this->getItem();
        if (!$item) {
            return 0;
        }

        return $item->discounted_price_sar ?? 0;
    }

    public function getDiscountedSubtotalEgpAttribute()
    {
        $effectivePrice = $this->discounted_price_egp > 0 ? $this->discounted_price_egp : $this->unit_price_egp;

        return ($effectivePrice + $this->calculateAttributePrice('egp')) * $this->quantity;
    }

    public function getDiscountedSubtotalSarAttribute()
    {
        $effectivePrice = $this->discounted_price_sar > 0 ? $this->discounted_price_sar : $this->unit_price_sar;

        return ($effectivePrice + $this->calculateAttributePrice('sar')) * $this->quantity;
    }

    /**
     * Calculate attribute price for given currency
     */
    private function calculateAttributePrice($currency)
    {
        return $this->attributes()->with('attributeValue')->get()->sum(function ($attribute) use ($currency) {
            return $attribute->attributeValue->{"price_{$currency}"} ?? 0;
        });
    }
}
