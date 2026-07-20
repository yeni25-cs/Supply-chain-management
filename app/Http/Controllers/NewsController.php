<?php

namespace App\Http\Controllers;

use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

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
}