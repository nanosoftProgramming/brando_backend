<?php

namespace Modules\Order\App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Product\App\Models\Product;
use Spatie\Activitylog\LogOptions;

class OrderDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'used_product_id',
        'item_type',
        'quantity',
        'price',
        'total',
        'note',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function usedProduct()
    {
        return $this->belongsTo(\Modules\Product\App\Models\UsedProduct::class, 'used_product_id');
    }

    public function getItem()
    {
        return $this->item_type === 'used_product' ? $this->usedProduct : $this->product;
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName('OrderDetail')
            ->dontLogIfAttributesChangedOnly(['updated_at']);
    }

    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->format('Y-m-d h:i A');
    }

    public function addons()
    {
        return $this->hasMany(OrderDetailAddon::class);
    }

    public function attributes()
    {
        return $this->hasMany(OrderDetailAttribute::class);
    }
}
