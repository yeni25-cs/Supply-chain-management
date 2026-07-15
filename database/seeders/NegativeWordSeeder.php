<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\NegativeWord;

class NegativeWordSeeder extends Seeder
{
    public function run(): void
    {
        $words = [

            'war',
            'conflict',
            'inflation',
            'delay',
            'disaster',
            'crisis',
            'strike',
            'shortage',
            'collapse',
            'decline',
            'loss',
            'recession',
            'storm',
            'flood',
            'earthquake',
            'sanction',
            'pandemic',
            'risk',
            'problem',
            'negative'

        ];

        foreach($words as $word){

            NegativeWord::firstOrCreate([
                'word'=>$word
            ]);

        }
    }
}