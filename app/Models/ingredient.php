<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Ingredient extends Model
{
    protected $fillable = [
        'name', 'unit', 'stock', 'cost_per_unit', 'minimum_stock', 'description',
    ];

    protected $casts = [
        'stock'         => 'decimal:3',
        'cost_per_unit' => 'decimal:2',
        'minimum_stock' => 'decimal:3',
    ];

    public function recipes(): BelongsToMany
    {
        return $this->belongsToMany(Recipe::class, 'recipe_ingredient')
            ->withPivot('quantity', 'unit')
            ->withTimestamps();
    }
}