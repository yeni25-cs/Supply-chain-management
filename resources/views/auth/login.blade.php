<!DOCTYPE html>
<html>
<head>
    <title>Login - ChainPulse AI</title>

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

    <div
class="row justify-content-center align-items-center"
style="height:100vh;">

        <div class="col-md-5">

            <div
                class="card shadow-lg"
                style="
                background:rgba(255,255,255,.92);
                border:none;
                border-radius:18px;
                ">

                <div class="card-body p-4">

                    <h2 class="text-center mb-4">
                        ChainPulse AI
                    </h2>

                    @if(session('success'))

                        <div class="alert alert-success">

                            {{ session('success') }}

                        </div>

                    @endif

                    @if($errors->any())

                        <div class="alert alert-danger">

                            {{ $errors->first() }}

                        </div>

                    @endif

                    <form method="POST" action="{{ route('login') }}">

                        @csrf

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

                        <button
                            class="btn btn-primary w-100 py-2">

                            Login

                        </button>

                    </form>

                    <div class="text-center mt-3">

                        Belum punya akun?

                        <a href="{{ route('register') }}">
                            Register
                        </a>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

</body>
</html>