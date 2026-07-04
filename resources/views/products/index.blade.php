@extends('layouts.app')

@section('content')
<div class="container">

    <h2 class="mb-3">Daftar Product</h2>

    <a href="{{ route('products.create') }}" class="btn btn-primary mb-3">
        Tambah Product
    </a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Product</th>
                <th>Supplier</th>
                <th>Stock</th>
                <th>Risk Score</th>
                <th>Risk Level</th>
                <th>Aksi</th>   
            </tr>
        </thead>

        <tbody>

        @foreach($products as $product)

            <tr>

                <td>{{ $loop->iteration }}</td>

<td>{{ $product->name }}</td>

<td>{{ $product->supplier->name }}</td>

<td>{{ $product->stock }}</td>

<td>{{ $product->risk_score }}</td>

<td>

    @if($product->risk_level == 'High')

        <span class="badge bg-danger">High</span>

    @elseif($product->risk_level == 'Medium')

        <span class="badge bg-warning text-dark">Medium</span>

    @else

        <span class="badge bg-success">Low</span>

    @endif

</td>

                <td>

                    <a href="{{ route('products.edit',$product->id) }}"
                        class="btn btn-warning btn-sm">
                        Edit
                    </a>

                    <form
                        action="{{ route('products.destroy',$product->id) }}"
                        method="POST"
                        style="display:inline">

                        @csrf
                        @method('DELETE')

                        <button
                            class="btn btn-danger btn-sm">
                            Hapus
                        </button>

                    </form>

                </td>

            </tr>

        @endforeach

        </tbody>

    </table>

</div>
@endsection