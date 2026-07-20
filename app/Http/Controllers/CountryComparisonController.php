<?php

namespace App\Http\Controllers;

use App\Models\Country;
use Illuminate\Http\Request;
use App\Models\Supplier;
use App\Services\RiskScoringService;
use App\Services\SentimentService;
use Illuminate\Support\Facades\Http;
use App\Models\Port;

class CountryComparisonController extends Controller
{
    public function index(Request $request)
    {
        $countries = Country::orderBy('name')->get();

        $countryA = Country::where(
            'code',
            $request->countryA
        )->first();

        $countryB = Country::where(
            'code',
            $request->countryB
        )->first();

$dataA = null;
$dataB = null;

if($countryA){

    $dataA = $this->getCountryData($countryA);

}

if($countryB){

    $dataB = $this->getCountryData($countryB);

}
$scoreA = null;
$scoreB = null;

if($dataA){
    $scoreA = $this->calculateScore($dataA);
}

if($dataB){
    $scoreB = $this->calculateScore($dataB);
}

        return view(
            'comparison.index',
            compact(
                'countries',
                'countryA',
                'countryB',
                'dataA',
                'dataB',
                'scoreA',
                'scoreB',
            )
        );
    }

    private function getCountryData($country)
{

    $data = [

        'capital'     => $country->capital,
        'population'  => $country->population,
        'currency'    => $country->currency,
        'language'    => $country->language,
        'region'      => $country->region,

        'gdp'         => null,
        'inflation'   => null,

    ];

    /*
    ==========================
    GDP
    ==========================
    */

    try{

        $response = Http::timeout(10)->get(
            "https://api.worldbank.org/v2/country/".$country->code."/indicator/NY.GDP.MKTP.CD?format=json"
        );

        if($response->successful()){

            $json = $response->json();

            if(isset($json[1])){

                foreach($json[1] as $item){

                    if($item['value'] != null){

                        $data['gdp'] = $item['value'];

                        break;

                    }

                }

            }

        }

    }catch(\Exception $e){

    }

    /*
    ==========================
    Inflation
    ==========================
    */

    try{

        $response = Http::timeout(10)->get(
            "https://api.worldbank.org/v2/country/".$country->code."/indicator/FP.CPI.TOTL.ZG?format=json"
        );

        if($response->successful()){

            $json = $response->json();

            if(isset($json[1])){

                foreach($json[1] as $item){

                    if($item['value'] != null){

                        $data['inflation'] = round($item['value'],2);

                        break;

                    }

                }

            }

        }

    }catch(\Exception $e){

    }

    /*
==========================
WEATHER
==========================
*/

$data['weather'] = '-';

try{

    if($country->latitude && $country->longitude){

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

            $data['weather']=$weather;

        }

    }

}catch(\Exception $e){

}

/*
==========================
PORT RISK
==========================
*/

$risk = Port::where(
    'country_code',
    $country->code
)
->where('risk_status','HIGH')
->exists();

$data['risk']=$risk ? 'HIGH' : 'LOW';
    return $data;

}

private function calculateScore($data)
{
    $score = 0;

    // GDP
    if(($data['gdp'] ?? 0) > 1000000000000){
        $score += 25;
    }elseif(($data['gdp'] ?? 0) > 300000000000){
        $score += 18;
    }else{
        $score += 10;
    }

    // Inflation
    if(($data['inflation'] ?? 99) < 3){
        $score += 20;
    }elseif(($data['inflation'] ?? 99) < 6){
        $score += 10;
    }

    // Weather
    if(in_array($data['weather'],[
        'Clear',
        'Partly Cloudy'
    ])){
        $score += 15;
    }elseif($data['weather']=='Cloudy'){
        $score += 10;
    }

    // Port Risk
    if($data['risk']=='LOW'){
        $score += 25;
    }

    // Population
    if(($data['population'] ?? 0) > 100000000){
        $score += 15;
    }elseif(($data['population'] ?? 0) > 30000000){
        $score += 10;
    }else{
        $score += 5;
    }

    return $score;
}
}