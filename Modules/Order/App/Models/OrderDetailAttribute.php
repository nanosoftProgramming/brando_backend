<?php

namespace Modules\Order\App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Product\App\Models\ProductAttributeValue;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class OrderDetailAttribute extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'order_detail_id',
        'product_attribute_value_id',
        'price',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName('OrderDetailAttribute')
            ->dontLogIfAttributesChangedOnly(['updated_at']);
    }

    protected function serializeDate(\DateTimeInterface $date): string
    {
        return $date->format('Y-m-d h:i A');
    }

    public function orderDetail()
    {
        return $this->belongsTo(OrderDetail::class);
    }

    public function attributeValue()
    {
        return $this->belongsTo(ProductAttributeValue::class, 'product_attribute_value_id');
    }
}
