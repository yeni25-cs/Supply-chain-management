<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class PortWeatherService
{

    public function getWeather($lat, $lon)
{
    $apiKey = env('OPENWEATHER_API_KEY');

    $response = Http::timeout(20)->get(
        'https://api.openweathermap.org/data/2.5/weather',
        [
            'lat'   => $lat,
            'lon'   => $lon,
            'appid' => trim($apiKey),
            'units' => 'metric'
        ]
    );

if (!$response->successful()) {
    return null;
}

return $response->json();
}

    public function calculateRisk($weather)
{

    $score = 0;

    $rain = $weather['rain']['1h'] ?? 0;

    $wind = $weather['wind']['speed'] ?? 0;

    $temp = $weather['main']['temp'] ?? 0;

    $condition = $weather['weather'][0]['main'] ?? '';

    if($rain >= 20){

        $score += 40;

    }

    if($wind >= 12){

        $score += 30;

    }

    if($temp >= 35){

        $score += 10;

    }

    if($condition == 'Thunderstorm'){

        $score += 40;

    }

    return $score >= 40 ? 'HIGH' : 'LOW';

}
}
