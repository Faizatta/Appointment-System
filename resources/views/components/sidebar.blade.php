
<div class="d-flex flex-column bg-light min-vh-100" style="width: 180px; border-right: 1px solid #dee2e6;">
    <div class="fw-bold fs-5 px-3 py-3">POLYCLINIC</div>

    <ul class="nav flex-column mt-1">
        <li class="nav-item">
            <a class="nav-link {{ request()->is('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                <i class="fas fa-home me-2"></i> Dashboard
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->is('doctors*') ? 'active' : '' }}" href="{{ route('doctors.index') }}">
                <i class="fas fa-user me-2"></i> Doctors
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->is('patients*') ? 'active' : '' }}" href="{{ route('patients.index') }}">
                <i class="fas fa-users me-2"></i> Patients
            </a>
        </li>
    </ul>
</div>

<style>
    .nav-link {
        display: flex;
        align-items: center;
        padding: 0.5rem 1rem;
        font-size: 14px;
        color: #212529;
        transition: 0.2s;
        text-decoration: none;
    }

    .nav-link i {
        width: 20px;
    }

    .nav-link:hover {
        background-color: #e9ecef;
        color: #151515;
    }

    .nav-link.active {
        background-color: #d3d5d8;
        color: rgb(2, 2, 2);
        font-weight: 600;
    }

    .nav-link.active i {
        color: rgb(16, 16, 16);
    }
</style>
