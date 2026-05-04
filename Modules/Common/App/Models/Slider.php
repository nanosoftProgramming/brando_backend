<?php

namespace Modules\Common\App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Admin\App\Models\Admin;
use Modules\Restaurant\App\Models\Restaurant;
use Spatie\Activitylog\LogOptions;

class Slider extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'image',
        'restaurant_id',
        'is_active',
        'admin_id',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName('Slider')
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
                return asset('uploads/slider/'.$value);
            }
        }
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }
    // Relations

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }
}
