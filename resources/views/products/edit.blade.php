@extends('layouts.app')

@section('content')

<div class="container">

<h2>Edit Product</h2>

<form action="{{ route('products.update',$product->id) }}" method="POST">

@csrf
@method('PUT')

<div class="mb-3">

<label>Nama Product</label>

<input
type="text"
name="name"
class="form-control"
value="{{ $product->name }}">

</div>

<div class="mb-3">

<label>Supplier</label>

<select
name="supplier_id"
class="form-control">

@foreach($suppliers as $supplier)

<option
value="{{ $supplier->id }}"
{{ $product->supplier_id == $supplier->id ? 'selected' : '' }}>

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
class="form-control"
value="{{ $product->stock }}">

</div>

<button class="btn btn-primary">

Update

</button>

</form>

</div>

@endsection