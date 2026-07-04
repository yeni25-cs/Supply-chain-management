<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Support\Facades\Http;

class RiskController extends Controller
{
    public function index()
    {
        $suppliers = Supplier::with(
            'country'
        )->get();

        $riskData = [];

        foreach($suppliers as $supplier){

            if(!$supplier->country){
                continue;
            }

            $risk = 0;
            $weather = '-';
            $inflation = 0;

            // WEATHER
            try{

                $capital =
                $supplier
                ->country
                ->capital;

                if($capital){

                    $response =
                    Http::timeout(10)
                    ->get(
                        'https://wttr.in/' .
                        urlencode($capital) .
                        '?format=j1'
                    );

                    if(
                        $response->successful()
                    ){

                        $data =
                        $response
                        ->json();

                        $weather =
                        $data[
                        'current_condition'
                        ][0]
                        ['weatherDesc']
                        [0]
                        ['value'];

                        $weatherLower =
                        strtolower(
                            $weather
                        );

                        if(
                            str_contains(
                                $weatherLower,
                                'rain'
                            )
                        ){

                            $risk += 20;

                        }

                        if(
                            str_contains(
                                $weatherLower,
                                'storm'
                            )
                        ){

                            $risk += 40;

                        }

                    }

                }

            }catch(\Exception $e){}

            // INFLATION
            try{

                $countryCode =
                $supplier
                ->country
                ->code;

                $response =
                Http::timeout(10)
                ->get(
'https://api.worldbank.org/v2/country/' .
$countryCode .
'/indicator/FP.CPI.TOTL.ZG?format=json'
                );

                if(
                    $response->successful()
                ){

                    $data =
                    $response
                    ->json();

                    $inflation =
                    $data[1][0]['value']
                    ??0;

                    if(
                        $inflation > 5
                    ){

                        $risk += 20;

                    }

                }

            }catch(\Exception $e){}

            $riskData[] = [

                'supplier' =>
                $supplier->name,

                'country' =>
                $supplier
                ->country
                ->name,

                'weather' =>
                $weather,

                'inflation' =>
                round(
                    $inflation,
                    2
                ),

                'risk' =>
                min(
                    $risk,
                    100
                )

            ];

        }

        return view(
            'risk.index',
            compact(
                'riskData'
            )
        );
    }
}