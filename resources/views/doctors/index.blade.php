@extends('layouts.layout')

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

<style>
    /* Table */
    #doctors-table {
        font-size: 0.875rem;
        width: 100%;
        border-collapse: collapse;
    }

    #doctors-table th,
    #doctors-table td {
        vertical-align: middle;
        border: none;
    }

    #doctors-table thead th {
        font-size: 1rem;
        font-weight: 700;
    }

    /* Compact pagination */
    .dataTables_wrapper .dataTables_paginate .paginate_button {
        padding: 4px 8px !important;
        font-size: 0.75rem !important;
        margin: 0 2px !important;
        border-radius: 4px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button.current {
        background-color: #0d6efd !important;
        color: #fff !important;
    }

    /* Compact search + entries */
    .dataTables_wrapper .dataTables_filter input {
        font-size: 0.75rem !important;
        padding: 2px 4px !important;
        height: 26px !important;
    }

    .dataTables_wrapper .dataTables_length select {
        font-size: 0.75rem !important;
        padding: 2px 4px !important;
        height: 26px !important;
    }

    /* Doctor cell */
    .doctor-cell {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .doctor-cell img,
    .doctor-cell .initials {
        width: 35px;
        height: 35px;
        border-radius: 50%;
        object-fit: cover;
        display: inline-flex;
        justify-content: center;
        align-items: center;
        font-size: 0.85rem;
        color: #fff;
        background-color: #6c757d;
    }

    /* Actions */
    .action-icons {
        display: flex;
        align-items: center;
        gap: 2px;
    }

    .action-icons button {
        display: flex;
        align-items: center;
        justify-content: center;
        height: 28px;
        width: 22px;
        font-size: 1rem;
        background: transparent !important;
        border: none;
        color: #000;
        cursor: pointer;
    }

    .action-icons button:hover {
        color: #2c1b1b;
    }

    #doctors-table th.actions {
        pointer-events: none;
        background-image: none !important;
    }
</style>
@endpush

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0 fw-bold">Doctors List</h4>
    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#doctorModal"
        @cannot('add doctor') disabled @endcannot>
        <i class="bi bi-plus-circle me-1"></i> Add Doctor
    </button>
</div>

<table id="doctors-table" class="table table-sm align-middle">
    <thead>
        <tr>
            <th>Doctor</th>
            <th>Contact</th>
            <th>Address</th>
            <th>Patients</th>
            <th class="text-center actions">Actions</th>
        </tr>
    </thead>
</table>

{{-- Add Doctor Modal --}}
<div class="modal fade" id="doctorModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Add Doctor</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="addDoctorForm" action="{{ route('doctors.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row mb-3">
                        <div class="col">
                            <label class="form-label">Name</label>
                            <input type="text" name="name" class="form-control form-control-sm" required>
                        </div>
                        <div class="col">
                            <label class="form-label">Phone</label>
                            <input type="text" name="phone" class="form-control form-control-sm">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control form-control-sm">
                        </div>
                        <div class="col">
                            <label class="form-label">Profile Picture</label>
                            <input type="file" id="add-image" name="image" class="form-control form-control-sm">
                            <img id="add-image-preview" width="70" height="70" class="border rounded mt-2 d-none">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col">
                            <label class="form-label">Address</label>
                            <input type="text" name="address" class="form-control form-control-sm">
                        </div>
                    </div>
                    <div class="text-end">
                        <button type="submit" class="btn btn-success btn-sm">Save</button>
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Edit Doctor Modal --}}
<div class="modal fade" id="editDoctorModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header ">
                <h5 class="modal-title">Edit Doctor</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="editDoctorForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="row mb-3">
                        <div class="col">
                            <label class="form-label">Name</label>
                            <input type="text" id="editDoctorName" name="name" class="form-control form-control-sm" required>
                        </div>
                        <div class="col">
                            <label class="form-label">Phone</label>
                            <input type="text" id="editDoctorPhone" name="phone" class="form-control form-control-sm">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col">
                            <label class="form-label">Email</label>
                            <input type="email" id="editDoctorMail" name="email" class="form-control form-control-sm">
                        </div>
                        <div class="col">
                            <label class="form-label">Profile Picture</label>
                            <input type="file" id="edit-image" name="image" class="form-control form-control-sm">
                            <img id="edit-image-preview" width="70" height="70" class="border rounded mt-2 d-none">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col">
                            <label class="form-label">Address</label>
                            <input type="text" id="editDoctorAddress" name="address" class="form-control form-control-sm">
                        </div>
                    </div>
                    <div class="text-end">
                        <button type="submit" class="btn btn-success btn-sm">Update</button>
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- View Doctor Modal --}}
<div class="modal fade" id="viewDoctorModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title">Doctor Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-8">
                        <p><strong>Name:</strong> <span id="viewDoctorName"></span></p>
                        <p><strong>Patients:</strong> <span id="viewDoctorPatients"></span></p>
                        <p><strong>Email:</strong> <span id="viewDoctorMail"></span></p>
                        <p><strong>Phone:</strong> <span id="viewDoctorPhone"></span></p>
                        <p><strong>Address:</strong> <span id="viewDoctorAddress"></span></p>
                    </div>
                    <div class="col-md-4 text-center">
                        <img id="viewDoctorImage" width="140" height="140" class="border rounded"
                             style="object-fit:cover; display:none;">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(function() {
    let table = $('#doctors-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('doctors.index') }}",
        pageLength: 5,
        language: {
            paginate: { previous: '&laquo;', next: '&raquo;' }
        },
        columns: [
            {
                data: 'doctor',
                name: 'doctor',
                render: function(d, t) {
                    if (typeof d === 'string') d = { name: d, image: null };
                    if (t === 'display') {
                        let initials = '';
                        if (d.name) {
                            let parts = d.name.trim().split(' ');
                            initials = parts.length > 1 ?
                                parts[0][0] + parts[parts.length - 1][0] :
                                parts[0][0];
                            initials = initials.toUpperCase();
                        }
                        let img = d.image ?
                            `<img src="${d.image}" alt="Doctor">` :
                            `<div class="initials">${initials}</div>`;
                        return `<div class="doctor-cell">${img}<span>${d.name}</span></div>`;
                    }
                    return d.name;
                }
            },
            {
                data: 'contact',
                name: 'contact',
                render: d =>
                    `<div>${d.email ?? '—'}<div class="text-muted small">${d.phone ?? '—'}</div></div>`
            },
            { data: 'address', name: 'address' },
            {
                data: 'patients',
                name: 'patients',
                render: function(d) {
                    if (!d || d.length === 0) return 'No Patients';
                    if (Array.isArray(d)) return d.map(p => p.name).join(', ');
                    return d;
                }
            },
            {
                data: 'actions',
                name: 'actions',
                orderable: false,
                searchable: false
            }
        ]
    });

    // Add Doctor Image preview
    $('#add-image').on('change', function() {
        const f = this.files[0];
        if (f) {
            let r = new FileReader();
            r.onload = e => $('#add-image-preview').attr('src', e.target.result).removeClass('d-none');
            r.readAsDataURL(f);
        } else $('#add-image-preview').addClass('d-none');
    });

    // Edit Doctor Image preview
    $('#edit-image').on('change', function() {
        const f = this.files[0];
        if (f) {
            let r = new FileReader();
            r.onload = e => $('#edit-image-preview').attr('src', e.target.result).removeClass('d-none');
            r.readAsDataURL(f);
        } else $('#edit-image-preview').addClass('d-none');
    });

    // View Doctor
    $(document).on('click', '.view-doctor', function() {
        let d = $(this).data('doctor');
        $('#viewDoctorName').text(d.name);
        $('#viewDoctorMail').text(d.email ?? '—');
        $('#viewDoctorPhone').text(d.phone ?? '—');
        $('#viewDoctorAddress').text(d.address ?? '—');
        if (Array.isArray(d.patients) && d.patients.length > 0) {
            $('#viewDoctorPatients').text(d.patients.map(p => p.name).join(', '));
        } else {
            $('#viewDoctorPatients').text('No Patients');
        }
        if (d.image) {
            $('#viewDoctorImage').attr('src', d.image).show();
        } else {
            $('#viewDoctorImage').hide();
        }
        $('#viewDoctorModal').modal('show');
    });

    // Edit Doctor
    $(document).on('click', '.edit-doctor', function() {
        let d = $(this).data('doctor');
        $('#editDoctorForm').attr('action', '/doctors/' + d.id);
        $('#editDoctorName').val(d.name);
        $('#editDoctorMail').val(d.email);
        $('#editDoctorPhone').val(d.phone);
        $('#editDoctorAddress').val(d.address);
        if (d.image) {
            $('#edit-image-preview').attr('src', d.image).removeClass('d-none');
        } else {
            $('#edit-image-preview').addClass('d-none');
        }
        $('#editDoctorModal').modal('show');
    });

    // Delete Doctor
    $(document).on('click', '.delete-doctor', function(e) {
        e.preventDefault();
        let form = $(this).closest('form');
        Swal.fire({
            title: 'Are you sure?',
            icon: 'warning',
            showCancelButton: true
        }).then(r => {
            if (r.isConfirmed) {
                $.post(form.attr('action'), form.serialize())
                    .done(() => {
                        table.ajax.reload();
                        Swal.fire('Deleted!', 'Doctor deleted.', 'success');
                    })
                    .fail(() => Swal.fire('Error!', 'Something went wrong.', 'error'));
            }
        });
    });
});
</script>
@endpush
