<?php

namespace App\Services;

class RiskScoringService
{
    public function calculate(
    $weather,
    $inflation,
    $sentiment,
    $geoRisk,
    $exchangeRate
)
{
    /*
    ======================
    WEATHER
    ======================
    */

    $weather = strtolower($weather);

    if (
        str_contains($weather,'storm') ||
        str_contains($weather,'thunder')
    ){

        $weatherRisk = 100;

    }
    elseif(
        str_contains($weather,'rain')
    ){

        $weatherRisk = 60;

    }
    elseif(
        str_contains($weather,'cloud')
    ){

        $weatherRisk = 30;

    }
    else{

        $weatherRisk = 10;

    }

    /*
    ======================
    INFLATION
    ======================
    */

    if($inflation >= 10){

        $inflationRisk = 100;

    }
    elseif($inflation >= 5){

        $inflationRisk = 60;

    }
    elseif($inflation >= 3){

        $inflationRisk = 30;

    }
    else{

        $inflationRisk = 10;

    }

    /*
    ======================
    POLITICAL / NEWS
    ======================
    */

    $sentiment = strtolower($sentiment);

    if($geoRisk == 'HIGH'){

        $politicalRisk = 100;

    }
    elseif($geoRisk == 'MEDIUM'){

        $politicalRisk = 70;

    }
    elseif($sentiment == 'negative'){

        $politicalRisk = 90;

    }
    elseif($sentiment == 'neutral'){

        $politicalRisk = 50;

    }
    else{

        $politicalRisk = 10;

    }

    /*
    ======================
    CURRENCY
    ======================
    */

    if($exchangeRate >= 17000){

        $currencyRisk = 90;

    }
    elseif($exchangeRate >= 16000){

        $currencyRisk = 60;

    }
    else{

        $currencyRisk = 10;

    }

    /*
    ======================
    WEIGHTED MODEL
    ======================
    */

    $score =

        ($weatherRisk * 0.30)

        +

        ($inflationRisk * 0.20)

        +

        ($politicalRisk * 0.40)

        +

        ($currencyRisk * 0.10);

    return round($score,2);
}

public function status($score)
{
    if ($score >= 80) {
        return 'HIGH';
    }

    if ($score >= 50) {
        return 'MEDIUM';
    }

    return 'LOW';
}

public function recommendation($status)
{
    switch($status){

        case 'HIGH':

            return [

                'Increase safety stock',

                'Use alternative suppliers',

                'Avoid affected ports',

                'Monitor every day'

            ];

        case 'MEDIUM':

            return [

                'Monitor logistics routes',

                'Prepare backup suppliers',

                'Review inventory',

                'Weekly monitoring'

            ];

        default:

            return [

                'Supply chain is stable',

                'Normal procurement',

                'Continue monitoring',

                'Monthly review'

            ];
    }
}
}