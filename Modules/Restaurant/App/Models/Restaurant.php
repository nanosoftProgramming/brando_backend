<?php

namespace Modules\Restaurant\App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Admin\App\Models\Admin;
use Modules\Branch\App\Models\Branch;
use Modules\Product\App\Models\Product;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Restaurant extends Model
{
    use HasFactory, LogsActivity;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ['name', 'image', 'is_active', 'min_time', 'max_time'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName('Restaurant')
            ->dontLogIfAttributesChangedOnly(['updated_at']);
    }

    // Serialize Date
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
                return asset('uploads/restaurant/'.$value);
            }
        }
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }

    // Relations
    public function manager()
    {
        return $this->hasOne(Admin::class);
    }

    public function branches()
    {
        return $this->hasMany(Branch::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function workingTimes()
    {
        return $this->hasMany(WorkingTime::class);
    }

    public function scopeFilter($query, $filters)
    {
        if (! empty($filters['name'])) {
            $query->where('name', 'like', '%'.$filters['name'].'%');
        }

        if (isset($filters['is_active'])) {
            $query->where('is_active', $filters['is_active']);
        }
    }
}
