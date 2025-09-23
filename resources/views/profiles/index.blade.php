@extends('layouts.layout')

@section('content')
    <div class="container py-0">
        <h2 class="mb-4 fw-bold text-dark">My Profile</h2>

        <div class="card mb-2 border-0 rounded-4 profile-card bg-light">
            <div class="card-body d-flex align-items-center p-4">
                <div class="me-4">
                    @if ($user->profile_picture)
                        <img src="{{ asset('storage/' . $user->profile_picture) }}" alt="Profile Picture"
                            class="rounded-circle border border-3 border-white shadow-sm"
                            style="width:120px; height:120px; object-fit:cover;">
                    @else
                        <div class="rounded-circle bg-dark text-white d-flex justify-content-center align-items-center fw-bold shadow-sm"
                            style="width:120px; height:120px; font-size:40px;">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                    @endif
                </div>
                <div>
                    <h4 class="mb-2 fw-bold text-dark">{{ $user->name }}</h4>
                    <p class="mb-1"><strong>Email:</strong> {{ $user->email }}</p>
                    <p class="mb-1"><strong>Phone:</strong> {{ $user->phone ?? 'N/A' }}</p>
                    <p class="mb-1"><strong>DOB:</strong> {{ $user->dob ?? 'N/A' }}</p>
                    <p class="mb-1"><strong>Address:</strong> {{ $user->address ?? 'N/A' }}</p>
                    <p class="mb-0"><strong>Bio:</strong> {{ $user->bio ?? 'No bio added.' }}</p>
                </div>
            </div>
        </div>

        <!-- Tabs -->
        <ul class="nav nav-tabs mb-0 border-0 fw-semibold" id="profileTabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="details-tab" data-bs-toggle="tab" href="#details" role="tab">
                    Personal Details
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="password-tab" data-bs-toggle="tab" href="#password" role="tab">
                    Change Password
                </a>
            </li>
        </ul>

        <div class="tab-content mt-0">
            <!-- Personal Details -->
            <div class="tab-pane fade show active "  id="details" role="tabpanel">
                <form action="{{ route('profiles.update') }}" method="POST" enctype="multipart/form-data"
                    class="card p-4 border-top shadow-md rounded-bottom-4 tab-card">
                    @csrf
                    @method('PATCH')

                    <!-- First Row (Name + Email) -->
                    <div class="row g-3">
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


                    <div class="row g-3 mt-2">
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

                    <!-- Third Row (Address + Profile Picture in one line) -->
                    <div class="row g-3 mt-2">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Address</label>
                            <input type="text" name="address" value="{{ old('address', $user->address) }}"
                                class="form-control form-control-sm">
                            @error('address')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Profile Picture</label>
                            <input type="file" name="profile_picture" class="form-control form-control-sm">
                            @error('profile_picture')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Bio (Single Row Textarea) -->
                    <div class="mt-3">
                        <label class="form-label fw-semibold">Bio</label>
                        <textarea name="bio" class="form-control form-control-sm" rows="3">{{ old('bio', $user->bio) }}</textarea>
                        @error('bio')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-dark btn-sm px-4">Update Profile</button>
                    </div>
                </form>
            </div>

            <!-- Change Password -->
            <div class="tab-pane fade" id="password" role="tabpanel">
                <form action="{{ route('profiles.updatePassword') }}" method="POST"
                    class="card p-3 border-0 shadow-sm rounded-3 tab-card">
                    @csrf
                    @method('PATCH')

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Current Password</label>
                        <input type="password" id="current_password" name="current_password"
                            class="form-control form-control-sm" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">New Password</label>
                        <input type="password" id="new_password" name="password" class="form-control form-control-sm"
                            required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Confirm Password</label>
                        <input type="password" id="confirm_password" name="password_confirmation"
                            class="form-control form-control-sm" required>
                        <div id="confirm_error" class="text-danger small mt-1 d-none">
                            Passwords do not match
                        </div>
                    </div>

                    <div class="mt-2">
                        <button type="submit" id="update_btn" class="btn btn-dark btn-sm">
                            Update Password
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endsection

    @push('styles')
        <style>
            .profile-card {
                margin-top: -5px;
            }

            .tab-card {
                margin-top: -1px;
                border-top-left-radius: 0 !important;
                border-top-right-radius: 0 !important;
                background: #fdfdfd;
            }

            .nav-tabs .nav-link {
                border: none;
                color: #555;
                padding: 10px 18px;
            }

            .nav-tabs .nav-link.active {
                background-color: #fdfdfd;
                border-bottom: 3px solid #212529;
                font-weight: bold;
                color: #212529;
            }
        </style>
    @endpush

    @push('scripts')

        <script>
            const newPass = document.getElementById('new_password');
            const confirm = document.getElementById('confirm_password');
            const errorMsg = document.getElementById('confirm_error');
            const updateBtn = document.getElementById('update_btn');

            function validatePasswords() {
                if (confirm.value.trim() !== "" && confirm.value !== newPass.value) {
                    errorMsg.classList.remove("d-none");
                    updateBtn.disabled = true;
                } else {
                    errorMsg.classList.add("d-none");
                    updateBtn.disabled = false;
                }
            }

            [newPass, confirm].forEach(el => el.addEventListener('input', validatePasswords));

            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: '{{ session('success') }}',
                    confirmButtonColor: '#212529'
                });
            @endif

            @if (session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: '{{ session('error') }}',
                    confirmButtonColor: '#d33'
                });
            @endif
       
        </script>
    @endpush
