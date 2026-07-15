<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Country;

class UpdateCountryCoordinates extends Command
{
    protected $signature = 'countries:coordinates';

    protected $description = 'Update coordinates';

    public function handle()
    {
        $this->info('Updating coordinates...');

        $countries = json_decode(
            file_get_contents(
                database_path('data/countries_full.json')
            ),
            true
        );

        foreach($countries as $item){

            if(
                !isset($item['cca2']) ||
                !isset($item['latlng'])
            ){
                continue;
            }

            Country::where(
                'code',
                strtoupper($item['cca2'])
            )->update([

                'latitude'=>$item['latlng'][0],

                'longitude'=>$item['latlng'][1]

            ]);

        }

        $this->info('Done!');
    }
}