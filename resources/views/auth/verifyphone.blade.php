<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Phone Verification</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex justify-content-center align-items-center vh-100">

<div class="card shadow p-4 rounded-4" style="width: 400px;">
    <h3 class="text-center mb-4 fw-bold text-primary">Verify Phone</h3>

    <!-- Success Message -->
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <!-- Error Messages -->
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

  <form method="POST" action="{{ route('verify.phone.verify') }}">
    @csrf
    <div class="mb-3">
        <label for="phone" class="form-label">Phone Number</label>
        <input id="phone" type="tel" name="phone" value="{{ $phone }}" class="form-control rounded-3" readonly>
    </div>

    <div class="mb-3">
        <label for="otp" class="form-label">Enter OTP</label>
        <input id="otp" type="text" name="otp" class="form-control rounded-3" placeholder="6-digit OTP" required>
    </div>

    <div class="d-grid mt-3">
        <button type="submit" class="btn btn-success rounded-pill py-2 fw-semibold">
            Verify OTP
        </button>
    </div>

    <div class="text-center mt-3">
        <a href="{{ route('resend.otp') }}" class="small text-decoration-none text-secondary">
            Resend OTP
        </a>
    </div>
</form>

</div>

</body>
</html>
