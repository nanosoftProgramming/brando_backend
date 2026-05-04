<?php

namespace Modules\Wishlist\App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Client\App\Models\Client;
use Modules\Product\App\Models\Product;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Wishlist extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = ['client_id', 'product_id'];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName('Wishlist')
            ->dontLogIfAttributesChangedOnly(['updated_at']);
    }

    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->format('Y-m-d h:i A');
    }
}
