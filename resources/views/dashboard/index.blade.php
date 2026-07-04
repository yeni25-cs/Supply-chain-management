@extends('layouts.app')

@section('content')

<h2 class="mb-4">
    Dashboard
</h2>

<div class="row">

    <div class="col-md-3">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h6>Total Supplier</h6>
                <h2>{{ $totalSupplier }}</h2>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h6>Total Product</h6>
                <h2>85</h2>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h6>High Risk</h6>
                <h2>5</h2>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h6>API Success</h6>
                <h2>98%</h2>
            </div>
        </div>
    </div>

</div>

@endsection