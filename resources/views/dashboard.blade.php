<!DOCTYPE html>
<html>
<head>
    <title>Global Supply Chain Dashboard</title>
    <link
rel="stylesheet"
href="https://unpkg.com/leaflet/dist/leaflet.css"/>
<link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.css" rel="stylesheet">

</head>
<body>

<h2>🌍 Global Risk Map</h2>

<div
id="map"
style="
height:600px;
width:100%;
border:1px solid #ddd;
margin-bottom:20px;
">
</div>

<div class="mb-4">

    <h2 class="fw-bold">
        🌍 Global Supply Chain Risk Intelligence
    </h2>

    <p class="text-muted">
        Real-time monitoring of global supply chain risks, logistics, economy,
        and geopolitical events.
    </p>

</div>

<div style="display:flex;align-items:end;gap:10px;">

    <form method="GET" id="countryForm">

        <input
        type="text"
        id="countrySearch"
        placeholder="Search Country..."
        autocomplete="off"
        value="{{ $country->name }}"
        style="width:320px;padding:8px;">

        <input
        type="hidden"
        name="country"
        id="countryCode"
        value="{{ $country->code }}">

        <div
        id="countryResult"
        style="
        border:1px solid #ccc;
        display:none;
        max-height:250px;
        overflow-y:auto;
        width:320px;
        background:white;
        position:absolute;
        z-index:999;">
        </div>

    </form>

    <form
    action="{{ route('favorite.add') }}"
    method="POST">

        @csrf

        <input
        type="hidden"
        name="country"
        value="{{ $country->code }}">

        <button class="btn btn-warning">

            ⭐ Monitor

        </button>

    </form>

</div>

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

<th>Language</th>

<td>

{{ $language }}

</td>

</tr>

<tr>

<th>Region</th>

<td>

{{ $country->region }}

</td>

</tr>

<tr>

<th>Exports</th>

<td>

@if($exports)

{{ number_format($exports,2) }} % GDP

@else

-

@endif

</td>

</tr>

<tr>

<th>Imports</th>

<td>

@if($imports)

{{ number_format($imports,2) }} % GDP

@else

-

@endif

</td>

</tr>

</table>

<br>

<hr>

<div class="row mb-4">

    <div class="col-md-3">

        <div class="card shadow-sm">

            <div class="card-body">

                <h6 class="text-muted">Supplier</h6>

                <h2>{{ $totalSuppliers }}</h2>

            </div>

        </div>

    </div>

    <div class="col-md-3">

        <div class="card shadow-sm">

            <div class="card-body">

            </div>

        </div>

    </div>

    <div class="col-md-3">

        <div class="card shadow-sm">

            <div class="card-body">

                <h6 class="text-muted">Countries</h6>

                <h2>{{ $totalCountries }}</h2>

            </div>

        </div>

    </div>

</div>

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

<th>Geopolitical Risk</th>

<th>Risk Score</th>

<th>Rainfall</th>

<th>Wind Speed</th>

<th>Status</th>

<th>Recommendation</th>

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

<td>{{ $geopoliticalRisk[$supplier->id] ?? '-' }}</td>

<td>
    {{ $riskScores[$supplier->id] ?? 0 }}
</td>

<td>{{ $weather[$supplier->id]['rain'] ?? '-' }} mm</td>

<td>{{ $weather[$supplier->id]['wind'] ?? '-' }} km/h</td>

<td>

@if(($riskStatus[$supplier->id] ?? '') == 'HIGH')

🔴 High

@elseif(($riskStatus[$supplier->id] ?? '') == 'MEDIUM')

🟡 Medium

@else

🟢 Low

@endif

</td>

<td>

{{ $recommendations[$supplier->id] ?? '-' }}

</td>

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

<tr>
    <td>{{ $country->currency }}</td>
    <td>{{ $exchangeRate[$country->currency] ?? '-' }}</td>
</tr>

</table>

<hr>

<h2>📈 Currency Impact Dashboard</h2>

<canvas
id="currencyChart"
width="900"
height="300">
</canvas>

<br><br>

<a href="{{ route('suppliers.index') }}">
Kelola Supplier
</a>

|

<a href="{{ route('simulation') }}">
Supply Chain Simulation
</a>

