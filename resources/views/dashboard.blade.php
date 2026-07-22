<!DOCTYPE html>
<html>

<head>
    <title>Global Supply Chain Dashboard</title>

    <link rel="stylesheet"
          href="https://unpkg.com/leaflet/dist/leaflet.css"/>

    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.css"
          rel="stylesheet">

    <!-- Google Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap"
          rel="stylesheet">

    <style>

        body{
            margin:0;
            font-family:'Poppins',sans-serif;
            background:#f5f7fb;
        }

        .topbar{

            height:72px;

            background:#0F172A;

            display:flex;

            align-items:center;

            justify-content:space-between;

            padding:0 35px;

            box-shadow:0 3px 12px rgba(0,0,0,.15);

        }

        .brand{

            color:white;

            font-size:26px;

            font-weight:700;

            letter-spacing:.5px;

        }

        .topbar-center{

            flex:1;

            display:flex;

            justify-content:center;

        }
        .topbar-search{
    display:flex;
    align-items:center;
    gap:12px;
}

.topbar-search input[type="text"]{
    width:320px !important;
    padding:10px 15px !important;
    border:none;
    border-radius:25px;
    outline:none;
    font-size:15px;
    font-family:'Poppins',sans-serif;
}

.topbar-search button{
    padding:10px 18px;
    border:none;
    border-radius:25px;
    background:#2563EB;
    color:white;
    font-weight:500;
    cursor:pointer;
    transition:.2s;
}

.topbar-search button:hover{
    background:#1D4ED8;
}

        .topbar-right{

            display:flex;

            align-items:center;

            gap:15px;

        }

    </style>

</head>

<body>

    <!-- =========================
         TOP BAR
    ========================== -->

    <nav class="topbar">

        <div class="brand">
            🌍 ChainPulse AI
        </div>

        <div class="topbar-center">

         <div class="topbar-center">

    <div class="topbar-search">

        <form method="GET" id="countryForm">

            <input
                type="text"
                id="countrySearch"
                placeholder="🔍 Search Country..."
                autocomplete="off"
                value="{{ $country->name }}">

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
                    z-index:9999;
                    border-radius:12px;
                    margin-top:5px;
                ">
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

            <button type="submit">

                Add Favorite

            </button>

        </form>

    </div>

</div>

        </div>

        <div class="topbar-right">

<div style="position:relative;">

        <button
            onclick="toggleMenu()"
            style="
                font-size:28px;
                border:none;
                background:none;
                cursor:pointer;
                color:white;
            ">
            ☰
        </button>

        <div
            id="menuDropdown"
            style="
                display:none;
                position:absolute;
                right:0;
                top:40px;
                background:white;
                min-width:220px;
                border:1px solid #ddd;
                border-radius:8px;
                box-shadow:0 4px 10px rgba(247, 243, 243, 0.2);
                z-index:9999;
            ">

            <a
                href="{{ route('suppliers.index') }}"
                style="
                    display:block;
                    padding:12px;
                    color:black;
                    text-decoration:none;
                ">
                Kelola Supplier
            </a>

            <a
                href="{{ route('favorites') }}"
                style="
                    display:block;
                    padding:12px;
                    color:black;
                    text-decoration:none;
                ">
                Favorite Monitoring
            </a>

<hr style="margin:8px 0;">

<form action="{{ route('logout') }}" method="POST">

    @csrf

    <button
        type="submit"
        style="
            width:100%;
            border:none;
            background:none;
            text-align:left;
            padding:12px 15px;
            cursor:pointer;
            color:#dc3545;
            font-size:16px;
        "
        onmouseover="this.style.background='#f8f9fa'"
        onmouseout="this.style.background='white'">

        🚪 Logout

    </button>

        </form>
        </div>
    </div>

        </div>

    </nav>


<div class="main-content">

<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px; position:relative;">

</div>
<div
id="map"
style="
height:600px;
width:85%;
margin:25px auto 35px auto;
border:1px solid #ddd;
border-radius:15px;
box-shadow:0 4px 12px rgba(0,0,0,.08);
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

            Add Favorite

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

<th>Inflation</th>

<th>Latest News</th>

<th>Geopolitical Risk</th>

<th>Status</th>

</tr>

@foreach($suppliers as $supplier)

<tr>

<td>{{ $supplier->name }}</td>

<td>{{ $supplier->country?->name ?? '-' }}</td>

<td>{{ $inflation[$supplier->id] ?? '-' }} %</td>

<td>{{ $news[$supplier->id] ?? '-' }}</td>

<td>{{ $geopoliticalRisk[$supplier->id] ?? '-' }}</td>

<td>
@if(($riskStatus[$supplier->id] ?? '') == 'HIGH')

High

@elseif(($riskStatus[$supplier->id] ?? '') == 'MEDIUM')

Medium

@else

Low

@endif

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

<h2>Currency Impact Dashboard</h2>

<canvas
id="currencyChart"
width="900"
height="300">
</canvas>

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

<b>Ports</b>

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

Safe

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

@include('partials.page-navigation')

<script>

const tooltipTriggerList =
document.querySelectorAll('[data-bs-toggle="tooltip"]');

[...tooltipTriggerList].map(
el => new bootstrap.Tooltip(el)
);

</script>

<script>

function toggleMenu(){

    var menu = document.getElementById("menuDropdown");

    if(menu.style.display=="block"){

        menu.style.display="none";

    }else{

        menu.style.display="block";

    }

}

window.onclick = function(e){

    if(!e.target.matches('button')){

        document.getElementById("menuDropdown").style.display="none";

    }

}

</script>

</div>
</body>
</html>
