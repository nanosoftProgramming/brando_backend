<?php

namespace Modules\Order\App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Client\App\Models\Client;

class Rate extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ['order_id', 'client_id', 'rate', 'restaurant_rate', 'comment', 'restaurant_comment'];

    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->format('Y-m-d h:i A');
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function products(): HasMany
    {
        return $this->hasMany(RateProduct::class);
    }
}
