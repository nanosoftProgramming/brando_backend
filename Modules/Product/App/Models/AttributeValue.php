<?php

namespace Modules\Product\App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class AttributeValue extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = ['attribute_value', 'attribute_id'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName('AttributeValue')
            ->dontLogIfAttributesChangedOnly(['updated_at']);
    }

    public function attribute()
    {
        return $this->belongsTo(Attribute::class, 'attribute_id');
    }

    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->format('Y-m-d h:i A');
    }
}
