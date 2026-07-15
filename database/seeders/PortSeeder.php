<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Port;

class PortSeeder extends Seeder
{
    public function run(): void
{
    $path = database_path('data/UpdatedPub150.csv');

    if (!file_exists($path)) {
        dd('CSV tidak ditemukan');
    }

    $file = fopen($path, 'r');

    $header = fgetcsv($file);

    while (($row = fgetcsv($file)) !== false) {

        $data = array_combine($header, $row);

        \App\Models\Port::updateOrCreate(
            [
                'port_name' => $data['Main Port Name']
            ],
            [
                'country_code' => $data['Country Code'],
                'country_name' => $data['Country Name'],
                'location'     => $data['World Water Body'],
                'latitude'     => $data['Latitude'],
                'longitude'    => $data['Longitude'],
            ]
        );
    }

    fclose($file);
}
}