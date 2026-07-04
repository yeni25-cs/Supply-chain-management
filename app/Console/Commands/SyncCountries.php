<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Country;
use Illuminate\Support\Facades\Http;

class SyncCountries extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'countries:sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync all countries from REST Countries API';

    /**
     * Execute the console command.
     */
public function handle()
{
    $this->info('Fetching population data...');

    $response = \Illuminate\Support\Facades\Http::get(
        'https://restcountries.com/v3.1/all?fields=cca2,population'
    );

    if(!$response->successful()){
        $this->error('Failed');
        return;
    }

    foreach($response->json() as $country){

        if(!isset($country['cca2'])){
            continue;
        }

        \App\Models\Country::where(
            'code',
            $country['cca2']
        )->update([

            'population'=>$country['population'] ?? 0

        ]);

    }

    $this->info('Population updated successfully!');
}
}
