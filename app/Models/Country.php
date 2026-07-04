<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Country extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'capital',
        'currency',
        'region',
        'subregion',
        'population',
        'flag',
    ];

    public function suppliers(): HasMany
    {
        return $this->hasMany(Supplier::class);
    }
}