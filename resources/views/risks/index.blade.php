@extends('layouts.app')

@section('content')

<h2 class="mb-4">

Supplier Country Risk Monitoring

</h2>

<table class="table table-bordered">

<thead>

<tr>

<th>Supplier</th>

<th>Country</th>

<th>Weather</th>

<th>Inflation</th>

<th>Risk Score</th>

<th>Status</th>

</tr>

</thead>

<tbody>

@foreach($riskData as $risk)

<tr>

<td>

{{ $risk['supplier'] }}

</td>

<td>

{{ $risk['country'] }}

</td>

<td>

{{ $risk['weather'] }}

</td>

<td>

{{ $risk['inflation'] }} %

</td>

<td>

{{ $risk['risk'] }}

</td>

<td>

@if($risk['risk'] >= 80)

<span class="badge bg-danger">

High

</span>

@elseif($risk['risk'] >= 50)

<span class="badge bg-warning text-dark">

Medium

</span>

@else

<span class="badge bg-success">

Low

</span>

@endif

</td>

</tr>

@endforeach

</tbody>

</table>

@endsection