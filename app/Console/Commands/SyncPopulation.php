<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Country;

class SyncPopulation extends Command
{
    protected $signature = 'countries:population';

    protected $description = 'Sync population data';

    public function handle()
    {
        $this->info('Updating population...');

        $populations = json_decode(
            file_get_contents(
                database_path('data/population.json')
            ),
            true
        );

        foreach($populations as $item){

            Country::where(
                'name',
                $item['country']
            )->update([

                'population'=>
                intval(
                    str_replace(
                        ',',
                        '',
                        $item['population']
                    )
                )

            ]);

        }

        $this->info(
            'Population updated successfully!'
        );
    }
}