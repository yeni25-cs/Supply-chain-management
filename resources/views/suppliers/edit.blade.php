<!DOCTYPE html>
<html>
<head>
    <title>Edit Supplier</title>
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

<h1>Edit Supplier</h1>

<form action="{{ route('suppliers.update', $supplier->id) }}" method="POST">

    @csrf
    @method('PUT')

    <p>
        Nama Supplier
        <br>
        <input
            type="text"
            name="name"
            value="{{ old('name', $supplier->name) }}"
            required>
    </p>

    <p>
        Negara
        <br>

        <select name="country_id" required>

            <option value="">
                -- Pilih Negara --
            </option>

            @foreach($countries as $country)

                <option
                    value="{{ $country->id }}"
                    {{ $supplier->country_id == $country->id ? 'selected' : '' }}>

                    {{ $country->name }}

                </option>

            @endforeach

        </select>

    </p>

    <p>
        Kontak
        <br>

        <input
            type="text"
            name="contact"
            value="{{ old('contact', $supplier->contact) }}"
            required>

    </p>

    <button type="submit">
        Update
    </button>

</form>

<br>

<a href="{{ route('suppliers.index') }}">
    Kembali
</a>

</body>
</html>