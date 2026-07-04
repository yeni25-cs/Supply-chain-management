<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
    'name',
    'supplier_id',
    'stock',
    'risk_score',
    'risk_level',
];

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public static function calculateRisk($stock, $country)
{
    $score = 0;

    // Stock sedikit
    if ($stock < 20) {
        $score += 40;
    }

    // Supplier luar Indonesia
    if ($country != 'Indonesia') {
        $score += 20;
    }

    // Tentukan level
    if ($score >= 60) {
        $level = 'High';
    } elseif ($score >= 30) {
        $level = 'Medium';
    } else {
        $level = 'Low';
    }

    return [
        'score' => $score,
        'level' => $level
    ];
}
}