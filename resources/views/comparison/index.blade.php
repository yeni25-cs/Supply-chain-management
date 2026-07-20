@extends('layouts.app')

@section('content')

<div class="card shadow-sm">

    <div class="card-header">

        <h3 class="mb-0">
            🌍 Country Comparison Engine
        </h3>

    </div>

    <div class="card-body">

        <form method="GET">

            <div class="row">

                <div class="col-md-5">

                    <label class="fw-semibold">

                        Country A

                    </label>

                    <select
                        name="countryA"
                        class="form-select">

                        <option value="">
                            Select Country
                        </option>

                        @foreach($countries as $country)

                            <option
                                value="{{ $country->code }}"
                                {{ request('countryA')==$country->code ? 'selected' : '' }}>

                                {{ $country->name }}

                            </option>

                        @endforeach

                    </select>

                </div>

                <div class="col-md-5">

                    <label class="fw-semibold">

                        Country B

                    </label>

                    <select
                        name="countryB"
                        class="form-select">

                        <option value="">
                            Select Country
                        </option>

                        @foreach($countries as $country)

                            <option
                                value="{{ $country->code }}"
                                {{ request('countryB')==$country->code ? 'selected' : '' }}>

                                {{ $country->name }}

                            </option>

                        @endforeach

                    </select>

                </div>

                <div class="col-md-2 d-flex align-items-end">

                    <button
                        class="btn btn-primary w-100">

                        Compare

                    </button>

                </div>

            </div>

        </form>

        <hr>

        @if($countryA && $countryB)

            <table class="table table-bordered">

                <thead class="table-dark">

                    <tr>

                        <th>Parameter</th>

                        <th>{{ $countryA->name }}</th>

                        <th>{{ $countryB->name }}</th>

                    </tr>

                </thead>

                <tbody>

<tr>
    <td>Capital</td>
    <td>{{ $dataA['capital'] ?? '-' }}</td>
    <td>{{ $dataB['capital'] ?? '-' }}</td>
</tr>

<tr>
    <td>Population</td>
    <td>{{ number_format($dataA['population'] ?? 0) }}</td>
    <td>{{ number_format($dataB['population'] ?? 0) }}</td>
</tr>

<tr>
    <td>Currency</td>
    <td>{{ $dataA['currency'] ?? '-' }}</td>
    <td>{{ $dataB['currency'] ?? '-' }}</td>
</tr>

<tr>
    <td>Language</td>
    <td>{{ $dataA['language'] ?? '-' }}</td>
    <td>{{ $dataB['language'] ?? '-' }}</td>
</tr>

<tr>
    <td>Region</td>
    <td>{{ $dataA['region'] ?? '-' }}</td>
    <td>{{ $dataB['region'] ?? '-' }}</td>
</tr>

<tr>
    <td>GDP</td>
    <td>{{ $dataA['gdp'] ? '$'.number_format($dataA['gdp'],0) : '-' }}</td>
    <td>{{ $dataB['gdp'] ? '$'.number_format($dataB['gdp'],0) : '-' }}</td>
</tr>

<tr>
    <td>Inflation</td>
    <td>{{ $dataA['inflation'] ?? '-' }} %</td>
    <td>{{ $dataB['inflation'] ?? '-' }} %</td>
</tr>

<tr>

<td>Weather</td>

<td>{{ $dataA['weather'] }}</td>

<td>{{ $dataB['weather'] }}</td>

</tr>

<tr>

<td>Port Risk</td>

<td>

@if($dataA['risk']=='HIGH')

<span class="badge bg-danger">

HIGH

</span>

@else

<span class="badge bg-success">

LOW

</span>

@endif

</td>

<td>

@if($dataB['risk']=='HIGH')

<span class="badge bg-danger">

HIGH

</span>

@else

<span class="badge bg-success">

LOW

</span>

@endif

</td>

</tr>

</tbody>

            </table>
            <div class="row mt-4">

    <div class="col-md-6">

        <div class="alert alert-primary text-center">

            <h5>{{ $countryA->name }}</h5>

            <h2>{{ $scoreA }}/100</h2>

        </div>

    </div>

    <div class="col-md-6">

        <div class="alert alert-success text-center">

            <h5>{{ $countryB->name }}</h5>

            <h2>{{ $scoreB }}/100</h2>

        </div>

    </div>

</div>
<div class="card mt-4">

    <div class="card-header">

        🤖 AI Recommendation

    </div>

    <div class="card-body">

        @if($scoreA > $scoreB)

            <strong>{{ $countryA->name }}</strong>
            lebih direkomendasikan sebagai supplier karena memperoleh
            skor logistik yang lebih tinggi.

        @elseif($scoreB > $scoreA)

            <strong>{{ $countryB->name }}</strong>
            lebih direkomendasikan sebagai supplier karena memiliki
            kondisi ekonomi dan logistik yang lebih baik.

        @else

            Kedua negara memiliki tingkat kelayakan yang relatif sama
            sehingga pemilihan supplier dapat mempertimbangkan faktor
            biaya maupun kebutuhan bisnis.

        @endif

    </div>

</div>

        @endif

    </div>

</div>

@include('partials.page-navigation')

@endsection