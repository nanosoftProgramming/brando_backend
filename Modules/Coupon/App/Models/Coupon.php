<?php

namespace Modules\Coupon\App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;

class Coupon extends Model
{
    use HasFactory;

    const FIXED = 1;

    const PERCENT = 2;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ['code', 'is_active', 'num_of_uses', 'counter', 'type', 'value', 'limit', 'date_from', 'date_to', 'time_from', 'time_to', 'client_uses', 'discount_on'];

    // Log Activity
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName('Coupon')
            ->dontLogIfAttributesChangedOnly(['updated_at']);
    }

    // Serialize Dates
    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->format('Y-m-d h:i A');
    }

    public function discount($total)
    {
        $this->counter++;
        $this->save();
        if ($this->type == self::FIXED) {
            return min($this->value, $total);
        } else {
            $discount = (int) ($total * $this->value) / 100;
            if ($this->limit > 0 && $discount > $this->limit) {
                return $this->limit;
            }

            return $discount;
        }
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }
}
