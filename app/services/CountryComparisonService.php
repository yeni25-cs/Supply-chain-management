<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class CountryComparisonService
{
    protected RiskScoringService $riskService;

    public function __construct(RiskScoringService $riskService)
    {
        $this->riskService = $riskService;
    }

    public function getCountryData($country)
    {
        $result = [];

        /*
        ==========================
        GDP
        ==========================
        */

        $result['gdp'] = null;

        try {

            $response = Http::timeout(10)->get(
                "https://api.worldbank.org/v2/country/{$country->code}/indicator/NY.GDP.MKTP.CD?format=json"
            );

            if($response->successful()){

                $json = $response->json();

                if(isset($json[1])){

                    foreach($json[1] as $item){

                        if($item['value'] != null){

                            $result['gdp'] = $item['value'];

                            break;

                        }

                    }

                }

            }

        } catch (\Exception $e) {
        }

        /*
        ==========================
        Inflation
        ==========================
        */

        $result['inflation'] = null;

        try{

            $response = Http::timeout(10)->get(
                "https://api.worldbank.org/v2/country/{$country->code}/indicator/FP.CPI.TOTL.ZG?format=json"
            );

            if($response->successful()){

                $json = $response->json();

                if(isset($json[1])){

                    foreach($json[1] as $item){

                        if($item['value'] != null){

                            $result['inflation'] = round($item['value'],2);

                            break;

                        }

                    }

                }

            }

        }catch(\Exception $e){

        }

        /*
        ==========================
        Weather
        ==========================
        */

        $result['weather'] = null;

        try{

            $response = Http::timeout(10)->get(
                'https://api.open-meteo.com/v1/forecast',
                [
                    'latitude'=>$country->latitude,
                    'longitude'=>$country->longitude,
                    'current'=>'temperature_2m,weather_code,wind_speed_10m,rain'
                ]
            );

            if($response->successful()){

                $current = $response->json()['current'];

                $weather = match($current['weather_code']){

                    0=>'Clear',
                    1,2=>'Partly Cloudy',
                    3=>'Cloudy',
                    45,48=>'Fog',
                    51,53,55=>'Drizzle',
                    61,63,65=>'Rain',
                    71,73,75=>'Snow',
                    80,81,82=>'Rain Shower',
                    95=>'Thunderstorm',
                    default=>'Unknown'

                };

                $result['weather'] = [

                    'desc'=>$weather,
                    'temp'=>$current['temperature_2m'],
                    'rain'=>$current['rain'] ?? 0,
                    'wind'=>$current['wind_speed_10m']

                ];

            }

        }catch(\Exception $e){

        }

        /*
        ==========================
        Data Database
        ==========================
        */

        $result['currency'] = $country->currency;

        $result['population'] = $country->population;

        $result['language'] = $country->language;

        $result['region'] = $country->region;

        return $result;
    }
}