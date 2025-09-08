<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light d-flex justify-content-center align-items-center vh-100">

    <div class="card shadow p-4" style="width: 400px;">
        <h3 class="text-center mb-4">Login</h3>

        @if (session('status'))
            <div class="alert alert-success">{{ session('status') }}</div>
        @endif


        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" class="form-control"
                    required autofocus>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input id="password" type="password" name="password" class="form-control" required>
            </div>

            <div class="form-check mb-3">
                <input id="remember_me" type="checkbox" class="form-check-input" name="remember">
                <label for="remember_me" class="form-check-label">Remember me</label>
            </div>
            <div class="d-grid mb-3">
                <button type="submit" class="btn btn-primary rounded-pill py-2 w-100">
                    Login
                </button>
            </div>


            @if (Route::has('password.request'))
                <div class="text-center mb-3">
                    <a href="{{ route('password.request') }}" class="small text-decoration-none">Forgot password?</a>
                </div>
            @endif


            <div class="text-center">
                <p class="small mb-0">Don't have an account?
                    <a href="{{ route('register') }}">Register here</a>
                </p>
            </div>
        </form>
    </div>

</body>

</html>
