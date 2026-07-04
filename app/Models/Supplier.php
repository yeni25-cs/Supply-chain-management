<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Supplier extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'country_id',
        'contact',
    ];

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }
}