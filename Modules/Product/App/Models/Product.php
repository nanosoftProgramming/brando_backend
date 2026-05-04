<?php

namespace Modules\Product\App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Category\App\Models\Category;
use Modules\Client\App\Models\Client;
use Modules\Order\App\Models\OrderDetail;
use Modules\Order\App\Models\RateProduct;
use Modules\Restaurant\App\Models\Restaurant;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Product extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'code',
        'category_id',
        'name',
        'description',
        'price_egp',
        'price_sar',
        'discounted_price_egp',
        'discounted_price_sar',
        'is_active',
        'restaurant_id',
        'image',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName('Product')
            ->dontLogIfAttributesChangedOnly(['updated_at']);
    }

    protected function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }

    // Serialize Date
    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->format('Y-m-d h:i A');
    }

    // Relations
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function addons()
    {
        return $this->belongsToMany(Addon::class, 'addon_product', 'product_id', 'addon_id');
    }

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function attributeValues()
    {
        return $this->hasMany(ProductAttributeValue::class, 'product_id');
    }

    public function attributes()
    {
        return $this->belongsToMany(Attribute::class, 'product_attribute_values', 'product_id', 'attribute_id')
            ->withPivot(['attribute_value_id', 'price_egp', 'price_sar', 'id'])
            ->withTimestamps();
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

    public function rate(): HasMany
    {
        return $this->hasMany(RateProduct::class, 'product_id', 'id');
    }

    public function orderDetails(): HasMany
    {
        return $this->hasMany(OrderDetail::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function sizeImages()
    {
        return $this->hasMany(ProductSizeImage::class);
    }

    public function wishlistedByUsers()
    {
        return $this->belongsToMany(Client::class, 'wishlists', 'product_id', 'client_id')->withTimestamps();
    }

    public function scopeFilter($query, $filters)
    {
        if (! empty($filters['name'])) {
            $query->where('name', 'like', '%'.$filters['name'].'%');
        }
        if (! empty($filters['restaurant_id'])) {
            $query->where('restaurant_id', $filters['restaurant_id']);
        }
        if (! empty($filters['category_id'])) {
            $query->where('category_id', $filters['category_id']);
        }
        if (! empty($filters['attribute_id'])) {
            $query->whereHas('attributeValues', function ($q) use ($filters) {
                $q->where('attribute_id', $filters['attribute_id']);
            });
        }
        if (! empty($filters['attribute_value_id'])) {
            $query->whereHas('attributeValues', function ($q) use ($filters) {
                $q->where('attribute_value_id', $filters['attribute_value_id']);
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
