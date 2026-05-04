<?php

namespace Modules\Branch\App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Admin\App\Models\Admin;
use Modules\Country\App\Models\City;
use Modules\Order\App\Models\Order;
use Modules\Product\App\Models\Product;
use Modules\Restaurant\App\Models\Restaurant;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Branch extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'name',
        'phone',
        'restaurant_id',
        'city_id',
        'latitude',
        'longitude',
        'block',
        'street',
        'building_number',
        'notes',
        'is_active',
    ];

    // Relationships
    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'branch_product')
            ->withPivot(['custom_price_egp', 'custom_price_sar', 'is_active'])
            ->withTimestamps();
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName('Branch')
            ->dontLogIfAttributesChangedOnly(['updated_at']);
    }

    public function manager()
    {
        return $this->hasOne(Admin::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->format('Y-m-d h:i A');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }

    public function scopeFilter($query, $filters)
    {
        if (! empty($filters['name'])) {
            $query->where('name', 'like', '%'.$filters['name'].'%');
        }

        if (! empty($filters['restaurant_id'])) {
            $query->where('restaurant_id', $filters['restaurant_id']);
        }

        if (! empty($filters['city_id'])) {
            $query->where('city_id', $filters['city_id']);
        }

        if (isset($filters['is_active'])) {
            $query->where('is_active', $filters['is_active']);
        }
        if (! empty($filters['category_id'])) {
            $query->whereHas('products', function ($q) use ($filters) {
                $q->where('category_id', $filters['category_id']);
            });
        }

    }

    protected function scopeAvailable($query)
    {
        if (auth('admin')->check()) {
            $admin = auth('admin')->user();
            if ($admin->hasRole('Super Admin')) {
            } elseif ($admin->hasRole('Restaurant Manager')) {
                $query->where('restaurant_id', $admin->restaurant_id);
            }
        }
    }
}
