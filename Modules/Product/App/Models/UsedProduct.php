<?php

namespace Modules\Product\App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Cart\App\Models\CartItem;
use Modules\Category\App\Models\Category;
use Modules\Client\App\Models\Client;
use Modules\Order\App\Models\OrderDetail;

class UsedProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'category_id',
        'name',
        'description',
        'code',
        'price_egp',
        'price_sar',
        'discounted_price_egp',
        'discounted_price_sar',
        'image',
        'is_sold',
        'is_active',
    ];

    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->format('Y-m-d h:i A');
    }

    public function scopeAvailable($query)
    {
        return $query->where('is_sold', false)->where('is_active', true);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class);
    }

    public function getImageAttribute($value)
    {
        if ($value != null && $value != '') {
            if (filter_var($value, FILTER_VALIDATE_URL)) {
                return $value;
            }

            return asset('uploads/used_product/' . $value);
        }
    }

    public function markAsSold()
    {
        $this->update(['is_sold' => true]);
    }
}
