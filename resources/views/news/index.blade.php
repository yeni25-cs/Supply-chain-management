@extends('layouts.app')

@section('content')

<div class="card shadow-sm">

    <div class="card-header">

        <h4 class="mb-0">
            📰 News Intelligence
        </h4>

    </div>

    <div class="card-body">

        <form method="GET">

            <label class="form-label">
                Search Country
            </label>

            <select
                name="country"
                class="form-select"
                onchange="this.form.submit()">

                @foreach($countries as $c)

                    <option
                        value="{{ $c->name }}"
                        {{ $country==$c->name ? 'selected' : '' }}>

                        {{ $c->name }}

                    </option>

                @endforeach

            </select>

        </form>

        <hr>

        <div class="row mt-4">

@forelse($articles as $article)

<div class="col-md-6 mb-4">

<div class="card h-100 shadow-sm">

@if($article['image'])

<img
src="{{ $article['image'] }}"
class="card-img-top"
style="height:220px;object-fit:cover;">

@endif

<div class="card-body">

<h5>

{{ $article['title'] }}

</h5>

<p>

{{ $article['description'] }}

</p>

<small class="text-muted">

{{ $article['source']['name'] }}

</small>

<br><br>

<a
href="{{ $article['url'] }}"
target="_blank"
class="btn btn-primary">

Read More

</a>

</div>

</div>

</div>

@empty

<div class="col-12 text-center">

Tidak ada berita ditemukan.

</div>

@endforelse

</div>

    </div>

</div>

@include('partials.page-navigation')

@endsection