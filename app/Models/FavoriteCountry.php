<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FavoriteCountry extends Model
{
    protected $fillable=[
        'country_code'
    ];

    public function country()
{
    return $this->belongsTo(

        Country::class,

        'country_code',

        'code'

    );
}
}