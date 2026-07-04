<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Supplier;
use App\Models\Country;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $suppliers = Supplier::with('country')->get();

        return view(
            'suppliers.index',
            compact('suppliers')
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $countries = Country::all();

        return view(
            'suppliers.create',
            compact('countries')
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'country_id' => 'required',
            'contact' => 'required'
        ]);

        Supplier::create($request->all());

        return redirect()
                ->route('suppliers.index')
                ->with(
                    'success',
                    'Supplier berhasil ditambahkan.'
                );
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(string $id)
    {
        //
    }
}