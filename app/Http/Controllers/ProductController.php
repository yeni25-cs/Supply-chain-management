<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
{
    $products = Product::with('supplier')->get();

    return view('products.index', compact('products'));
}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
{
    $suppliers = Supplier::all();

    return view('products.create', compact('suppliers'));
}

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    $request->validate([
        'name' => 'required',
        'supplier_id' => 'required',
        'stock' => 'required|integer'
    ]);

   $supplier = Supplier::find($request->supplier_id);

$risk = Product::calculateRisk(
    $request->stock,
    $supplier->country
);

Product::create([
    'name' => $request->name,
    'supplier_id' => $request->supplier_id,
    'stock' => $request->stock,
    'risk_score' => $risk['score'],
    'risk_level' => $risk['level'],
]);

    return redirect()->route('products.index');
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
    public function edit(Product $product)
{
    $suppliers = Supplier::all();

    return view('products.edit', compact('product','suppliers'));
}

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
{
    $request->validate([
        'name' => 'required',
        'supplier_id' => 'required',
        'stock' => 'required|integer'
    ]);

    $supplier = Supplier::find($request->supplier_id);

$risk = Product::calculateRisk(
    $request->stock,
    $supplier->country
);

$product->update([
    'name' => $request->name,
    'supplier_id' => $request->supplier_id,
    'stock' => $request->stock,
    'risk_score' => $risk['score'],
    'risk_level' => $risk['level'],
]);

    return redirect()->route('products.index');
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
{
    $product->delete();

    return redirect()->route('products.index');
}
}
