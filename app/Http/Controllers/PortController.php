<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\Port;
use Illuminate\Http\Request;
use App\Services\PortWeatherService;
use Illuminate\Support\Facades\Http;

class PortController extends Controller
{
    public function index(Request $request)
    {
        $countries = Country::orderBy('name')->get();

        $country = Country::where(
            'code',
            $request->country ?? 'ID'
        )->first();

        $ports = Port::where(
            'country_code',
            $country->code
        )->get();

        return view('ports.index', compact(
            'countries',
            'country',
            'ports'
        ));
    }
   public function updateWeather(
    Request $request,
    PortWeatherService $weatherService
){

    $ports = Port::where(
        'country_code',
        $request->country
    )->get();

    foreach($ports as $port){

        /*
        =============================
        UPDATE CITY
        =============================
        */

        $geo = Http::withHeaders([
    'User-Agent' => 'SupplyChainAI'
])->get(
    'https://nominatim.openstreetmap.org/reverse',
    [
        'lat'=>$port->latitude,
        'lon'=>$port->longitude,
        'format'=>'jsonv2'
    ]
);

if($geo->successful()){

    $address = $geo->json()['address'] ?? [];

    $port->city =
        $address['city']
        ?? $address['city_district']
        ?? $address['town']
        ?? $address['municipality']
        ?? $address['county']
        ?? $address['state']
        ?? $address['village']
        ?? 'Unknown';

}

        /*
        =============================
        UPDATE WEATHER
        =============================
        */

        $weather = $weatherService->getWeather(
            $port->latitude,
            $port->longitude
        );

        if($weather){

            $risk = $weatherService->calculateRisk($weather);

            $port->risk_status = $risk;

        }

        $port->save();

    }

    return redirect()
        ->route(
            'ports.index',
            [
                'country'=>$request->country
            ]
        )
        ->with(
            'success',
            'Weather berhasil diperbarui.'
        );

}
}