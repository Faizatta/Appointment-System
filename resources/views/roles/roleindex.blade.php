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

    /* Bulk Delete Button */
    #bulkDeleteBtn {
        opacity: 0.6;
        cursor: not-allowed;
        display: none;
    }

    #bulkDeleteBtn:not(:disabled) {
        opacity: 1;
        cursor: pointer;
    }

    /* Users table actions column */
    #users-table th.actions,
    #users-table td.actions-cell {
        text-align: center;
        vertical-align: middle;
    }

    /* Admin row styling */
    .admin-row {
        background-color: #f8f9fa !important;
    }

    .admin-badge {
        background-color: #dc3545;
        color: white;
        padding: 2px 6px;
        border-radius: 3px;
        font-size: 0.7rem;
        font-weight: bold;
    }

    .permission-role label {
        text-decoration: line-through;
        color: #000 !important;
    }

    .form-check-input {
        box-shadow: none !important;
        outline: none !important;
    }
</style>
@endpush

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
                                              class="d-inline delete-role-form" data-role-name="{{ $role->name }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="text-dark bg-transparent p-0 delete-role-btn"
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
                <th width="5%"><input type="checkbox" id="checkAll"></th>
                <th>Name</th>
                <th>Email</th>
                <th>Roles</th>
                <th class="text-center actions">Actions</th>
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

                    <div class="mb-2">
                        <label class="form-label">Assign Roles</label>
                        <div id="editUserRoles" class="d-flex flex-wrap gap-2"></div>
                    </div>

                    <div class="mb-2">
                        <label class="form-label">Specific Permissions</label>
                        <div id="editUserPermissions" class="row"></div>
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(function () {
    // Show success messages from Laravel session
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: '{{ session('success') }}',
            timer: 3000,
            showConfirmButton: false
        });
    @endif

    @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: '{{ session('error') }}',
            timer: 3000,
            showConfirmButton: false
        });
    @endif

    var table = $('#users-table').DataTable({
        processing: true,
        serverSide: true,
        pageLength: 5,
        lengthMenu: [5, 10, 25, 50],
        ajax: "{{ route('users.index') }}",
        dom: '<"d-flex align-items-center justify-content-between mb-2"<"d-flex align-items-center gap-2"l<"datatable-buttons">><"d-flex"f>>rtip',
        columns: [
            {
                data: 'id',
                render: function(data, type, row) {
                    let isAdmin = false;
                    if (row.is_admin === true || row.is_admin == 1 || row.roles?.toLowerCase().includes('admin')) {
                        isAdmin = true;
                    }
                    return isAdmin ? '<span class="text-muted">—</span>' : `<input type="checkbox" class="row-check" value="${data}">`;
                },
                orderable: false,
                searchable: false,
                className: 'text-center'
            },
            { data: 'name', name: 'name' },
            { data: 'email', name: 'email' },
            { data: 'roles', name: 'roles', orderable: false, searchable: false },
            {
                data: 'actions',
                name: 'actions',
                orderable: false,
                searchable: false,
                className: 'text-center action-icons actions-cell'
            }
        ],
        rowCallback: function(row, data) {
            let isAdmin = false;
            if (data.is_admin === true || data.is_admin == 1 || (data.roles && data.roles.toLowerCase().includes('admin'))) {
                isAdmin = true;
            }
            if (isAdmin) $(row).addClass('admin-row');
        }
    });

    window.allRoles = @json($roles->map(fn($r) => [
        'name' => $r->name,
        'permissions' => $r->permissions->pluck('name')->toArray()
    ])->values());

    window.allPermissions = @json($permissions->map(fn($p) => ['name' => $p->name])->values());

    // === Bulk select ===
    $(document).on('change', '#checkAll', function() {
        $('.row-check').prop('checked', $(this).prop('checked'));
        toggleBulkButton();
    });

    $(document).on('change', '.row-check', function() {
        toggleBulkButton();
        let total = $('.row-check').length;
        let checked = $('.row-check:checked').length;
        $('#checkAll').prop('checked', checked === total).prop('indeterminate', checked > 0 && checked < total);
    });

    function toggleBulkButton() {
        let count = $('.row-check:checked').length;
        $('#bulkDeleteBtn').toggleClass('d-none', count === 0);
    }

    // === Bulk delete ===
    $(document).on('click', '#bulkDeleteBtn', function() {
        let ids = $('.row-check:checked').map(function() { return $(this).val(); }).get();
        if (!ids.length) return;
        Swal.fire({
            title: 'Are you sure?',
            text: `This will permanently delete ${ids.length} user${ids.length > 1 ? 's' : ''}`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete',
        }).then((result) => {
            if (result.isConfirmed) {
                $.post("{{ route('users.bulkDelete') }}", { ids: ids, _token: "{{ csrf_token() }}" })
                    .done(res => {
                        table.ajax.reload();
                        $('#checkAll').prop('checked', false).prop('indeterminate', false);
                        Swal.fire('Deleted!', res.success || 'Users deleted.', 'success');
                    })
                    .fail(xhr => Swal.fire('Error!', xhr.responseJSON?.message || 'Something went wrong.', 'error'));
            }
        });
    });

    // === Delete Role with Confirmation ===
    $(document).on('click', '.delete-role-btn', function(e) {
        e.preventDefault();
        let form = $(this).closest('.delete-role-form');
        let roleName = form.data('role-name');

        Swal.fire({
            title: 'Delete Role?',
            text: `Are you sure you want to delete the role "${roleName}"? This action cannot be undone.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });

    // === Delete User with Confirmation ===
    $(document).on('click', '.deleteUserBtn', function(e) {
        e.preventDefault();
        let form = $(this).closest('form');
        let userName = $(this).data('name') || 'this user';

        Swal.fire({
            title: 'Delete User?',
            text: `Are you sure you want to delete ${userName}? This action cannot be undone.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });

    // === Edit User Modal ===
    $(document).on('click', '.editUserBtn', function () {
        let userId = $(this).data('id');
        let name = $(this).data('name');
        let email = $(this).data('email');
        let roles = $(this).data('roles') ? $(this).data('roles').split(',') : [];
        let permissions = $(this).data('permissions') ? $(this).data('permissions').split(',') : [];

        $('#editUserName').val(name);
        $('#editUserEmail').val(email);
        $('#editUserForm').attr('action', '/users/' + userId);

        // Roles
        let rolesHtml = '';
        window.allRoles.forEach(role => {
            if (role.name.toLowerCase() === 'admin') return;
            let checked = roles.includes(role.name) ? 'checked' : '';
            rolesHtml += `
                <div class="form-check me-3">
                    <input class="form-check-input role-checkbox" type="checkbox" name="roles[]" value="${role.name}" ${checked} data-role="${role.name}">
                    <label class="form-check-label">${role.name}</label>
                </div>`;
        });
        $('#editUserRoles').html(rolesHtml);

        // Permissions
        updatePermissionsDisplay(roles, permissions);

        // Handle role checkbox changes
        $(document).off('change', '.role-checkbox').on('change', '.role-checkbox', function() {
            let selectedRoles = [];
            $('.role-checkbox:checked').each(function() {
                selectedRoles.push($(this).val());
            });
            let currentPermissions = [];
            $('input[name="permissions[]"]:checked:not(:disabled)').each(function() {
                currentPermissions.push($(this).val());
            });
            updatePermissionsDisplay(selectedRoles, currentPermissions);
        });

        $('#editUserModal').modal('show');
    });

    function updatePermissionsDisplay(roles, permissions) {
        let rolePermissions = [];
        if (roles.length) {
            roles.forEach(r => {
                let roleObj = window.allRoles.find(x => x.name === r);
                if (roleObj && roleObj.permissions) {
                    rolePermissions.push(...roleObj.permissions);
                }
            });
        }
        rolePermissions = [...new Set(rolePermissions)];

        let permsHtml = '';
        window.allPermissions.forEach(perm => {
            let isFromRole = rolePermissions.includes(perm.name);
            let checked = permissions.includes(perm.name) ? 'checked' : '';
            let disabled = isFromRole ? 'disabled' : '';
            let strikeStyle = isFromRole ? 'style="text-decoration: line-through; color:black;"' : '';

            permsHtml += `
                <div class="col-6">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="permissions[]" value="${perm.name}" ${checked} ${disabled}>
                        <label class="form-check-label" ${strikeStyle}>${perm.name}</label>
                    </div>
                </div>`;
        });
        $('#editUserPermissions').html(permsHtml);
    }

    // === Edit User Form Submit ===
    $('#editUserForm').on('submit', function(e) {
        e.preventDefault();
        let form = $(this);
        let url = form.attr('action');
        let formData = form.serialize();

        $.ajax({
            url: url,
            method: 'POST',
            data: formData,
            success: function(response) {
                $('#editUserModal').modal('hide');
                table.ajax.reload();
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: response.success || 'User updated successfully!',
                    timer: 2000,
                    showConfirmButton: false


                });
            },
            error: function(xhr) {
                let errorMsg = 'Something went wrong!';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMsg = xhr.responseJSON.message;
                }
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: errorMsg
                });
            }
        });
    });

    // Reset on redraw
    table.on('draw', function() {
        $('#checkAll').prop('checked', false).prop('indeterminate', false);
        $('#bulkDeleteBtn').addClass('d-none');
    });
});
</script>
@endpush
