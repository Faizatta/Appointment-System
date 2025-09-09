<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>

<div class="d-flex justify-content-center align-items-center vh-100 bg-light">
    <div class="card shadow p-4 rounded-4" style="width: 420px;">
        <h3 class="text-center mb-3 fw-bold text-primary">Forgot Password</h3>
        <p class="text-muted small text-center mb-4">
            Enter your email or phone number and we'll send you a reset link or OTP.
        </p>

        @if (session('status'))
            <div class="alert alert-success text-center py-2 small rounded-3">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('verify.phone.post') }}">
            @csrf

            <div class="mb-3">
                <label for="identifier" class="form-label fw-semibold">Email or Phone</label>
                <input id="identifier" type="text" class="form-control rounded-3 @error('identifier') is-invalid @enderror"
                    name="identifier" value="{{ old('identifier') }}" required autofocus
                    placeholder="Enter your email or phone">
                @error('identifier')
                    <div class="invalid-feedback small">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-grid mt-3">
                <button type="submit" class="btn btn-primary rounded-pill py-2 fw-semibold">
                    Send Reset Link / OTP
                </button>
            </div>
        </form>

        <div class="text-center mt-3">
            <a href="{{ route('login') }}" class="text-decoration-none small text-secondary">
                <i class="bi bi-arrow-left-circle me-1"></i> Back to Login
            </a>
        </div>
    </div>
</div>