<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>

<script>

const map = L.map('map').setView(
[-2.5489,118.0149],
5
);

L.tileLayer(
'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
{
    attribution:'© OpenStreetMap'
}
).addTo(map);


L.marker([
{{ $country->latitude }},
{{ $country->longitude }}
])
.addTo(map)
.bindPopup(`

<h3>🌍 {{ $country->name }}</h3>

<b>🚢 Ports</b>

<br><br>

@if(isset($ports[$country->code]) && $ports[$country->code]->count())

@php
$mainPorts = $ports[$country->code]->take(5);
@endphp

@foreach($mainPorts as $port)

• {{ $port->name }}<br>

@endforeach

@if($ports[$country->code]->count() > 5)

<br>

<small><i>dan {{ $ports[$country->code]->count() - 5 }} pelabuhan lainnya...</i></small>

@endif

@else

No Port Data

@endif

<br><br>

<hr>

@if(isset($selectedWeather))

{{ $selectedWeather['temp'] }}°C

<br>

{{ $selectedWeather['desc'] }}

<br>

Rain: {{ $selectedWeather['rain'] }} mm

<br>

Wind: {{ $selectedWeather['wind'] }} km/h

<br><br>

@endif

<b>Supply Status</b>

<br>

🟢 Safe

`)
.openPopup();

</script>

<script>

const labels = @json(collect($currencyHistory)->pluck('date'));

const values = @json(collect($currencyHistory)->pluck('rate'));

const ctx = document.getElementById('currencyChart');

new Chart(ctx,{

    type:'bar',

    data:{

        labels:labels,

        datasets:[{

            label:'{{ $country->currency }} Exchange Rate',

            data:values,

            borderWidth:1

        }]

    },

    options:{

        responsive:true,

        plugins:{

            legend:{
                display:true
            }

        },

        scales:{

            y:{

                beginAtZero:false

            }

        }

    }

});

</script>

<script>

const countries = @json($countries);
console.log(countries);

const search = document.getElementById('countrySearch');
const result = document.getElementById('countryResult');
const code = document.getElementById('countryCode');
const form = document.getElementById('countryForm');

search.addEventListener('input', function(){

    const keyword = this.value.toLowerCase();

    result.innerHTML = '';

    if(keyword.length == 0){

        result.style.display = 'none';
        return;

    }

    const filtered = countries.filter(c =>
        c.name.toLowerCase().includes(keyword)
    );

    filtered.forEach(country=>{

        const div = document.createElement('div');

        div.style.padding = '8px';
        div.style.cursor = 'pointer';

        div.innerHTML = country.name;

        div.onclick = function(){

            search.value = country.name;

            code.value = country.code;

            result.style.display='none';

            form.submit();

        }

        result.appendChild(div);

    });

    result.style.display='block';

});

document.addEventListener('click',function(e){

    if(!result.contains(e.target) && e.target!==search){

        result.style.display='none';

    }

});

</script>   

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<hr>

<h3>⭐ Favorite Monitoring</h3>

@if($favorites->count())

<table class="table table-bordered">

    <thead>

        <tr>

            <th>Country</th>

            <th>Capital</th>

            <th>Region</th>

            <th>Action</th>

        </tr>

    </thead>

    <tbody>

        @foreach($favorites as $fav)

        <tr>

            <td>

               {{ optional($fav->country)->name ?? '-' }}
                {{ optional($fav->country)->capital ?? '-' }}
                {{ optional($fav->country)->region ?? '-' }}

            </td>

            <td>

                <form
                    action="{{ route('favorite.remove') }}"
                    method="POST">

                    @csrf

                    <input
                        type="hidden"
                        name="country"
                        value="{{ $fav->country_code }}">

                    <button
                        class="btn btn-danger btn-sm">

                        Remove

                    </button>

                </form>

            </td>

        </tr>

        @endforeach

    </tbody>

</table>

@else

<div class="alert alert-info">

    No monitored countries yet.

</div>

@endif

@include('partials.page-navigation')

<script>

const tooltipTriggerList =
document.querySelectorAll('[data-bs-toggle="tooltip"]');

[...tooltipTriggerList].map(
el => new bootstrap.Tooltip(el)
);

</script>
</body>
</html>
