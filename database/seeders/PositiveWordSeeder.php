<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PositiveWord;

class PositiveWordSeeder extends Seeder
{
    public function run(): void
    {
        $words = [

            'growth',
            'increase',
            'profit',
            'stable',
            'improve',
            'export',
            'success',
            'recovery',
            'development',
            'investment',
            'opportunity',
            'efficient',
            'strong',
            'expand',
            'innovation',
            'production',
            'supply',
            'partnership',
            'improvement',
            'positive'

        ];

        foreach($words as $word){

            PositiveWord::firstOrCreate([
                'word'=>$word
            ]);

        }
    }
}