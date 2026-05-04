<?php

namespace Modules\Product\App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class ProductImage extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = ['image', 'product_id'];

    protected $hidden = ['created_at', 'updated_at', 'product_id'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName('ProductImage')
            ->dontLogIfAttributesChangedOnly(['updated_at']);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Get Full Image Path
    public function getImageAttribute($value)
    {
        if ($value != null && $value != '') {
            if (filter_var($value, FILTER_VALIDATE_URL)) {
                return $value;
            } else {
                return asset('uploads/product/'.$value);
            }
        }
    }

    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->format('Y-m-d h:i A');
    }
}
