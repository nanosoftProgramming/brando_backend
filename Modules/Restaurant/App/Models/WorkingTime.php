<?php

namespace Modules\Restaurant\App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class WorkingTime extends Model
{
    use HasFactory, LogsActivity;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ['restaurant_id', 'day', 'opening_time', 'closing_time', 'is_closed'];

    /**
     * The days of the week.
     */
    public const DAYS = [
        'saturday',
        'sunday',
        'monday',
        'tuesday',
        'wednesday',
        'thursday',
        'friday',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName('WorkingTime')
            ->dontLogIfAttributesChangedOnly(['updated_at']);
    }

    // Serialize Date
    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->format('Y-m-d h:i A');
    }

    // Relations
    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }
}
