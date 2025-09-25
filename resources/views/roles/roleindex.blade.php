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
        dom: '<"d-flex align-items-center justify-content-between mb-2"<"d-flex align-items-center gap-2"l<"datatable-buttons">><"d-flex"f>>rtip',
        columns: [
            {
                data: 'id',
                render: function(data, type, row) {
                    // Check if user is admin - handle both boolean and string formats
                    let isAdmin = false;

                    // Check is_admin field
                    if (row.is_admin === true || row.is_admin === 1 || row.is_admin === '1') {
                        isAdmin = true;
                    }

                    // Check roles string for admin
                    if (row.roles && typeof row.roles === 'string' &&
                        row.roles.toLowerCase().includes('admin')) {
                        isAdmin = true;
                    }

                    // Check roles array for admin
                    if (Array.isArray(row.roles_array) &&
                        row.roles_array.some(role => role.toLowerCase().includes('admin'))) {
                        isAdmin = true;
                    }

                    // Don't show checkbox for admin users
                    if (isAdmin) {
                        return '<span class="text-muted">—</span>';
                    }

                    return `<input type="checkbox" class="row-check" value="${data}">`;
                },
                orderable: false,
                searchable: false,
                className: 'text-center'
            },
            {
                data: 'name',
                name: 'name',
                render: function(data, type, row) {
                    // Check if user is admin
                    let isAdmin = false;
                    if (row.is_admin === true || row.is_admin === 1 || row.is_admin === '1') {
                        isAdmin = true;
                    }
                    if (row.roles && typeof row.roles === 'string' &&
                        row.roles.toLowerCase().includes('admin')) {
                        isAdmin = true;
                    }


                    return data;
                }
            },
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
            // Add admin row styling
            let isAdmin = false;
            if (data.is_admin === true || data.is_admin === 1 || data.is_admin === '1') {
                isAdmin = true;
            }
            if (data.roles && typeof data.roles === 'string' &&
                data.roles.toLowerCase().includes('admin')) {
                isAdmin = true;
            }

            if (isAdmin) {
                $(row).addClass('admin-row');
            }
        }
    });

    // Add bulk delete button to datatable
    $("div.datatable-buttons").html(`
        <button id="bulkDeleteBtn" class="btn btn-sm d-flex align-items-center d-none"
            style="padding: 2px 8px; font-size: 0.75rem; gap: 4px; background: none; color: #dc3545; border:1px solid #dc3545; border-radius:4px;">
            <i class="fas fa-trash" style="font-size:0.8rem;"></i> Delete all
        </button>
    `);

    // === Bulk Select ===
    $(document).on('change', '#checkAll', function() {
        $('.row-check').prop('checked', $(this).prop('checked'));
        toggleBulkButton();
    });

    $(document).on('change', '.row-check', function() {
        toggleBulkButton();

        // Update "Check All" checkbox state
        let totalCheckboxes = $('.row-check').length;
        let checkedCheckboxes = $('.row-check:checked').length;

        if (checkedCheckboxes === 0) {
            $('#checkAll').prop('indeterminate', false).prop('checked', false);
        } else if (checkedCheckboxes === totalCheckboxes) {
            $('#checkAll').prop('indeterminate', false).prop('checked', true);
        } else {
            $('#checkAll').prop('indeterminate', true);
        }
    });

    function toggleBulkButton() {
        let checkedCount = $('.row-check:checked').length;
        if (checkedCount > 0) {
            $('#bulkDeleteBtn').removeClass('d-none').text(`Delete all`);
        } else {
            $('#bulkDeleteBtn').addClass('d-none');
        }
    }

    // === Bulk Delete ===
    $(document).on('click', '#bulkDeleteBtn', function() {
        let ids = $('.row-check:checked').map(function() {
            return $(this).val();
        }).get();

        if (ids.length === 0) return;

        Swal.fire({
            title: 'Are you sure?',
            text: `This will permanently delete ${ids.length} selected user${ids.length > 1 ? 's' : ''}`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete them!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                // Show loading state
                let originalText = $('#bulkDeleteBtn').html();
                $('#bulkDeleteBtn').html('<i class="fas fa-spinner fa-spin"></i> Deleting...').prop('disabled', true);

                $.ajax({
                    url: "{{ route('users.bulkDelete') }}",
                    method: 'POST',
                    data: {
                        ids: ids,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        $('#checkAll').prop('checked', false).prop('indeterminate', false);
                        table.ajax.reload();
                        $('#bulkDeleteBtn').addClass('d-none').html(originalText).prop('disabled', false);
                        Swal.fire('Deleted!', response.success || 'Users deleted successfully.', 'success');
                    },
                    error: function(xhr) {
                        $('#bulkDeleteBtn').html(originalText).prop('disabled', false);
                        let errorMessage = 'Something went wrong.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        Swal.fire('Error!', errorMessage, 'error');
                    }
                });
            }
        });
    });

    // SweetAlert delete for single items
    $(document).on('submit', '.delete-role-form, .delete-user-form', function(e) {
        e.preventDefault();
        let form = this;
        let itemType = $(form).hasClass('delete-role-form') ? 'role' : 'user';

        Swal.fire({
            title: 'Are you sure?',
            text: `This will permanently delete this ${itemType}!`,
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
            @if (strtolower($role->name) !== 'admin')
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

    table.on('draw', function() {
        $('#checkAll').prop('checked', false).prop('indeterminate', false);
        $('#bulkDeleteBtn').addClass('d-none');
    });

    // Prevent accidental admin deletion
    $(document).on('click', '.delete-user-form button[type="submit"]', function(e) {
        let form = $(this).closest('form');
        let actionUrl = form.attr('action');


        let userId = actionUrl.split('/').pop();


    });
});
</script>
@endpush
