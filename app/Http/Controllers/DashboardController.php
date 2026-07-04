<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use App\Models\Supplier;
use App\Models\Product;
use App\Models\Country;

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
            ->whereHas('country', function ($q) use ($selectedCountry) {
                $q->where('code', $selectedCountry);
            })
            ->get();

        // Statistik
        $totalSuppliers = $suppliers->count();

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
        $weather = [];
        $inflation = [];
        $news = [];
        $gdp = null;

        // =========================
        // EXCHANGE RATE API
        // =========================

        try {

            $response = Http::get(
                'https://open.er-api.com/v6/latest/USD'
            );

            if ($response->successful()) {

                $exchangeRate = $response->json()['rates'];

            }

        } catch (\Exception $e) {
        }

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

                    $news[$supplier->id] = (string)
                        $rss->channel->item[0]->title;

                }

            } catch (\Exception $e) {
            }

        }

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
                'gdp'
            )
        );
    }
}   