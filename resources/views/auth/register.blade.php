{{-- <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex justify-content-center align-items-center vh-100">

    <div class="card shadow p-4 rounded-4" style="width: 450px;">
        <h3 class="text-center mb-4 fw-bold text-primary">Register</h3>


        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input id="name" type="text" name="name" value="{{ old('name') }}"
                       class="form-control rounded-3" required autofocus>
            </div>
            <div class="mb-3">
                <label for="phone" class="form-label">Phone</label>
                <input type="tel" id="phone" name="phone" value="{{ old('phone') }}"
                       class="form-control rounded-3" required>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}"
                       class="form-control rounded-3" required>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input id="password" type="password" name="password"
                       class="form-control rounded-3" required>
            </div>

            <div class="mb-3">
                <label for="password_confirmation" class="form-label">Confirm Password</label>
                <input id="password_confirmation" type="password" name="password_confirmation"
                       class="form-control rounded-3" required>
            </div>


            <div class="d-grid mt-3">
                <button type="submit" class="btn btn-success rounded-pill py-2 fw-semibold">
                    Register
                </button>
            </div>

            <div class="text-center mt-3">
                <a href="{{ route('login') }}" class="small text-decoration-none text-secondary">
                    Already registered? Login
                </a>
            </div>
        </form>
    </div>

</body>
</html> --}}


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa; /* light grey background */
        }
        .register-card {
            height: auto;
            width: 500px;
            max-width: 90%;
            border-radius: 1rem;
            padding: 2rem;
            box-shadow: 0 0.5rem 1.5rem rgba(0,0,0,0.15);
        }
        .register-card h3 {
            color: #0d6efd;
        }
    </style>
</head>
<body class="d-flex justify-content-center align-items-center vh-80">

    <div class="card register-card">
        <h3 class="text-center mb-4 fw-bold">Register</h3>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input id="name" type="text" name="name" value="{{ old('name') }}"
                       class="form-control rounded-3" required autofocus>
            </div>

            <div class="mb-3">
                <label for="phone" class="form-label">Phone</label>
                <input type="tel" id="phone" name="phone" value="{{ old('phone') }}"
                       class="form-control rounded-3" required>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}"
                       class="form-control rounded-3" required>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input id="password" type="password" name="password"
                       class="form-control rounded-3" required>
            </div>

            <div class="mb-3">
                <label for="password_confirmation" class="form-label">Confirm Password</label>
                <input id="password_confirmation" type="password" name="password_confirmation"
                       class="form-control rounded-3" required>
            </div>

            <div class="d-grid mt-3">
                <button type="submit" class="btn btn-success rounded-pill py-2 fw-semibold">
                    Register
                </button>
            </div>

            <div class="text-center mt-3">
                <a href="{{ route('login') }}" class="small text-decoration-none text-secondary">
                    Already registered? Login
                </a>
            </div>
        </form>
    </div>

</body>
</html>
