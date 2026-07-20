@extends('layouts.app')

@section('content')

<div class="card shadow-sm">

    <div class="card-header d-flex justify-content-between align-items-center">

        <h4 class="mb-0">
            🚢 Port Management
        </h4>

        <form action="{{ route('ports.updateWeather') }}" method="POST">

            @csrf

            <input
                type="hidden"
                name="country"
                value="{{ $country->code }}">

            <button class="btn btn-primary">

                🌦 Update Weather

            </button>

        </form>

    </div>

    <div class="card-body">

        <form method="GET" class="mb-4">

            <label class="form-label fw-semibold">

                Search Country

            </label>

            <select
                name="country"
                class="form-select"
                onchange="this.form.submit()">

                @foreach($countries as $c)

                    <option
                        value="{{ $c->code }}"
                        {{ request('country',$country->code)==$c->code?'selected':'' }}>

                        {{ $c->name }}

                    </option>

                @endforeach

            </select>

        </form>

        <div class="mt-3">

            <label class="form-label fw-semibold">

                Search Port

            </label>

            <input
                type="text"
                id="searchPort"
                class="form-control"
                placeholder="Cari nama pelabuhan...">

        </div>

        <hr>

        <div
            id="portMap"
            style="height:550px;border-radius:10px;">
        </div>

        <br>

        <table class="table table-striped table-hover align-middle">

            <thead class="table-dark">

                <tr>

                    <th>No</th>

                    <th>Port Name</th>

                    <th>City</th>

                    <th>Risk</th>

                    <th>Latitude</th>

                    <th>Longitude</th>

                </tr>

            </thead>

            <tbody>

                @forelse($ports as $i=>$port)

                <tr>

                    <td>{{ $i+1 }}</td>

                    <td>{{ $port->name }}</td>

                    <td>{{ $port->city ?? '-' }}</td>

                    <td>

                        @if($port->risk_status=='HIGH')

                            <span class="badge bg-danger">

                                HIGH

                            </span>

                        @else

                            <span class="badge bg-success">

                                LOW

                            </span>

                        @endif

                    </td>

                    <td>{{ $port->latitude }}</td>

                    <td>{{ $port->longitude }}</td>

                </tr>

                @empty

                <tr>

                    <td colspan="6" class="text-center">

                        No Port Found

                    </td>

                </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

@include('partials.page-navigation')

<link
rel="stylesheet"
href="https://unpkg.com/leaflet/dist/leaflet.css"/>

<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script>
window.PORT_DATA = {

    country:{
        lat: {{ $country->latitude }},
        lon: {{ $country->longitude }}
    },

    ports: [

        @foreach($ports as $port)

        {
            name: @json($port->name),
            city: @json($port->city ?? '-'),
            risk: @json($port->risk_status),
            lat: {{ $port->latitude }},
            lon: {{ $port->longitude }}
        },

        @endforeach

    ]

};
</script>

<link
rel="stylesheet"
href="https://unpkg.com/leaflet/dist/leaflet.css">

<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

<script src="{{ asset('js/ports.js') }}"></script>
@endsection