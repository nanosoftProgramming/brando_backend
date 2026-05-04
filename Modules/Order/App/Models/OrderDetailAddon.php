<?php

namespace Modules\Order\App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Product\App\Models\Addon;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class OrderDetailAddon extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'order_detail_id',
        'addon_id',
        'quantity',
        'unit_price',
        'total_price',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName('OrderDetailAddon')
            ->dontLogIfAttributesChangedOnly(['updated_at']);
    }

    protected function serializeDate(\DateTimeInterface $date): string
    {
        return $date->format('Y-m-d h:i A');
    }

    public function orderDetail()
    {
        return $this->belongsTo(OrderDetail::class);
    }

    public function addon()
    {
        return $this->belongsTo(Addon::class);
    }
}
