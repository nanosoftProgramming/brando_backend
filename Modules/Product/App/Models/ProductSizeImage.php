<?php

namespace Modules\Product\App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class ProductSizeImage extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = ['product_id', 'image'];

    protected $hidden = ['created_at', 'updated_at', 'product_id'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName('ProductSizeImage')
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
            }

            return asset('uploads/product_size_images/'.$value);
        }
    }

    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->format('Y-m-d h:i A');
    }
}


