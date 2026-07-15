<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\Port;
use App\Models\Supplier;

class SimulationController extends Controller
{
    public function index()
    {
        $countries = Country::orderBy('name')->get();

        $suppliers = Supplier::with('country')->get();

        $ports = Port::with('country')
            ->orderBy('name')
            ->get();

        return view(
            'simulation',
            compact(
                'countries',
                'suppliers',
                'ports'
            )
        );
    }
}