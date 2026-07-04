@extends('layouts.app')

@section('content')

<div class="container">

<h2>Tambah Product</h2>

<form action="{{ route('products.store') }}" method="POST">

@csrf

<div class="mb-3">

<label>Nama Product</label>

<input
type="text"
name="name"
class="form-control">

</div>

<div class="mb-3">

<label>Supplier</label>

<select
name="supplier_id"
class="form-control">

@foreach($suppliers as $supplier)

<option value="{{ $supplier->id }}">

{{ $supplier->name }}

</option>

@endforeach

</select>

</div>

<div class="mb-3">

<label>Stock</label>

<input
type="number"
name="stock"
class="form-control">

</div>

<button class="btn btn-success">

Simpan

</button>

</form>

</div>

@endsection