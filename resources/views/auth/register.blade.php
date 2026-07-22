<!DOCTYPE html>
<html>
<head>
    <title>Register - ChainPulse AI</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

</head>
<body style="
    background-image: url('{{ asset('images/login-bg.jpg') }}');
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    background-attachment: fixed;
    min-height: 100vh;
">

<div class="container">

    <div class="row justify-content-center mt-5">

        <div class="col-md-5">

            <div class="card shadow">

                <div class="card-body p-4">

                    <h2 class="text-center mb-4">
                        Register
                    </h2>

                    @if($errors->any())

                        <div class="alert alert-danger">

                            {{ $errors->first() }}

                        </div>

                    @endif

                    <form method="POST" action="{{ route('register') }}">

                        @csrf

                        <div class="mb-3">

                            <label>Nama</label>

                            <input
                                type="text"
                                name="name"
                                class="form-control"
                                required>

                        </div>

                        <div class="mb-3">

                            <label>Email</label>

                            <input
                                type="email"
                                name="email"
                                class="form-control"
                                required>

                        </div>

                        <div class="mb-3">

                            <label>Password</label>

                            <input
                                type="password"
                                name="password"
                                class="form-control"
                                required>

                        </div>

                        <div class="mb-3">

                            <label>Konfirmasi Password</label>

                            <input
                                type="password"
                                name="password_confirmation"
                                class="form-control"
                                required>

                        </div>

                        <button
                            class="btn btn-success w-100">

                            Register

                        </button>

                    </form>

                    <div class="text-center mt-3">

                        Sudah punya akun?

                        <a href="{{ route('login') }}">

                            Login

                        </a>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

</body>
</html>