@extends('layouts.layout')

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

<style>
    .dataTables_filter input {
        height: 28px;
        width: 200px;
        font-size: 1rem;
        padding: 4px 8px;
    }

    .dataTables_paginate .paginate_button {
        padding: 2px 6px !important;
        font-size: 0.75rem !important;
    }

    .action-icons {
        display: flex;
        justify-content: center;
        align-items: center;

    }

    .action-icons a,
    .action-icons button {
        color: #000 !important;
        font-size: 0.85rem;
        margin: 0;
        padding: 0;
        background: none !important;
        border: none !important;
        box-shadow: none !important;
    }

    .action-icons a:hover,
    .action-icons button:hover {
        color: #000 !important;
    }



.action-icons a,
.action-icons button {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 22px;
    height: 28px;
    font-size: 0.85rem;
    background: none !important;
    border: none !important;
    box-shadow: none !important;
}


.card-body .action-icons i {
    font-size: 0.7rem;
}

</style>
@endpush

@section('content')

{{-- Roles Section --}}
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

    <h5 class="fw-bold mb-2">Users List</h5>

    <table class="table-sm align-middle" id="users-table" style="width:100%">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Roles</th>
                <th class="text-center">Actions</th>
            </tr>
        </thead>
    </table>
</div>

@include('users.create', ['roles' => $roles])
@include('roles.createrole')


<div class="modal fade" id="editUserModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-s">
        <div class="modal-content border-0 shadow">
            <div class="modal-header">
                <h6 class="modal-title fw-bold">Edit User</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form id="editUserForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-2">
                        <label class="form-label">Name</label>
                        <input type="text" id="editUserName" name="name" class="form-control form-control-sm" required>
                    </div>

                    <div class="mb-2">
                        <label class="form-label">Email</label>
                        <input type="email" id="editUserEmail" name="email" class="form-control form-control-sm" required>
                    </div>

                    <div class="mb-2" id="editUserRoles">

                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-sm">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(function () {

    var table = $('#users-table').DataTable({
        processing: true,
        serverSide: true,
        pageLength: 5,
        lengthMenu: [5, 10, 25, 50],
        ajax: "{{ route('users.index') }}",
        columns: [
            { data: 'name', name: 'name' },
            { data: 'email', name: 'email' },
            { data: 'roles', name: 'roles', orderable: false, searchable: false },
            { data: 'actions', name: 'actions', orderable: false, searchable: false, className: 'text-center action-icons' }
        ]
    });

    // SweetAlert delete
    $(document).on('submit', '.delete-role-form, .delete-user-form', function(e) {
        e.preventDefault();
        let form = this;
        Swal.fire({
            title: 'Are you sure?',
            text: "This action cannot be undone!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });

    // Dynamic Edit User Modal
    $(document).on('click', '.editUserBtn', function() {
        let userId = $(this).data('id');
        let name = $(this).data('name');
        let email = $(this).data('email');
        let roles = $(this).data('roles') ? $(this).data('roles').split(',') : [];

        $('#editUserName').val(name);
        $('#editUserEmail').val(email);

        let rolesHtml = '<label class="form-label">Assign Roles</label>';
        @foreach ($roles as $role)
            @if ($role->name !== 'Admin')
                rolesHtml += `
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="roles[]" value="{{ $role->name }}" ${roles.includes('{{ $role->name }}') ? 'checked' : ''}>
                    <label class="form-check-label">{{ $role->name }}</label>
                </div>`;
            @endif
        @endforeach
        $('#editUserRoles').html(rolesHtml);

        $('#editUserForm').attr('action', '/users/' + userId);

        var editModal = new bootstrap.Modal(document.getElementById('editUserModal'));
        editModal.show();
    });
});
</script>
@endpush
