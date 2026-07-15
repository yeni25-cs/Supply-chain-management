<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\PositiveWordSeeder;
use Database\Seeders\NegativeWordSeeder;


class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            CountrySeeder::class,
            PositiveWordSeeder::class,
            NegativeWordSeeder::class,
            PortSeeder::class,
        ]);
    }
}