<?php

namespace Modules\Category\App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Product\App\Models\Product;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Category extends Model
{
    use HasFactory, LogsActivity;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ['name', 'image', 'description', 'image', 'category_id', 'is_active'];

    // Log Activity
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName('Category')
            ->dontLogIfAttributesChangedOnly(['updated_at']);
    }

    // Serialize Dates
    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->format('Y-m-d h:i A');
    }

    // Get FullImage Path
    public function getImageAttribute($value)
    {
        if ($value != null && $value != '') {
            if (filter_var($value, FILTER_VALIDATE_URL)) {
                return $value;
            } else {
                return asset('uploads/category/'.$value);
            }
        }
    }

    // Helper Functions
    public function scopeParent($query)
    {
        return $query->whereNull('category_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }

    // Relations
    public function parent()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'category_id');
    }

    public function childrenRecursive()
    {
        return $this->children()->with('childrenRecursive')->active();
    }

    public function childrenRecursiveAll()
    {
        return $this->children()->with('childrenRecursiveAll');
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
