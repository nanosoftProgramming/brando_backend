<?php

namespace Modules\Product\App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class ProductAttributeValue extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = ['attribute_value_id', 'product_id', 'attribute_id', 'price_egp', 'price_sar'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName('ProductAttributeValue')
            ->dontLogIfAttributesChangedOnly(['updated_at']);
    }

    public function attribute()
    {
        return $this->belongsTo(Attribute::class, 'attribute_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function value()
    {
        return $this->belongsTo(AttributeValue::class, 'attribute_value_id');
    }

    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->format('Y-m-d h:i A');
    }
}
