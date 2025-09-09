<x-slot name="logo"></x-slot>

<head>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>

<div class="d-flex justify-content-center align-items-center vh-100 bg-light">

    <div class="card shadow p-4 rounded-4" style="width: 420px;">

        <!-- Title -->
        <h3 class="text-center mb-3 fw-bold text-primary">Reset Password</h3>
        <p class="text-muted small text-center mb-4">
            Enter your new password to reset your account.
        </p>

        <!-- Form -->
   <form method="POST" action="{{ isset($phone) ? route('reset.password.phone.post') : route('password.store') }}">
    @csrf


    @isset($phone)
        <input type="hidden" name="phone" value="{{ $phone }}">
    @endisset

    @isset($email)
        <input type="hidden" name="email" value="{{ $email }}">
    @endisset

    <div class="mb-3">
        <label for="password" class="form-label fw-semibold">New Password</label>
        <input id="password" type="password" class="form-control rounded-3 @error('password') is-invalid @enderror"
               name="password" required autocomplete="new-password" placeholder="Enter new password">
        @error('password')
            <div class="invalid-feedback small">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="password_confirmation" class="form-label fw-semibold">Confirm Password</label>
        <input id="password_confirmation" type="password" class="form-control rounded-3 @error('password_confirmation') is-invalid @enderror"
               name="password_confirmation" required autocomplete="new-password" placeholder="Re-enter new password">
        @error('password_confirmation')
            <div class="invalid-feedback small">{{ $message }}</div>
        @enderror
    </div>

    <div class="d-grid mt-3">
        <button type="submit" class="btn btn-primary rounded-pill py-2 fw-semibold">Reset Password</button>
    </div>
</form>



        <div class="text-center mt-3">
            <a href="{{ route('login') }}" class="text-decoration-none small text-secondary">
                <i class="bi bi-arrow-left-circle me-1"></i> Back to Login
            </a>
        </div>
    </div>
</div>
