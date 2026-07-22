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
public function edit(Supplier $supplier)
{
    $countries = Country::all();

    return view('suppliers.edit', compact('supplier', 'countries'));
}

public function update(Request $request, Supplier $supplier)
{
    $request->validate([
        'name' => 'required',
        'country_id' => 'required',
        'contact' => 'required'
    ]);

    $supplier->update([
        'name' => $request->name,
        'country_id' => $request->country_id,
        'contact' => $request->contact,
    ]);

    return redirect()
        ->route('suppliers.index')
        ->with('success', 'Supplier berhasil diperbarui.');
}

public function destroy(Supplier $supplier)
{
    $supplier->delete();

    return redirect()
        ->route('suppliers.index')
        ->with('success', 'Supplier berhasil dihapus.');
}
}