<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Port;

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
        'latitude',
        'longitude',
    ];

    public function suppliers(): HasMany
    {
        return $this->hasMany(Supplier::class);
    }

    public function ports()
{
    return $this->hasMany(
        Port::class,
        'country_code',
        'code'
    );
}
}