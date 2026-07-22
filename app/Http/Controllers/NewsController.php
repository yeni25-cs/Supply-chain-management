<?php

namespace App\Http\Controllers;

use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Services\SentimentService;
use App\Services\RiskScoringService;

class NewsController extends Controller
{
    public function index(Request $request)
{
    $countries = Country::orderBy('name')->get();

    $country = $request->country ?? 'Indonesia';

    $apiKey = env('GNEWS_API_KEY');

    $response = Http::get(
        'https://gnews.io/api/v4/search',
        [
            'q'       => $country . ' logistics OR shipping OR trade OR economy',
            'lang'    => 'en',
            'max'     => 10,
            'apikey'  => $apiKey
        ]
    );

    $articles = [];

    if($response->successful()){

        $articles = $response->json()['articles'];

    }

    return view(
        'news.index',
        compact(
            'countries',
            'country',
            'articles'
        )
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
    $neutral  = 0;

    if ($response->successful()) {

        $articles = $response->json()['articles'];

        foreach ($articles as &$article) {

            $text = ($article['title'] ?? '') . ' ' . ($article['description'] ?? '');

            $result = $sentimentService->analyze($text);

            $article['sentiment'] = $result['sentiment'];

            switch ($result['sentiment']) {

                case 'Positive':
                    $positive++;
                    break;

                case 'Negative':
                    $negative++;
                    break;

                default:
                    $neutral++;
            }
        }
    }

    $total = max(count($articles), 1);

    $positivePercent = round($positive / $total * 100);
    $neutralPercent  = round($neutral / $total * 100);
    $negativePercent = round($negative / $total * 100);

    // contoh sementara
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
            'riskStatus'
        )
    );
}
}