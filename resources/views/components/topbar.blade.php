<nav class="navbar navbar-light border-bottom px-3" style="height: 60px;">
    <div class="container-fluid d-flex justify-content-between align-items-center">

        <span class="fw-bold fs-5 text-dark">Doctor Management</span>

        <div class="dropdown">
            <button class="d-flex align-items-center" type="button" id="adminDropdown" data-bs-toggle="dropdown"
                aria-expanded="false">

                @php
                    $user = Auth::user();
                    $initials = strtoupper(substr($user->name, 0, 1));
                @endphp


                <span class="fw-semibold text-dark me-2">{{ $user->name }}</span>


                <div class="rounded-circle bg-secondary text-white d-flex justify-content-center align-items-center"
                    style="width: 32px; height: 32px; font-size: 14px;">
                    {{ $initials }}
                </div>
            </button>

            <ul class="dropdown-menu dropdown-menu-end text-center" aria-labelledby="adminDropdown"
                style="min-width: 50px; padding: 0.2rem 0; font-size: 0.85rem;">

                <li>
                    <a class="dropdown-item py-1" href="{{ route('profiles.index') }}">
                        Profile
                    </a>
                </li>

                <li><a class="dropdown-item py-1" href="#">Settings</a></li>
                <li>
                    <hr class="dropdown-divider my-1">
                </li>
                <li>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="dropdown-item text-danger fw-semibold py-1">Logout</button>
                    </form>
                </li>
            </ul>


        </div>
    </div>
</nav>


<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"></script>
