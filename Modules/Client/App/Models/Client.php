<?php

namespace Modules\Client\App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Modules\Cart\App\Models\Cart;
use Modules\Order\App\Models\Order;
use Modules\Product\App\Models\Product;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Client extends Authenticatable implements JWTSubject
{
    use HasFactory, LogsActivity;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ['name', 'email', 'phone', 'password', 'image', 'date_of_birth', 'fcm_token', 'verify_code', 'is_active', 'allow_notification'];

    protected $hidden = ['password'];

    // Log Activity
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName('Client')
            ->dontLogIfAttributesChangedOnly(['updated_at']);
    }

    // Serialize Dates
    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->format('Y-m-d h:i A');
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }

    public function cart()
    {
        return $this->hasOne(Cart::class);
    }

    public function wishlistProducts()
    {
        return $this->belongsToMany(Product::class, 'wishlists')->withTimestamps();
    }

    public function usedProducts()
    {
        return $this->hasMany(\Modules\Product\App\Models\UsedProduct::class, 'client_id');
    }

    public function soldUsedProducts()
    {
        return $this->hasMany(\Modules\Product\App\Models\UsedProduct::class, 'client_id')->where('is_sold', true);
    }

    // Get FullImage Path
    public function getImageAttribute($value)
    {
        if ($value != null && $value != '') {
            if (filter_var($value, FILTER_VALIDATE_URL)) {
                return $value;
            } else {
                return asset('uploads/client/' . $value);
            }
        }
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

    public function addresses()
    {
        return $this->hasMany(\Modules\Client\App\Models\Address::class);
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

    public function scopeFilter($query, array $filters)
    {
        if (!empty($filters['name'])) {
            $query->where('name', 'like', '%' . $filters['name'] . '%');
        }

        return $query;
    }
}
