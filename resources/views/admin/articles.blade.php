@extends('layouts.app')

@section('content')

<div class="container">

    <h2 class="fw-bold mb-1">
        📰 Analysis Articles
    </h2>

    <p class="text-muted mb-4">
        AI-Based Supply Chain News Intelligence
    </p>

    <form method="GET" class="row mb-4">

        <div class="col-md-4">

            <select
                name="country"
                class="form-select"
                onchange="this.form.submit()">

                @foreach($countries as $c)

                    <option
                        value="{{ $c->name }}"
                        {{ $country==$c->name?'selected':'' }}>

                        {{ $c->name }}

                    </option>

                @endforeach

            </select>

        </div>

    </form>

    {{-- Sentiment Summary --}}

    <div class="row mb-4">

        <div class="col-md-4">

            <div class="card shadow-sm border-success">

                <div class="card-body text-center">

                    <h2 class="text-success">

                        {{ $positivePercent }}%

                    </h2>

                    <h5>Positive</h5>

                </div>

            </div>

        </div>

        <div class="col-md-4">

            <div class="card shadow-sm border-secondary">

                <div class="card-body text-center">

                    <h2 class="text-secondary">

                        {{ $neutralPercent }}%

                    </h2>

                    <h5>Neutral</h5>

                </div>

            </div>

        </div>

        <div class="col-md-4">

            <div class="card shadow-sm border-danger">

                <div class="card-body text-center">

                    <h2 class="text-danger">

                        {{ $negativePercent }}%

                    </h2>

                    <h5>Negative</h5>

                </div>

            </div>

        </div>

    </div>

    {{-- Risk Prediction --}}

    <div class="card shadow-sm mb-4">

        <div class="card-header fw-bold">

            Supply Chain Risk Prediction

        </div>

        <div class="card-body">

            <table class="table">

                <tr>

                    <td>Weather Risk</td>

                    <td>{{ $weatherRisk }}%</td>

                </tr>

                <tr>

                    <td>Inflation Risk</td>

                    <td>{{ $inflationRisk }}%</td>

                </tr>

                <tr>

                    <td>Political News Risk</td>

                    <td>{{ $politicalRisk }}%</td>

                </tr>

                <tr>

                    <td>Currency Risk</td>

                    <td>{{ $currencyRisk }}%</td>

                </tr>

                <tr class="table-primary">

                    <th>Total Risk</th>

                    <th>

                        {{ $totalRisk }}%

                    </th>

                </tr>

            </table>

            <div class="mt-4">

    <h5 class="mb-2">

        Overall Risk Score

    </h5>

    <div class="progress"
         style="height:28px;">

        <div

            class="progress-bar

            @if($riskStatus=='HIGH')

                bg-danger

            @elseif($riskStatus=='MEDIUM')

                bg-warning text-dark

            @else

                bg-success

            @endif"

            role="progressbar"

            style="width: {{ $totalRisk }}%;"

            aria-valuenow="{{ $totalRisk }}"

            aria-valuemin="0"

            aria-valuemax="100">

            {{ $totalRisk }}%

        </div>

    </div>

</div>

            @if($riskStatus=="HIGH")

                <div class="alert alert-danger">

                    HIGH RISK

                </div>

            @elseif($riskStatus=="MEDIUM")

                <div class="alert alert-warning">

                    MEDIUM RISK

                </div>

            @else

                <div class="alert alert-success">

                    LOW RISK

                </div>

            @endif

        </div>

    </div>

    <div class="card shadow-sm mt-4">

    <div class="card-header fw-bold">

        🤖 AI Recommendation

    </div>

    <div class="card-body">

        <ul>

            @foreach($recommendation as $item)

                <li>{{ $item }}</li>

            @endforeach

        </ul>

    </div>

</div>

    {{-- Article List --}}

    <div class="card shadow-sm">

        <div class="card-header fw-bold">

            News Analysis

        </div>

        <div class="card-body">

            @foreach($articles as $article)

                <div class="border rounded p-3 mb-3">

                    <h5>

                        {{ $article['title'] }}

                    </h5>

                    <p>

                        {{ $article['description'] }}

                    </p>

                    <small>

                        {{ $article['publishedAt'] }}

                    </small>

                    <br><br>

                    @if($article['sentiment']=="Positive")

                        <span class="badge bg-success">

                            Positive

                        </span>

                    @elseif($article['sentiment']=="Negative")

                        <span class="badge bg-danger">

                            Negative

                        </span>

                    @else

                        <span class="badge bg-secondary">

                            Neutral

                        </span>

                    @endif

                </div>

            @endforeach

        </div>

    </div>

</div>

@endsection