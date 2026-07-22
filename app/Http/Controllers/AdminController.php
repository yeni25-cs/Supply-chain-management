<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\Supplier;
use App\Models\Port;
use App\Models\FavoriteCountry;
use App\Models\User;
use App\Services\SentimentService;
use App\Services\RiskScoringService;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        return view('admin.index', [

            'totalCountries' => Country::count(),

            'totalSuppliers' => Supplier::count(),

            'totalPorts' => Port::count(),

            'totalMonitoring' => FavoriteCountry::count(),

            // sementara dummy
            'totalUsers' => 1,

            'totalArticles' => 0,

        ]);
    }

    public function users()
{
    $users = User::latest()->get();

    return view(
        'admin.users',
        compact('users')
    );
}

public function articles(
    Request $request,
    SentimentService $sentimentService,
    RiskScoringService $riskService
)
{
    $countries = Country::orderBy('name')->get();

    $country = $request->country ?? 'Indonesia';

    $response = Http::get(
        'https://gnews.io/api/v4/search',
        [
            'q'      => $country . ' logistics OR shipping OR trade OR economy',
            'lang'   => 'en',
            'max'    => 10,
            'apikey' => env('GNEWS_API_KEY')
        ]
    );

    $articles = [];

    $positive = 0;
    $negative = 0;
    $neutral = 0;

    if ($response->successful()) {

        $articles = $response->json()['articles'];

        foreach ($articles as &$article) {

            $text = ($article['title'] ?? '') . ' ' . ($article['description'] ?? '');

            $result = $sentimentService->analyze($text);

            $article['sentiment'] = $result['sentiment'];

            if ($result['sentiment'] == 'Positive') {
                $positive++;
            } elseif ($result['sentiment'] == 'Negative') {
                $negative++;
            } else {
                $neutral++;
            }
        }
    }

    $total = max(count($articles), 1);

    $positivePercent = round(($positive / $total) * 100);
    $neutralPercent  = round(($neutral / $total) * 100);
    $negativePercent = round(($negative / $total) * 100);

    $weatherRisk = 10;
    $inflationRisk = 20;
    $currencyRisk = 10;

    if ($negativePercent >= 60) {
        $politicalRisk = 40;
    } elseif ($negativePercent >= 30) {
        $politicalRisk = 20;
    } else {
        $politicalRisk = 10;
    }

    $totalRisk =
        $weatherRisk +
        $inflationRisk +
        $currencyRisk +
        $politicalRisk;

    if ($totalRisk >= 70) {
        $riskStatus = "HIGH";
    } elseif ($totalRisk >= 40) {
        $riskStatus = "MEDIUM";
    } else {
        $riskStatus = "LOW";
    }

    $recommendation = $riskService->recommendation($riskStatus);

    if($riskStatus=="HIGH"){

    $recommendation = [

        "Increase safety stock",

        "Use alternative suppliers",

        "Avoid affected ports",

        "Monitor supply chain every day"

    ];

}
elseif($riskStatus=="MEDIUM"){

    $recommendation = [

        "Monitor logistics routes",

        "Prepare backup suppliers",

        "Review inventory level",

        "Weekly monitoring"

    ];

}
else{

    $recommendation = [

        "Supply chain conditions are stable",

        "Continue normal procurement",

        "Monthly monitoring",

        "No immediate action required"

    ];

}
    return view(
        'admin.articles',
        compact(
            'countries',
            'country',
            'articles',
            'positivePercent',
            'neutralPercent',
            'negativePercent',
            'weatherRisk',
            'inflationRisk',
            'currencyRisk',
            'politicalRisk',
            'totalRisk',
            'riskStatus',
            'recommendation',
        )
    );
}
}