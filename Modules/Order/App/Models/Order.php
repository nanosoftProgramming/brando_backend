<?php

namespace Modules\Order\App\Models;

use Modules\Order\App\Models\Rate;
use Spatie\Activitylog\LogOptions;
use Modules\Branch\App\Models\Branch;
use Modules\Client\App\Models\Client;
use Modules\Coupon\App\Models\Coupon;
use Modules\Driver\App\Models\Driver;
use Modules\Client\App\Models\Address;
use Illuminate\Database\Eloquent\Model;
use Modules\Order\App\Models\OrderDetail;
use Modules\Order\App\Models\OrderStatus;
use Modules\Restaurant\App\Models\Restaurant;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_no',
        'client_id',
        'address_id',
        'restaurant_id',
        'coupon_id',
        'order_status_id',
        'payment_method',
        'currency',
        'subtotal',
        'discount',
        'discount_type',
        'tax',
        'delivery_fee',
        'total',
        'quantity',
        'note',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName('Order')
            ->dontLogIfAttributesChangedOnly(['updated_at']);
    }

    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->format('Y-m-d h:i A');
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function details(): HasMany
    {
        return $this->hasMany(OrderDetail::class);
    }

    public function address(): BelongsTo
    {
        return $this->belongsTo(Address::class);
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function restaurant(): BelongsTo
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function coupon(): BelongsTo
    {
        return $this->belongsTo(Coupon::class);
    }

    public function rate(){
        return $this->hasMany(Rate::class);
    }

    public function scopeAvailable($query)
    {
        if (auth('admin')->check()) {
            $admin = auth('admin')->user();
            if ($admin->hasRole('Super Admin')) {
            } elseif ($admin->hasRole('Restaurant Manager')) {
                $query->where('restaurant_id', $admin->restaurant_id);
            }
        }

        return $query;
    }

    public function driver(): BelongsTo
    {
        return $this->belongsTo(Driver::class);
    }

    public function scopeFilter($query, $filters)
    {
        if (! empty($filters['status_id'])) {
            $query->where('order_status_id', $filters['order_status_id']);
        }

        if (! empty($filters['branch_name'])) {
            $query->whereHas('branch', function ($q) use ($filters) {
                $q->where('name', 'like', '%'.$filters['branch_name'].'%');
            });
        }

        if (! empty($filters['client_name'])) {
            $query->whereHas('client', function ($q) use ($filters) {
                $q->where('name', 'like', '%'.$filters['client_name'].'%');
            });
        }

        if (! empty($filters['created_at'])) {
            $query->whereDate('created_at', $filters['created_at']);
        }

        if (! empty($filters['status_name'])) {
            $query->whereHas('status', function ($q) use ($filters) {
                $q->where('name', 'like', '%'.$filters['status_name'].'%');
            });
        }

        if (! empty($filters['payment_type'])) {
            $query->where('payment_type', $filters['payment_type']);
        }

        if (! empty($filters['total'])) {
            $query->where('total', $filters['total']);
        }

        if (! empty($filters['client_id'])) {
            $query->where('client_id', $filters['client_id']);
        }

        if (! empty($filters['branch_id'])) {
            $query->where('branch_id', $filters['branch_id']);
        }

        if (! empty($filters['address_id'])) {
            $query->where('address_id', $filters['address_id']);
        }

        return $query;
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(OrderStatus::class, 'order_status_id');
    }
}
