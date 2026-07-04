<!DOCTYPE html>
<html>
<head>
    <title>Daftar Supplier</title>
</head>
<body>

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
    </tr>

    @foreach($suppliers as $supplier)
    <tr>
        <td>{{ $loop->iteration }}</td>
        <td>{{ $supplier->name }}</td>
        <td>{{ $supplier->country?->name ?? 'Belum dipilih' }}</td>
        <td>{{ $supplier->contact }}</td>
    </tr>
    @endforeach

</table>

</body>
</html>