<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use App\Models\Supplier;
use App\Models\Product;
use App\Models\Country;
use App\Services\SentimentService;
use App\Services\RiskScoringService;
use App\Models\Port;

class DashboardController extends Controller
{
    public function index()
    {
        // Negara yang dipilih
        $selectedCountry = request('country') ?? 'ID';

        // Semua negara untuk dropdown
        $countries = Country::orderBy('name')->get();

        // Detail negara yang dipilih
        $country = Country::where('code', $selectedCountry)->first();

        // Supplier berdasarkan negara
       $suppliers = Supplier::with('country')
        ->where('country_id',$country->id)
        ->get();

        // Statistik
        $totalSuppliers = Supplier::where(
            'country_id',
            $country->id
        )->count();

        $totalProducts = Product::whereHas('supplier.country', function ($q) use ($selectedCountry) {
            $q->where('code', $selectedCountry);
        })->count();

        $totalCountries = Country::count();

        // Risk
        $highRisk = Product::whereHas('supplier.country', function ($q) use ($selectedCountry) {
                $q->where('code', $selectedCountry);
            })
            ->where('risk_score', '>=', 80)
            ->count();

        $mediumRisk = Product::whereHas('supplier.country', function ($q) use ($selectedCountry) {
                $q->where('code', $selectedCountry);
            })
            ->whereBetween('risk_score', [50,79])
            ->count();

        $lowRisk = Product::whereHas('supplier.country', function ($q) use ($selectedCountry) {
                $q->where('code', $selectedCountry);
            })
            ->where('risk_score', '<', 50)
            ->count();

        $exchangeRate = [];
        $currencyHistory = [];
        $weather = [];
        $countryWeather = [];   
        $inflation = [];
        $news = [];
        $sentiments = [];
        $sentimentService = new SentimentService();
        $riskService = new RiskScoringService();
        $geopoliticalRisk = [];
        $gdp = null;
        $riskScores = [];
        $riskStatus = [];
        $recommendations = [];

// =========================
// EXCHANGE RATE API
// =========================

$exchangeRate = [];
$currencyHistory = [];

try {

    $response = Http::get(
        'https://open.er-api.com/v6/latest/USD'
    );

    if ($response->successful()) {

        $rates = $response->json()['rates'];

        $currencyCode = strtoupper($country->currency);

        if (isset($rates[$currencyCode])) {

            $selectedRate = $rates[$currencyCode];

            // Untuk tabel Exchange Rate
            $exchangeRate = [
                $currencyCode => $selectedRate
            ];

            // Simulasi grafik 7 hari
            for ($i = 6; $i >= 0; $i--) {

                $date = now()->subDays($i)->format('d M');

                // perubahan acak ±0.5%
                $random = rand(-50, 50) / 10000;

                $currencyHistory[] = [

                    'date' => $date,

                    'rate' => round(
                        $selectedRate * (1 + $random),
                        2
                    )

                ];

            }

            // Supaya titik terakhir = nilai tabel
            $currencyHistory[6]['rate'] = $selectedRate;

        }

    }

} catch (\Exception $e) {

    $exchangeRate = [];
    $currencyHistory = [];

}
        
        $highRiskKeywords = [

    'war',
    'attack',
    'missile',
    'military',
    'conflict',
    'sanction',
    'terror',
    'strike',
    'protest',
    'riot',
    'earthquake',
    'flood',
    'tsunami',
    'hurricane',
    'cyclone'

];

        // =========================
        // WEATHER + INFLATION + NEWS
        // =========================

        foreach ($suppliers as $supplier) {

            if (!$supplier->country) {
                continue;
            }

            // WEATHER
            try {

                $capital = $supplier->country->capital;

                if ($capital) {

                    $response = Http::timeout(10)->get(
                        'https://wttr.in/' .
                        urlencode($capital) .
                        '?format=j1'
                    );

                    if ($response->successful()) {

                        $data = $response->json();

                        $weather[$supplier->id] = [

                            'temp' => $data['current_condition'][0]['temp_C'],

                            'desc' => $data['current_condition'][0]['weatherDesc'][0]['value']

                        ];

                        // Simpan juga berdasarkan kode negara
                        $countryWeather[$supplier->country->code] = [

                            'temp' => $data['current_condition'][0]['temp_C'],

                            'desc' => $data['current_condition'][0]['weatherDesc'][0]['value']

                        ];

                    }

                }

            } catch (\Exception $e) {
            }

            // INFLATION
            try {

                $countryCode = $supplier->country->code;

                $response = Http::timeout(10)->get(
                    'https://api.worldbank.org/v2/country/' .
                    $countryCode .
                    '/indicator/FP.CPI.TOTL.ZG?format=json'
                );

                if ($response->successful()) {

                    $data = $response->json();

                    if (isset($data[1])) {

                        foreach ($data[1] as $item) {

                            if ($item['value'] != null) {

                                $inflation[$supplier->id] = round($item['value'],2);

                                break;

                            }

                        }

                    }

                }

            } catch (\Exception $e) {
            }

         // NEWS
try {

    $countryName = $supplier->country->name;

    $rss = simplexml_load_file(
        'https://news.google.com/rss/search?q=' .
        urlencode($countryName)
    );

    if (isset($rss->channel->item[0])) {

        // Ambil berita dulu
        $news[$supplier->id] =
            (string) $rss->channel->item[0]->title;

        // Baru analisis beritanya
        $title = strtolower($news[$supplier->id]);

        $result =

$sentimentService->analyze(

$news[$supplier->id]

);

$sentiments[$supplier->id] = $result;

        $score = 0;

        foreach ($highRiskKeywords as $keyword) {

            if (str_contains($title, $keyword)) {

                $score += 20;

            }

        }

        if ($score >= 60) {

            $geopoliticalRisk[$supplier->id] = 'HIGH';

        } elseif ($score >= 20) {

            $geopoliticalRisk[$supplier->id] = 'MEDIUM';

        } else {

            $geopoliticalRisk[$supplier->id] = 'LOW';

        }

    }

} catch (\Exception $e) {
}
$weatherDesc = $weather[$supplier->id]['desc'] ?? '';

$inflationValue = $inflation[$supplier->id] ?? 0;

$sentiment = strtolower(
    $sentiments[$supplier->id]['label'] ?? 'neutral'
);

$geo = $geopoliticalRisk[$supplier->id] ?? 'LOW';

$exchange = 0;

if (!empty($exchangeRate)) {
    $exchange = array_values($exchangeRate)[0];
}

$score = $riskService->calculate(
    $weatherDesc,
    $inflationValue,
    $sentiment,
    $geo,
    $exchange
);

$riskScores[$supplier->id] = $score;

$riskStatus[$supplier->id] = $riskService->status($score);

if ($score >= 80) {

    $recommendations[$supplier->id] = '❌ Delay Shipment';

} elseif ($score >= 50) {

    $recommendations[$supplier->id] = '⚠ Proceed with Caution';

} else {

    $recommendations[$supplier->id] = '✅ Safe to Import';

}
} // 
        // =========================
        // GDP API
        // =========================

        try {

            $response = Http::timeout(10)->get(
                'https://api.worldbank.org/v2/country/' .
                $selectedCountry .
                '/indicator/NY.GDP.MKTP.CD?format=json'
            );

            if ($response->successful()) {

                $data = $response->json();

                if (isset($data[1])) {

                    foreach ($data[1] as $item) {

                        if ($item['value'] != null) {

                            $gdp = $item['value'];

                            break;

                        }

                    }

                }

            }

        } catch (\Exception $e) {
        }

    $mapCountries = Country::with('ports')
    ->whereNotNull('latitude')
    ->whereNotNull('longitude')
    ->get();

    // =========================
// WEATHER FOR MAP (CACHE 30 MENIT)
// =========================

$countryWeather = [];

foreach ($mapCountries->take(5) as $mapCountry) {

    $countryWeather[$mapCountry->code] = Cache::remember(

        'weather_'.$mapCountry->code,

        now()->addMinutes(30),

        function () use ($mapCountry) {

            try {

                if (!$mapCountry->capital) {

                    return [

                        'temp' => '-',

                        'desc' => '-'

                    ];

                }

                $response = Http::timeout(10)->get(

                    'https://wttr.in/' .

                    urlencode($mapCountry->capital) .

                    '?format=j1'

                );

                if ($response->successful()) {

                    $data = $response->json();

                    return [

                        'temp' => $data['current_condition'][0]['temp_C'],

                        'desc' => $data['current_condition'][0]['weatherDesc'][0]['value']

                    ];

                }

            } catch (\Exception $e) {

            }

            return [

                'temp' => '-',

                'desc' => '-'

            ];

        }

    );

}

    $ports = Port::select(
    'name',
    'country_code'
    )
    ->orderBy('name')
    ->get()
    ->groupBy('country_code');
        return view(
            'dashboard',
            compact(
                'countries',
                'country',
                'suppliers',
                'totalSuppliers',
                'totalProducts',
                'totalCountries',
                'highRisk',
                'mediumRisk',
                'lowRisk',
                'exchangeRate',
                'weather',
                'inflation',
                'news',
                'sentiments',
                'gdp',
                'geopoliticalRisk',
                'mapCountries',
                'ports',
                'riskScores',
                'riskStatus',
                'recommendations',
                'currencyHistory',
                'countryWeather',
            )
        );
    }
}   