<?php

namespace Modules\Cart\App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Client\App\Models\Client;
use Spatie\Activitylog\LogOptions;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = ['client_id'];

    // Add these to JSON response
    protected $appends = [
        'subtotal_egp',
        'subtotal_sar',
        'discounted_subtotal_egp',
        'discounted_subtotal_sar',
        'discount_egp',
        'discount_sar',
        'delivery_fee_egp',
        'delivery_fee_sar',
        'total_price_egp',
        'total_price_sar',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function items()
    {
        return $this->hasMany(CartItem::class);
    }

    /**
     * Calculate subtotal (items total before discount and delivery)
     */
    public function getSubtotalEgpAttribute()
    {
        return $this->items->sum(function ($item) {
            return $item->total_price_egp;
        });
    }

    /**
     * Calculate subtotal (items total before discount and delivery)
     */
    public function getSubtotalSarAttribute()
    {
        return $this->items->sum(function ($item) {
            return $item->total_price_sar;
        });
    }

    /**
     * Get discount amount in EGP (placeholder for future implementation)
     */
    public function getDiscountEgpAttribute()
    {
        // TODO: Implement discount calculation logic
        return 0;
    }

    /**
     * Get discount amount in SAR (placeholder for future implementation)
     */
    public function getDiscountSarAttribute()
    {
        // TODO: Implement discount calculation logic
        return 0;
    }

    /**
     * Get delivery fee in EGP (placeholder for future implementation)
     */
    public function getDeliveryFeeEgpAttribute()
    {
        // TODO: Implement delivery fee calculation based on distance, restaurant, etc.
        return 0;
    }

    /**
     * Get delivery fee in SAR (placeholder for future implementation)
     */
    public function getDeliveryFeeSarAttribute()
    {
        // TODO: Implement delivery fee calculation based on distance, restaurant, etc.
        return 0;
    }

    /**
     * Calculate final total (subtotal - discount + delivery)
     */
    public function getTotalPriceEgpAttribute()
    {
        return $this->subtotal_egp - $this->discount_egp + $this->delivery_fee_egp;
    }

    /**
     * Calculate final total (subtotal - discount + delivery)
     */
    public function getTotalPriceSarAttribute()
    {
        return $this->subtotal_sar - $this->discount_sar + $this->delivery_fee_sar;
    }

    public function getDiscountedSubtotalEgpAttribute()
    {
        return $this->items->sum('discounted_subtotal_egp');
    }

    public function getDiscountedSubtotalSarAttribute()
    {
        return $this->items->sum('discounted_subtotal_sar');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName('Cart')
            ->dontLogIfAttributesChangedOnly(['updated_at']);
    }

    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->format('Y-m-d h:i A');
    }
}
