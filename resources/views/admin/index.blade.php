@extends('layouts.app')

@section('content')

<div class="container">

    <h2 class="fw-bold mb-1">
        ⚙️ Admin Dashboard
    </h2>

    <p class="text-muted mb-4">
        Global Supply Chain Risk Intelligence Administration Panel
    </p>

    <div class="row g-3">

        <div class="col-md-2">
            <div class="card shadow-sm text-center">
                <div class="card-body">
                    <h3>{{ $totalCountries }}</h3>
                    <small>Countries</small>
                </div>
            </div>
        </div>

        <div class="col-md-2">
            <div class="card shadow-sm text-center">
                <div class="card-body">
                    <h3>{{ $totalSuppliers }}</h3>
                    <small>Suppliers</small>
                </div>
            </div>
        </div>

        <div class="col-md-2">
            <div class="card shadow-sm text-center">
                <div class="card-body">
                    <h3>{{ $totalPorts }}</h3>
                    <small>Ports</small>
                </div>
            </div>
        </div>

        <div class="col-md-2">
            <div class="card shadow-sm text-center">
                <div class="card-body">
                    <h3>{{ $totalMonitoring }}</h3>
                    <small>Monitoring</small>
                </div>
            </div>
        </div>

        <div class="col-md-2">
            <div class="card shadow-sm text-center">
                <div class="card-body">
                    <h3>{{ $totalArticles }}</h3>
                    <small>Articles</small>
                </div>
            </div>
        </div>

        <div class="col-md-2">
            <div class="card shadow-sm text-center">
                <div class="card-body">
                    <h3>{{ $totalUsers }}</h3>
                    <small>Users</small>
                </div>
            </div>
        </div>

    </div>

    <hr class="my-4">

    <h4 class="mb-3">
        Quick Management
    </h4>

    <div class="row g-4">

        <div class="col-md-3">
            <div class="card shadow h-100">
                <div class="card-body">

                    <h5>👤 User Management</h5>

                    <p class="text-muted">
                        Manage administrator and user accounts.
                    </p>

                    <a href="{{ route('admin.users') }}" class="btn btn-primary w-100">
                        Open
                    </a>

                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow h-100">
                <div class="card-body">

                    <h5>🚢 Port Dataset</h5>

                    <p class="text-muted">
                        Add, edit and delete port information.
                    </p>

                    <a href="{{ route('ports.index') }}" class="btn btn-success w-100">
                        Open
                    </a>

                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow h-100">
                <div class="card-body">

                    <h5>📰 Analysis Articles</h5>

                    <p class="text-muted">
                        Manage analysis and intelligence reports.
                    </p>

                    <a href="#" class="btn btn-warning w-100">
                        Open
                    </a>

                </div>
            </div>
        </div>
        
        <div class="col-md-3">
    <div class="card shadow h-100">

        <div class="card-body">

            <h5>🛰️ API Status</h5>

            <p class="text-muted">

                Check external services used by the system.

            </p>

            <ul class="list-unstyled mb-3">

                <li>🟢 Weather API</li>

                <li>🟢 World Bank API</li>

                <li>🟢 Exchange Rate API</li>

                <li>🟢 Google News RSS</li>

            </ul>

        </div>

    </div>
</div>

    <div class="card shadow-sm">

    <div class="card-header fw-bold">

        ℹ️ System Information

    </div>

    <div class="card-body">

        <table class="table mb-0">

            <tr>

                <th width="220">Application</th>

                <td>Global Supply Chain Risk Intelligence</td>

            </tr>

            <tr>

                <th>Laravel Version</th>

                <td>Laravel 9</td>

            </tr>

            <tr>

                <th>PHP Version</th>

                <td>{{ PHP_VERSION }}</td>

            </tr>

            <tr>

                <th>Total Countries</th>

                <td>{{ $totalCountries }}</td>

            </tr>

            <tr>

                <th>Total Ports</th>

                <td>{{ $totalPorts }}</td>

            </tr>

            <tr>

                <th>Total Suppliers</th>

                <td>{{ $totalSuppliers }}</td>

            </tr>

        </table>

    </div>

</div>

@include('partials.page-navigation')

@endsection