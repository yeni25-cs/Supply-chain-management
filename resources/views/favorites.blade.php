@extends('layouts.app')

@section('content')

<div class="container">

    <div class="mb-3">
        <a href="{{ route('dashboard') }}"
           style="
                text-decoration:none;
                font-size:28px;
                color:#000;
                font-weight:bold;
           ">
            ←
        </a>
    </div>

    <h2 class="fw-bold mb-3">
        Favorite Monitoring
    </h2>

    <p class="text-muted mb-4">
        List of monitored countries.
    </p>

    <div class="card shadow-sm">

        <div class="card-header fw-bold">
            Monitoring List
        </div>

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-hover align-middle">

                    <thead class="table-dark">

                        <tr>

                            <th>No</th>
                            <th>Country</th>
                            <th>Capital</th>
                            <th>Region</th>
                            <th width="150">Action</th>

                        </tr>

                    </thead>

                    <tbody>

                    @forelse($favorites as $index => $favorite)

                        <tr>

                            <td>{{ $index + 1 }}</td>

                            <td>{{ $favorite->country->name }}</td>

                            <td>{{ $favorite->country->capital }}</td>

                            <td>{{ $favorite->country->region }}</td>

                            <td>

                                <form
                                    action="{{ route('favorite.remove') }}"
                                    method="POST">

                                    @csrf

                                    <input
                                    type="hidden"
                                    name="country"
                                    value="{{ $favorite->country_code }}">

                                    <button
                                        class="btn btn-danger btn-sm">

                                        Remove

                                    </button>

                                </form>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="5" class="text-center text-muted">

                                No monitored country.

                            </td>

                        </tr>

                    @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>

@endsection