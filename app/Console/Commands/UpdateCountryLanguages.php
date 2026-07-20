<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Country;

class UpdateCountryLanguages extends Command
{
    protected $signature = 'countries:update-languages';

    protected $description = 'Update country language from countries.json';

    public function handle()
    {
        $path = database_path('data/countries.json');

        if (!file_exists($path)) {
            $this->error('countries.json tidak ditemukan!');
            return;
        }

        $countries = json_decode(file_get_contents($path), true);

        $updated = 0;

        foreach ($countries as $item) {

            if (!isset($item['cca2'])) {
                continue;
            }

            $code = strtoupper($item['cca2']);

            $language = '-';

            if (isset($item['languages'])) {
                $language = implode(', ', array_values($item['languages']));
            }

            $country = Country::where('code', $code)->first();

            if ($country) {

                $country->language = $language;
                $country->save();

                $updated++;
            }
        }

        $this->info("Selesai. {$updated} negara berhasil diupdate.");
    }
}