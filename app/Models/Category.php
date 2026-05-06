<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Category extends Model
{
    protected $fillable = [
        'name', 'slug', 'description', 'image', 'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    protected static function boot(): void
    {
        parent::boot();
        static::creating(fn ($model) => $model->slug = Str::slug($model->name));
        static::updating(fn ($model) => $model->slug = Str::slug($model->name));
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}