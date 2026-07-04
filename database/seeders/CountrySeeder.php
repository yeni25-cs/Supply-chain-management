<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Country;

class CountrySeeder extends Seeder
{
    public function run(): void
    {
        $countries = json_decode(
            file_get_contents(
                database_path('data/countries.json')
            ),
            true
        );

        foreach ($countries as $country) {

            if (!isset($country['cca2'])) {
                continue;
            }

            Country::updateOrCreate(

                [
                    'code' => $country['cca2']
                ],

                [
                    'name' => $country['name']['common'] ?? '',
                    'capital' => $country['capital'][0] ?? null,

                    'currency' => array_key_first(
                        $country['currencies'] ?? []
                    ),

                    'region' => $country['region'] ?? null,

                    'subregion' => $country['subregion'] ?? null,

                    'population' => intval(
                        $country['population'] ?? 0
                    ),

                    'flag' => isset($country['cca2'])
                        ? 'https://flagcdn.com/w320/' .
                          strtolower($country['cca2']) .
                          '.png'
                        : null
                ]
            );
        }
    }
}