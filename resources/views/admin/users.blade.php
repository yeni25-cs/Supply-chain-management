@extends('layouts.app')

@section('content')

<div class="container">

    <h2 class="fw-bold mb-1">

        👤 User Management

    </h2>

    <p class="text-muted">

        Manage administrator and application users.

    </p>

    <div class="card shadow-sm">

        <div class="card-body">

            <table class="table table-hover align-middle">

                <thead class="table-dark">

                    <tr>

                        <th>ID</th>

                        <th>Name</th>

                        <th>Email</th>

                        <th>Created</th>

                        <th>Status</th>

                    </tr>

                </thead>

                <tbody>

                    @foreach($users as $user)

                    <tr>

                        <td>

                            {{ $user->id }}

                        </td>

                        <td>

                            {{ $user->name }}

                        </td>

                        <td>

                            {{ $user->email }}

                        </td>

                        <td>

                            {{ $user->created_at->format('d M Y') }}

                        </td>

                        <td>

                            <span class="badge bg-success">

                                Active

                            </span>

                        </td>

                    </tr>

                    @endforeach

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection