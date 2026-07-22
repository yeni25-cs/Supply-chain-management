<!DOCTYPE html>
<html>
<head>
    <title>Tambah Supplier</title>
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

<h1>Tambah Supplier</h1>

<form action="{{ route('suppliers.store') }}" method="POST">

    @csrf

    <p>
        Nama Supplier
        <br>
        <input type="text" name="name" required>
    </p>

    <p>
        Negara
        <br>

        <select name="country_id" required>

            <option value="">
                -- Pilih Negara --
            </option>

            @foreach($countries as $country)

                <option value="{{ $country->id }}">

                    {{ $country->name }}

                </option>

            @endforeach

        </select>

    </p>

    <p>
        Kontak
        <br>
        <input type="text" name="contact" required>
    </p>

    <button type="submit">
        Simpan
    </button>

</form>

<br>

<a href="{{ route('suppliers.index') }}">
    Kembali
</a>

</body>
</html>