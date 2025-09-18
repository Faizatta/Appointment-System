@extends('layouts.layout')

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<style>
    /* Table */
    #patients-table {
        font-size: 0.875rem;
        width: 100%;
        border-collapse: collapse;
        border: none;
    }
    #patients-table th, #patients-table td {
        vertical-align: middle;
        border: none;
    }
    #patients-table thead th {
        font-size: 1rem;
        font-weight: 700;
    }
    .patient-cell {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    .patient-cell img, .patient-cell .initials {
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
    .action-icons {
        display: flex;
    }
    .action-icons button {
        display: flex;
        align-items: center;
        justify-content: center;
        height: 28px;
        width: 22px;
        padding: 0;
        font-size: 1rem;
        background: transparent;
        border: none;
        cursor: pointer;
    }
    .action-icons button[disabled] i {
        color: #6c757d;
        cursor: not-allowed;
    }
</style>
@endpush

@section('content')
<div class="container mt-2" style="max-width: 1000px;">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0 fw-bold">Patients List</h4>
        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#patientModal"
            @cannot('add patient') disabled @endcannot>
            <i class="bi bi-plus-circle me-1"></i> Add Patient
        </button>
    </div>

    <table id="patients-table" class="table table-sm align-middle">
        <thead>
            <tr>
                <th>Patient</th>
                <th>Doctor</th>
                <th>Address</th>
                <th>Contact</th>
                <th class="text-center">Actions</th>
            </tr>
        </thead>
    </table>
</div>

@can('add patient')
    @include('components.addpatientmodal', ['patient' => null, 'doctors' => $doctors])
@endcan

<!-- Edit Patient Modal -->
<div class="modal fade" id="editPatientModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header ">
                <h5 class="modal-title">Edit Patient</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="editPatientForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="row mb-3">
                        <div class="col">
                            <label class="form-label">Name</label>
                            <input type="text" id="editPatientName" name="name" class="form-control form-control-sm" required>
                        </div>
                        <div class="col">
                            <label class="form-label">Doctor</label>
                            <select id="editDoctorId" name="doctor_id" class="form-control form-control-sm">
                                <option value="">— None —</option>
                                @foreach ($doctors as $doc)
                                    <option value="{{ $doc->id }}">{{ $doc->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col">
                            <label class="form-label">Phone</label>
                            <input type="text" id="editPatientPhone" name="phone" class="form-control form-control-sm">
                        </div>
                        <div class="col">
                            <label class="form-label">Email</label>
                            <input type="email" id="editPatientMail" name="mail" class="form-control form-control-sm">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col">
                            <label class="form-label">Profile Picture</label>
                            <input type="file" name="image" class="form-control form-control-sm">
                            <img id="editPatientImagePreview" width="70" height="70" class="border rounded mt-2 d-none">
                        </div>
                        <div class="col">
                            <label class="form-label">Address</label>
                            <input type="text" id="editPatientAddress" name="address" class="form-control form-control-sm">
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

<!-- View Patient Modal -->
<div class="modal fade" id="viewPatientModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title">Patient Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-8">
                        <p><strong>Name:</strong> <span id="viewPatientName"></span></p>
                        <p><strong>Doctor:</strong> <span id="viewPatientDoctor"></span></p>
                        <p><strong>Email:</strong> <span id="viewPatientMail"></span></p>
                        <p><strong>Phone:</strong> <span id="viewPatientPhone"></span></p>
                        <p><strong>Address:</strong> <span id="viewPatientAddress"></span></p>
                    </div>
                    <div class="col-md-4 text-center">
                        <img id="viewPatientImage" width="140" height="140" class="border rounded" style="object-fit:cover;">
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
    var table = $('#patients-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('patients.index') }}",
        pageLength: 5,
        lengthMenu: [5, 10, 25],
        columns: [
            {
                data: 'patient.name',
                name: 'patient.name',
                render: function(data, type, row) {
                    if (type === 'display') {
                        if (row.patient.image) {
                            return `<div class="patient-cell">
                                <img src="${row.patient.image}" alt="Patient">
                                <span>${row.patient.name}</span>
                            </div>`;
                        } else {
                            return `<div class="patient-cell">
                                <div class="initials">${row.patient.initials}</div>
                                <span>${row.patient.name}</span>
                            </div>`;
                        }
                    }
                    return data;
                }
            },
            { data: 'doctor', name: 'doctor' },
            { data: 'address', name: 'address' },
            {
                data: 'contact.mail',
                name: 'contact.mail',
                render: function(data, type, row) {
                    if (type === 'display') {
                        return `<div>${data ?? ''}<br><span class="text-secondary">${row.contact.phone ?? ''}</span></div>`;
                    }
                    return data;
                }
            },
            {
                data: 'id',
                name: 'actions',
                orderable: false,
                searchable: false,
                className: 'action-icons',
                render: function(data, type, row) {
                    let buttons = '';

                    buttons += `<button class="btn btn-sm viewPatient"
                        data-id="${row.patient.id}"
                        data-name="${row.patient.name}"
                        data-doctor="${row.doctor}"
                        data-mail="${row.contact.mail}"
                        data-phone="${row.contact.phone}"
                        data-address="${row.address}"
                        data-image="${row.patient.image ?? ''}"
                        ${!row.permissions.canView ? 'disabled' : ''}>
                        <i class="bi bi-eye"></i>
                    </button>`;

                    buttons += `<button class="btn btn-sm editPatient"
                        data-id="${row.patient.id}"
                        data-name="${row.patient.name}"
                        data-doctor-id="${row.patient.doctor_id ?? ''}"
                        data-mail="${row.contact.mail}"
                        data-phone="${row.contact.phone}"
                        data-address="${row.address}"
                        data-image="${row.patient.image ?? ''}"
                        ${!row.permissions.canEdit ? 'disabled' : ''}>
                        <i class="bi bi-pencil-square"></i>
                    </button>`;

                    buttons += `<form action="/patients/${row.patient.id}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm delete-patient" ${!row.permissions.canDelete ? 'disabled' : ''}>
                            <i class="bi bi-trash"></i>
                        </button>
                    </form>`;

                    return buttons;
                }
            }
        ]
    });

    // Delete patient
    $(document).on('click', '.delete-patient', function(e) {
        if ($(this).is(':disabled')) return;
        e.preventDefault();
        let form = $(this).closest('form');
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
                $.ajax({
                    url: form.attr('action'),
                    type: 'POST',
                    data: form.serialize(),
                    success: function() {
                        table.ajax.reload(null, false);
                        Swal.fire('Deleted!', 'Patient deleted successfully.', 'success');
                    },
                    error: function() {
                        Swal.fire('Error!', 'Something went wrong.', 'error');
                    }
                });
            }
        });
    });

    // Edit patient
    $(document).on('click', '.editPatient', function() {
        if ($(this).is(':disabled')) return;
        let btn = $(this);
        $('#editPatientName').val(btn.data('name'));
        $('#editPatientPhone').val(btn.data('phone'));
        $('#editPatientMail').val(btn.data('mail'));
        $('#editPatientAddress').val(btn.data('address'));
        $('#editDoctorId').val(btn.data('doctor-id'));
        let img = btn.data('image');
        if (img) {
            $('#editPatientImagePreview').attr('src', img).removeClass('d-none');
        } else {
            $('#editPatientImagePreview').addClass('d-none');
        }
        $('#editPatientForm').attr('action', '/patients/' + btn.data('id'));
        new bootstrap.Modal(document.getElementById('editPatientModal')).show();
    });

    // View patient
    $(document).on('click', '.viewPatient', function() {
        if ($(this).is(':disabled')) return;
        let btn = $(this);
        $('#viewPatientName').text(btn.data('name'));
        $('#viewPatientDoctor').text(btn.data('doctor'));
        $('#viewPatientMail').text(btn.data('mail'));
        $('#viewPatientPhone').text(btn.data('phone'));
        $('#viewPatientAddress').text(btn.data('address'));
        let img = btn.data('image');
        if (img) {
            $('#viewPatientImage').attr('src', img).removeClass('d-none');
        } else {
            $('#viewPatientImage').attr('src', '{{ asset('default-avatar.png') }}').removeClass('d-none');
        }
        new bootstrap.Modal(document.getElementById('viewPatientModal')).show();
    });
});
</script>
@endpush
