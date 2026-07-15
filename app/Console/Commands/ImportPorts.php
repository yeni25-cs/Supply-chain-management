<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Port;
use App\Models\Country;

class ImportPorts extends Command
{
    protected $signature = 'ports:import';

    protected $description = 'Import World Port Index';

    public function handle()
    {
        $file = database_path('data/UpdatedPub150.csv');

        if (!file_exists($file)) {
            $this->error('CSV tidak ditemukan!');
            return;
        }

        $csv = fopen($file, 'r');

        $header = fgetcsv($csv);

        $countries = Country::all()->keyBy(function ($item) {
            return strtolower(trim($item->name));
        });

        $alias = [

            'United States' => 'United States of America',

            'Russian Federation' => 'Russia',

            'Korea Republic' => 'South Korea',

            'Korea DPR' => 'North Korea',

            'Viet Nam' => 'Vietnam',

            'Iran' => 'Iran',

            'Syrian Arab Republic' => 'Syria',

            'Brunei Darussalam' => 'Brunei',

            'Lao People\'s Democratic Republic' => 'Laos',

            'Micronesia (Federated States of)' => 'Micronesia',

            'Caroline Islands' => 'Micronesia',

            'Czech Republic' => 'Czechia',

            'United Kingdom' => 'United Kingdom',

            'Taiwan' => 'Taiwan',

            'Hong Kong' => 'Hong Kong',

            'Macao' => 'Macau',

            'Timor-Leste' => 'Timor-Leste',

        ];

        while (($row = fgetcsv($csv)) !== false) {

            $data = array_combine($header, $row);

            if ($data === false) {
                continue;
            }

            if (
                empty($data['Main Port Name']) ||
                empty($data['Country Code']) ||
                empty($data['Latitude']) ||
                empty($data['Longitude'])
            ) {
                continue;
            }

            $countryName = trim($data['Country Code']);

            if (isset($alias[$countryName])) {
                $countryName = $alias[$countryName];
            }

            $country = $countries->get(
                strtolower($countryName)
            );

            Port::updateOrCreate(

                [

                    'name' => trim($data['Main Port Name'])

                ],

                [

                    'country_code' => $country ? $country->code : '--',

                    'country_name' => $countryName,

                    'latitude' => $data['Latitude'],

                    'longitude' => $data['Longitude'],

                    'location' => $data['World Water Body'] ?? null,

                    'city' => null,

                    'type' => 'Seaport',

                    'status' => 'Active'

                ]

            );

        }

        fclose($csv);

        $this->info('Import selesai!');

    }
}