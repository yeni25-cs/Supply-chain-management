<!DOCTYPE html>
<html>
<head>
    <title>Global Supply Chain Dashboard</title>
</head>
<body>

<h1>Global Supply Chain Dashboard</h1>

<hr>

<h2>Country Dashboard</h2>

<form method="GET">

<select name="country">

@foreach($countries as $c)

<option
value="{{ $c->code }}"
{{ $country->code == $c->code ? 'selected' : '' }}>

{{ $c->name }}

</option>

@endforeach

</select>

<button type="submit">

View Country

</button>

</form>

<br>

<table border="1" cellpadding="10">

<tr>

<th>Country</th>

<td>

{{ $country->name }}

</td>

</tr>

<tr>

<th>Capital</th>

<td>

{{ $country->capital }}

</td>

</tr>

<tr>

<th>Population</th>

<td>

{{ number_format($country->population) }}

</td>

</tr>

<tr>

<th>GDP</th>

<td>

@if($gdp)

US$ {{ number_format($gdp,0,',','.') }}

@else

-

@endif

</td>

</tr>

<tr>

<th>Currency</th>

<td>

{{ $country->currency }}

</td>

</tr>

<tr>

<th>Region</th>

<td>

{{ $country->region }}

</td>

</tr>

</table>

<br>

<hr>

<h2>Statistik</h2>

<table border="1" cellpadding="10">

<tr>
<th>Total Supplier</th>
<th>Total Product</th>
<th>Total Negara</th>
</tr>

<tr>
<td>{{ $totalSuppliers }}</td>
<td>{{ $totalProducts }}</td>
<td>{{ $totalCountries }}</td>
</tr>

</table>

<br>

<h2>Risk Monitoring</h2>

<table border="1" cellpadding="10">

<tr>
<th>High Risk</th>
<th>Medium Risk</th>
<th>Low Risk</th>
</tr>

<tr>
<td>{{ $highRisk }}</td>
<td>{{ $mediumRisk }}</td>
<td>{{ $lowRisk }}</td>
</tr>

</table>

<br>

<h2>Supplier List</h2>

<table border="1" cellpadding="10">

<tr>
<th>Supplier</th>
<th>Country</th>
<th>Currency</th>
<th>Weather</th>
<th>Temperature</th>
<th>Inflation</th>
<th>Latest News</th>
</tr>

@foreach($suppliers as $supplier)

<tr>

<td>{{ $supplier->name }}</td>

<td>{{ $supplier->country?->name ?? '-' }}</td>

<td>{{ $supplier->country?->currency ?? '-' }}</td>

<td>{{ $weather[$supplier->id]['desc'] ?? '-' }}</td>

<td>{{ $weather[$supplier->id]['temp'] ?? '-' }} °C</td>

<td>{{ $inflation[$supplier->id] ?? '-' }} %</td>

<td>{{ $news[$supplier->id] ?? '-' }}</td>

</tr>

@endforeach

</table>

<br>

<h2>Exchange Rate (USD)</h2>

<table border="1" cellpadding="10">

<tr>
<th>Currency</th>
<th>Rate</th>
</tr>

@foreach(array_slice($exchangeRate,0,10) as $currency=>$rate)

<tr>

<td>{{ $currency }}</td>

<td>{{ $rate }}</td>

</tr>

@endforeach

</table>

<br><br>

<a href="{{ route('suppliers.index') }}">
Kelola Supplier
</a>

|

<a href="{{ route('products.index') }}">
Kelola Product
</a>

</body>
</html>