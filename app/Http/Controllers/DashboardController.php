<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Supplier;
use App\Models\Country;
use App\Models\Port;
use App\Models\FavoriteCountry;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use App\Services\SentimentService;
use App\Services\RiskScoringService;

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

        $totalCountries = Country::count();


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

    if ($response->successful()) {

    $data = $response->json();

    if (
        isset($data[0]) &&
        isset($data[0]['languages'])
    ) {

        $language = implode(', ', array_values($data[0]['languages']));

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

    if (
        $supplier->country &&
        $supplier->country->latitude &&
        $supplier->country->longitude
    ) {

        $response = Http::timeout(10)->get(
            'https://api.open-meteo.com/v1/forecast',
            [
                'latitude' => $supplier->country->latitude,
                'longitude' => $supplier->country->longitude,

                // Data cuaca yang diambil
                'current' => 'temperature_2m,weather_code,wind_speed_10m,rain'
            ]
        );

        if ($response->successful()) {

            $data = $response->json();

            $current = $data['current'];

            // Konversi weather code menjadi teks
            $weatherText = match ($current['weather_code']) {

                0 => 'Clear',

                1,2 => 'Partly Cloudy',

                3 => 'Cloudy',

                45,48 => 'Fog',

                51,53,55 => 'Drizzle',

                61,63,65 => 'Rain',

                71,73,75 => 'Snow',

                80,81,82 => 'Rain Shower',

                95 => 'Thunderstorm',

                default => 'Unknown'

            };

            $weather[$supplier->id] = [

                'temp' => $current['temperature_2m'],

                'desc' => $weatherText,

                'rain' => $current['rain'] ?? 0,

                'wind' => $current['wind_speed_10m']

            ];

            // Untuk popup map
            $countryWeather[$supplier->country->code] = $weather[$supplier->id];

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

        // =========================
// EXPORTS (% GDP)
// =========================

$exports = null;

try {

    $response = Http::timeout(10)->get(
        'https://api.worldbank.org/v2/country/' .
        $selectedCountry .
        '/indicator/NE.EXP.GNFS.ZS?format=json'
    );

    if ($response->successful()) {

        $data = $response->json();

        if (isset($data[1])) {

            foreach ($data[1] as $item) {

                if ($item['value'] != null) {

                    $exports = round($item['value'], 2);

                    break;

                }

            }

        }

    }

} catch (\Exception $e) {
}


// =========================
// IMPORTS (% GDP)
// =========================

$imports = null;

try {

    $response = Http::timeout(10)->get(
        'https://api.worldbank.org/v2/country/' .
        $selectedCountry .
        '/indicator/NE.IMP.GNFS.ZS?format=json'
    );

    if ($response->successful()) {

        $data = $response->json();

        if (isset($data[1])) {

            foreach ($data[1] as $item) {

                if ($item['value'] != null) {

                    $imports = round($item['value'], 2);

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

                if (
                    !$mapCountry->latitude ||
                    !$mapCountry->longitude
                ) {

                    return [

                        'temp' => '-',

                        'desc' => '-',

                        'rain' => 0,

                        'wind' => 0

                    ];

                }

                $response = Http::timeout(10)->get(

                    'https://api.open-meteo.com/v1/forecast',

                    [

                        'latitude' => $mapCountry->latitude,

                        'longitude' => $mapCountry->longitude,

                        'current' => 'temperature_2m,weather_code,wind_speed_10m,rain'

                    ]

                );

                if ($response->successful()) {

                    $current = $response->json()['current'];

                    $weatherText = match ($current['weather_code']) {

                        0 => 'Clear',

                        1,2 => 'Partly Cloudy',

                        3 => 'Cloudy',

                        45,48 => 'Fog',

                        51,53,55 => 'Drizzle',

                        61,63,65 => 'Rain',

                        71,73,75 => 'Snow',

                        80,81,82 => 'Rain Shower',

                        95 => 'Thunderstorm',

                        default => 'Unknown'

                    };

                    return [

                        'temp' => $current['temperature_2m'],

                        'desc' => $weatherText,

                        'rain' => $current['rain'] ?? 0,

                        'wind' => $current['wind_speed_10m']

                    ];

                }

            } catch (\Exception $e) {

            }

            return [

                'temp' => '-',

                'desc' => '-',

                'rain' => 0,

                'wind' => 0

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

    $selectedWeather = null;

try {

    if ($country->latitude && $country->longitude) {

        $response = Http::timeout(10)->get(
            'https://api.open-meteo.com/v1/forecast',
            [
                'latitude' => $country->latitude,
                'longitude' => $country->longitude,
                'current' => 'temperature_2m,weather_code,wind_speed_10m,rain'
            ]
        );

        if ($response->successful()) {

            $current = $response->json()['current'];

            $weatherText = match ($current['weather_code']) {

                0 => 'Clear',
                1, 2 => 'Partly Cloudy',
                3 => 'Cloudy',
                45, 48 => 'Fog',
                51, 53, 55 => 'Drizzle',
                61, 63, 65 => 'Rain',
                71, 73, 75 => 'Snow',
                80, 81, 82 => 'Rain Shower',
                95 => 'Thunderstorm',
                default => 'Unknown'

            };

            $selectedWeather = [

                'temp' => $current['temperature_2m'],

                'desc' => $weatherText,

                'rain' => $current['rain'] ?? 0,

                'wind' => $current['wind_speed_10m']

            ];

        }

    }

} catch (\Exception $e) {

    $selectedWeather = null;

}
$language = $country->language ?? '-';

$favorites = FavoriteCountry::with('country')->get();

        return view(
        'dashboard',
                compact(
                'countries',
                'country',
                'suppliers',
                'totalSuppliers',
                'totalCountries',
                'exchangeRate',
                'weather',
                'inflation',
                'news',
                'sentiments',
                'gdp',
                'exports',
                'imports',
                'geopoliticalRisk',
                'mapCountries',
                'ports',
                'riskScores',
                'riskStatus',
                'recommendations',
                'currencyHistory',
                'countryWeather',
                'selectedWeather',
                'language',
                'favorites',
            )
        );
    }

public function addFavorite(Request $request)
{
    FavoriteCountry::firstOrCreate([

        'country_code'=>$request->country

    ]);

    return back();

}

public function removeFavorite(Request $request)
{

    FavoriteCountry::where(

        'country_code',

        $request->country

    )->delete();

    return back();

}

public function favorites()
{
    $favorites = FavoriteCountry::with('country')->get();

    return view('favorites', compact('favorites'));
}
}   