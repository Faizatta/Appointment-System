@extends('layouts.layout')

@section('content')
    <div class="container mt-1" style="max-width: 1000px;">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="mb-0 fw-bold">Roles List</h4>

            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createRoleModal">
                <i class="fas fa-plus"></i> Create Role
            </button>
        </div>

        @if ($roles->count())
            <div class="row g-0">
                @foreach ($roles as $role)
                    <div class="col-md-3 col-sm-6 mb-3">
                        <div class="card border-0 shadow-sm" style="min-height: 70px; width: 200px;">
                            <div class="card-body p-2">

                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <h6 class="fw-bold mb-0">{{ $role->name }}</h6>
                                    <div class="d-flex gap-1">
                                        @if (strtolower($role->name) !== 'admin')
                                            <a href="#" data-bs-toggle="modal"
                                                data-bs-target="#editRoleModal{{ $role->id }}" class="text-dark"
                                                style="width: 20px; height: 20px; font-size: 0.7rem; display:flex; align-items:center; justify-content:center;">
                                                <i class="fas fa-edit"></i>
                                            </a>

                                            <form action="{{ route('roles.destroyrole', $role->id) }}" method="POST"
                                                class="d-inline delete-role-form">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-dark bg-transparent p-0"
                                                    style="width: 20px; height: 20px; font-size: 0.7rem; display:flex; align-items:center; justify-content:center; border:none;">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>

                                <p class="text-muted mb-0" style="font-size: 0.7rem;">
                                    <strong>Permissions:</strong> {{ $role->permissions->count() }}
                                    &nbsp;&nbsp;
                                    <strong>Users:</strong> {{ $role->users->count() }}
                                </p>

                            </div>
                        </div>
                    </div>

                    @include('roles.editrole', ['role' => $role, 'permissions' => $permissions])
                @endforeach
            </div>
        @else
            <div class="alert alert-info">
                No roles found. Click "Create Role" to add a new role.
            </div>
        @endif
    </div>


    <div class="container mt-4" style="max-width: 1000px;">
        <div class="d-flex justify-content-end mb-3">
            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createUserModal">
                <i class="fas fa-plus"></i> Add User
            </button>
        </div>

        <div class="container mt-2" style="max-width: 1000px;">
            <h5 class="fw-bold mb-2">Users List</h5>

            @if ($users->count())
                <table class="table table-borderless align-middle">
                    <thead>
                        <tr>
                            <th style="font-size: 0.9rem;">Name</th>
                            <th style="font-size: 0.9rem;">Email</th>
                            <th style="font-size: 0.9rem;">Role(s)</th>
                            <th style="font-size: 0.9rem;" class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            <tr>
                                <td style="font-size: 0.85rem;">{{ $user->name }}</td>
                                <td style="font-size: 0.85rem;">{{ $user->email }}</td>
                                <td style="font-size: 0.85rem;">
                                    {{ $user->roles->pluck('name')->join(', ') }}
                                </td>
                                <td>
                                    <div class="d-flex justify-content-center align-items-center gap-0.5">
                                        @if (!$user->hasRole('admin'))
                                            <a href="#" data-bs-toggle="modal"
                                                data-bs-target="#editUserModal{{ $user->id }}"
                                                class="text-dark d-flex align-items-center justify-content-center"
                                                style="width: 28px; height: 28px; border-radius: 4px;">
                                                <i class="fas fa-edit" style="font-size: 0.85rem;"></i>
                                            </a>

                                            <form action="{{ route('users.destroy', $user->id) }}" method="POST"
                                                class="d-inline delete-user-form">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="bg-transparent text-dark p-0 d-flex align-items-center justify-content-center"
                                                    style="width: 28px; height: 28px; border: none; border-radius: 4px;">
                                                    <i class="fas fa-trash-alt" style="font-size: 0.85rem;"></i>
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-muted" style="font-size: 0.75rem;">—</span>
                                        @endif
                                    </div>


                                </td>
                            </tr>

                            @include('users.edit', ['user' => $user, 'roles' => $roles])
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="alert alert-info">
                    No users found. Click "Add User" to create one.
                </div>
            @endif
        </div>
    </div>

    @include('users.create', ['roles' => $roles])
    @include('roles.createrole')
    @include('users.edit', ['roles' => $roles])

@endsection

@push('scripts')
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const deleteForms = document.querySelectorAll('.delete-role-form, .delete-user-form');
            deleteForms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();

                    Swal.fire({
                        title: 'Are you sure?',
                        text: "You won't be able to revert this!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, delete it!',
                        cancelButtonText: 'Cancel'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });
        });
    </script>
@endpush
