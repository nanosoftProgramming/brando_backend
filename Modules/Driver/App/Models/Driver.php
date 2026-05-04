<?php

namespace Modules\Driver\App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Modules\Branch\App\Models\Branch;
use Modules\Country\App\Models\City;
use Modules\Order\App\Models\Order;
use Modules\Restaurant\App\Models\Restaurant;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Driver extends Authenticatable implements JWTSubject
{
    use HasFactory, LogsActivity;

    protected $fillable = ['name', 'email', 'phone', 'password', 'image',
        'is_active', 'verify_code', 'license_id',
        'city_id', 'latitude', 'longitude', 'is_available', 'fcm_token',
        'allow_notification',

    ];

    protected $hidden = ['password'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName('Driver')
            ->dontLogIfAttributesChangedOnly(['updated_at']);
    }

    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->format('Y-m-d h:i A');
    }

    public function getImageAttribute($value)
    {
        if ($value != null && $value != '') {
            if (filter_var($value, FILTER_VALIDATE_URL)) {
                return $value;
            } else {
                return asset('uploads/driver/'.$value);
            }
        }
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }

    protected function scopeAvailable($query)
    {
        if (auth()->check()) {
            $admin = auth()->user();
            if ($admin->hasRole('Super Admin')) {
            } elseif ($admin->hasRole('Restaurant Manager')) {
                $query->where('restaurant_id', $admin->restaurant_id);
            } elseif ($admin->hasRole('Branch Manager')) {
                $query->where('branch_id', $admin->branch_id);
            }
        }
    }

    // Relations
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }

    // JWT

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
}
