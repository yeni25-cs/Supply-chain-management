<!DOCTYPE html>
<html>

<head>

    <title>Supply Chain Simulation</title>

</head>

<body>

<h1>🚢 Supply Chain Simulation</h1>

<hr>

<form>

<h3>Supplier</h3>

<select>

@foreach($suppliers as $supplier)

<option value="{{ $supplier->id }}">

{{ $supplier->name }}
(
{{ $supplier->country?->name ?? 'Unknown Country' }}
)

</option>

@endforeach

</select>

<br><br>

<h3>Origin Port</h3>

<select name="origin_port">

@foreach($ports as $port)

<option value="{{ $port->id }}">

{{ $port->name }}
(
{{ $port->country_name ?? 'Unknown Country' }}
)

</option>

@endforeach

</select>

<br><br>

<h3>Destination Country</h3>

<select>

@foreach($countries as $country)

<option>

{{ $country->name }}

</option>

@endforeach

</select>

<br><br>

<button>

Simulate Shipment

</button>

</form>

</body>

</html>