<?php

namespace Modules\Product\App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Restaurant\App\Models\Restaurant;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Addon extends Model
{
    use HasFactory, LogsActivity;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ['name', 'price_egp', 'price_sar', 'is_active', 'restaurant_id'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName('Addon')
            ->dontLogIfAttributesChangedOnly(['updated_at']);
    }

    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->format('Y-m-d h:i A');
    }

    // Get Full Image Path
    public function getImageAttribute($value)
    {
        if ($value != null && $value != '') {
            if (filter_var($value, FILTER_VALIDATE_URL)) {
                return $value;
            } else {
                return asset('uploads/addon/'.$value);
            }
        }
    }

    protected function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }
    // Relations

    public function products()
    {
        return $this->belongsToMany(Product::class, 'addon_product', 'addon_id', 'product_id');
    }

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }
}
