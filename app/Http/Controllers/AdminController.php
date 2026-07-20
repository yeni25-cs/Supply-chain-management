<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\Supplier;
use App\Models\Port;
use App\Models\FavoriteCountry;
use App\Models\User;

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
}