<!DOCTYPE html>
<html>
<head>
    <title>Daftar Supplier</title>
</head>
<body>

<div style="margin-bottom:20px;">
    <a href="{{ route('dashboard') }}"
       style="
            text-decoration:none;
            font-size:30px;
            color:black;
            font-weight:bold;
       ">
        ←
    </a>
</div>

<h1>Daftar Supplier</h1>

<a href="{{ route('suppliers.create') }}">Tambah Supplier</a>

@if(session('success'))
    <p style="color:green">{{ session('success') }}</p>
@endif

<table border="1" cellpadding="10">
    <tr>
        <th>No</th>
        <th>Nama</th>
        <th>Negara</th>
        <th>Kontak</th>
        <th>Aksi</th>
    </tr>

    @foreach($suppliers as $supplier)
    <tr>
        <td>{{ $loop->iteration }}</td>
        <td>{{ $supplier->name }}</td>
        <td>{{ $supplier->country?->name ?? 'Belum dipilih' }}</td>
        <td>{{ $supplier->contact }}</td>

        <td>

            <a href="{{ route('suppliers.edit', $supplier->id) }}">
                Edit
            </a>

            |

            <form action="{{ route('suppliers.destroy', $supplier->id) }}"
                  method="POST"
                  style="display:inline;">

                @csrf
                @method('DELETE')

                <button
                    type="submit"
                    onclick="return confirm('Yakin ingin menghapus supplier ini?')">

                    Hapus

                </button>

            </form>

        </td>

    </tr>
    @endforeach

</table>

</body>
</html>