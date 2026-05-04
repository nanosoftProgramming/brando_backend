<?php

namespace Modules\Product\App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Attribute extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = ['name', 'required', 'multi_select', 'override_price'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName('Attribute')
            ->dontLogIfAttributesChangedOnly(['updated_at']);
    }

    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->format('Y-m-d h:i A');
    }

    public function values()
    {
        return $this->hasMany(AttributeValue::class, 'attribute_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
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
