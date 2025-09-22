{{-- @extends('layouts.layout')

@section('content')
    <div class="container py-4">
        <h2 class="mb-4 fw-bold">My Profile</h2>

        <!-- Flash Messages -->
        @if (session('status'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('status') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Profile Header -->
        <div class="card mb-6 shadow-sm border-0 rounded-3">
            <div class="card-body d-flex align-items-center p-3">
                <div class="me-3">
                    @if ($user->profile_picture)
                        <img src="{{ asset('storage/' . $user->profile_picture) }}" alt="Profile Picture"
                            class="rounded-circle border" style="width:80px; height:80px; object-fit:cover;">
                    @else
                        <div class="rounded-circle bg-dark text-white d-flex justify-content-center align-items-center"
                            style="width:80px; height:80px; font-size:28px;">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                    @endif
                </div>
                <div>
                    <h5 class="mb-1 fw-bold">{{ $user->name }}</h5>
                    <p class="mb-1 text-dark"><strong>Email:</strong> {{ $user->email }}</p>
                    <p class="mb-1 text-dark"><strong>Phone:</strong> {{ $user->phone ?? 'N/A' }}</p>
                    <p class="mb-1 text-dark"><strong>DOB:</strong> {{ $user->dob ?? 'N/A' }}</p>
                    <p class="mb-1 text-dark"><strong>Address:</strong> {{ $user->address ?? 'N/A' }}</p>
                    <p class="mb-0 text-dark"><strong>Bio:</strong> {{ $user->bio ?? 'No bio added.' }}</p>
                </div>
            </div>
        </div>

        <!-- Tabs -->
        <ul class="nav nav-tabs mb-3 border-bottom-0" id="profileTabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link active text-dark fw-semibold" id="details-tab" data-bs-toggle="tab" href="#details"
                    role="tab">
                    Personal Details
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-dark fw-semibold" id="password-tab" data-bs-toggle="tab" href="#password"
                    role="tab">
                    Change Password
                </a>
            </li>
        </ul>

        <div class="tab-content">
            <!-- Personal Details -->
            <div class="tab-pane fade show active" id="details" role="tabpanel">
                <form action="{{ route('profiles.update') }}" method="POST" enctype="multipart/form-data"
                    class="card p-3 border-0 shadow-sm rounded-3">
                    @csrf
                    @method('PATCH')

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Profile Picture</label>
                        <input type="file" name="profile_picture" class="form-control form-control-sm">
                        @error('profile_picture')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row g-2">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Name</label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}"
                                class="form-control form-control-sm">
                            @error('name')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Email</label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}"
                                class="form-control form-control-sm">
                            @error('email')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row g-2 mt-2">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Phone</label>
                            <input type="text" name="phone" value="{{ old('phone', $user->phone) }}"
                                class="form-control form-control-sm">
                            @error('phone')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Date of Birth</label>
                            <input type="date" name="dob" value="{{ old('dob', $user->dob) }}"
                                class="form-control form-control-sm" max="{{ now()->toDateString() }}">
                            @error('dob')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row g-2 mt-2">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Address</label>
                            <input type="text" name="address" value="{{ old('address', $user->address) }}"
                                class="form-control form-control-sm">
                            @error('address')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Bio</label>
                            <textarea name="bio" class="form-control form-control-sm" rows="2">{{ old('bio', $user->bio) }}</textarea>
                            @error('bio')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-3">
                        <button type="submit" class="btn btn-dark btn-sm">Update Profile</button>
                    </div>
                </form>
            </div>

            <!-- Change Password -->
            <div class="tab-pane fade" id="password" role="tabpanel">
                <form action="{{ route('profiles.update') }}" method="POST"
                    class="card p-3 border-0 shadow-sm rounded-3">
                    @csrf
                    @method('PATCH')

                    <!-- Current Password -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Current Password</label>
                        <input type="password" id="current_password" class="form-control form-control-sm">
                        @error('current_password')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Confirm Password</label>
                        <input type="password" id="confirm_password" class="form-control form-control-sm">
                    </div>

                    <!-- New Password -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold">New Password</label>
                        <input type="password" name="password" id="new_password" class="form-control form-control-sm"
                            disabled>
                        @error('password')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mt-2">
                        <button type="submit" class="btn btn-dark btn-sm">Update Password</button>
                    </div>
                </form>
            </div>




        </div>
    </div>
@endsection

<script>
    const current = document.getElementById('current_password');
    const confirm = document.getElementById('confirm_password');
    const newPass = document.getElementById('new_password');

    function checkPassword() {
        newPass.disabled = !(confirm.value !== "" && confirm.value === current.value);
    }

    current.addEventListener('input', checkPassword);
    confirm.addEventListener('input', checkPassword);
</script> --}}


@extends('layouts.layout')

@section('content')
<div class="container py-4">
    <h2 class="mb-4 fw-bold">My Profile</h2>

    <!-- Flash Messages -->
    @if (session('status'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('status') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Profile Header (Larger) -->
    <div class="card mb-5 shadow-sm border-0 rounded-4">
        <div class="card-body d-flex align-items-center p-4">
            <div class="me-4">
                @if ($user->profile_picture)
                    <img src="{{ asset('storage/' . $user->profile_picture) }}" alt="Profile Picture"
                        class="rounded-circle border border-2" style="width:120px; height:120px; object-fit:cover;">
                @else
                    <div class="rounded-circle bg-dark text-white d-flex justify-content-center align-items-center fw-bold"
                        style="width:120px; height:120px; font-size:40px;">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                @endif
            </div>
            <div>
                <h4 class="mb-2 fw-bold">{{ $user->name }}</h4>
                <p class="mb-1 text-dark"><strong>Email:</strong> {{ $user->email }}</p>
                <p class="mb-1 text-dark"><strong>Phone:</strong> {{ $user->phone ?? 'N/A' }}</p>
                <p class="mb-1 text-dark"><strong>DOB:</strong> {{ $user->dob ?? 'N/A' }}</p>
                <p class="mb-1 text-dark"><strong>Address:</strong> {{ $user->address ?? 'N/A' }}</p>
                <p class="mb-0 text-dark"><strong>Bio:</strong> {{ $user->bio ?? 'No bio added.' }}</p>
            </div>
        </div>
    </div>

    <!-- Tabs -->
    <ul class="nav nav-tabs mb-3 border-bottom-0" id="profileTabs" role="tablist">
        <li class="nav-item">
            <a class="nav-link active text-dark fw-semibold" id="details-tab" data-bs-toggle="tab" href="#details"
                role="tab">
                Personal Details
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link text-dark fw-semibold" id="password-tab" data-bs-toggle="tab" href="#password"
                role="tab">
                Change Password
            </a>
        </li>
    </ul>

    <div class="tab-content">
        <!-- Personal Details -->
        <div class="tab-pane fade show active" id="details" role="tabpanel">
            <form action="{{ route('profiles.update') }}" method="POST" enctype="multipart/form-data"
                class="card p-3 border-0 shadow-sm rounded-3">
                @csrf
                @method('PATCH')

                <div class="mb-3">
                    <label class="form-label fw-semibold">Profile Picture</label>
                    <input type="file" name="profile_picture" class="form-control form-control-sm">
                    @error('profile_picture')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row g-2">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Name</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}"
                            class="form-control form-control-sm">
                        @error('name')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Email</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}"
                            class="form-control form-control-sm">
                        @error('email')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row g-2 mt-2">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Phone</label>
                        <input type="text" name="phone" value="{{ old('phone', $user->phone) }}"
                            class="form-control form-control-sm">
                        @error('phone')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Date of Birth</label>
                        <input type="date" name="dob" value="{{ old('dob', $user->dob) }}"
                            class="form-control form-control-sm" max="{{ now()->toDateString() }}">
                        @error('dob')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row g-2 mt-2">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Address</label>
                        <input type="text" name="address" value="{{ old('address', $user->address) }}"
                            class="form-control form-control-sm">
                        @error('address')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Bio</label>
                        <textarea name="bio" class="form-control form-control-sm" rows="2">{{ old('bio', $user->bio) }}</textarea>
                        @error('bio')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mt-3">
                    <button type="submit" class="btn btn-dark btn-sm">Update Profile</button>
                </div>
            </form>
        </div>

        <!-- Change Password -->
        <div class="tab-pane fade" id="password" role="tabpanel">
            <form action="{{ route('profiles.update') }}" method="POST"
                class="card p-3 border-0 shadow-sm rounded-3">
                @csrf
                @method('PATCH')

                <!-- Current Password -->
                <div class="mb-3">
                    <label class="form-label fw-semibold">Current Password</label>
                    <input type="password" id="current_password" class="form-control form-control-sm">
                    @error('current_password')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div class="mb-3">
                    <label class="form-label fw-semibold">Confirm Password</label>
                    <input type="password" id="confirm_password" class="form-control form-control-sm">
                </div>

                <!-- New Password -->
                <div class="mb-3">
                    <label class="form-label fw-semibold">New Password</label>
                    <input type="password" name="password" id="new_password" class="form-control form-control-sm"
                        disabled>
                    @error('password')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mt-2">
                    <button type="submit" class="btn btn-dark btn-sm">Update Password</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

<script>
const current = document.getElementById('current_password');
const confirm = document.getElementById('confirm_password');
const newPass = document.getElementById('new_password');

function checkPassword() {
    newPass.disabled = !(confirm.value !== "" && confirm.value === current.value);
}

current.addEventListener('input', checkPassword);
confirm.addEventListener('input', checkPassword);
</script>
