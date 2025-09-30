@extends('layouts.layout')

@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        /* Table */
        #patients-table {
            font-size: 0.875rem;
            width: 100%;
            border-collapse: collapse;
        }

        #patients-table th,
        #patients-table td {
            vertical-align: middle;
            border: none;
        }

        #patients-table thead th {
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
        .dataTables_wrapper .dataTables_filter input,
        .dataTables_wrapper .dataTables_length select {
            font-size: 0.75rem !important;
            padding: 2px 4px !important;
            height: 26px !important;
        }

        /* Patient cell */
        .patient-cell {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .patient-cell img,
        .patient-cell .initials {
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

        #patients-table th.actions {
            pointer-events: none;
            background-image: none !important;
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

        /* Actions column for Patients table */
        #patients-table th.actions,
        #patients-table td.actions-cell {
            text-align: center;
            vertical-align: middle;
        }

        .action-icons {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 2px;
        }

        .action-icons button:hover,
        .action-icons form button:hover {
            background-color: #f0f0f0;
        }

        .action-icons button:disabled {
            color: #bbb !important;
            cursor: not-allowed !important;
        }
    </style>
@endpush

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0 fw-bold">Patients List</h4>
        <div>
            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#patientModal"
                @cannot('add patient') disabled @endcannot>
                <i class="bi bi-plus-circle me-1"></i> Add Patient
            </button>
        </div>
    </div>

    <table id="patients-table" class="table table-sm align-middle">
        <thead>
            <tr>
                <th><input type="checkbox" id="checkAll"></th>
                <th>Patient</th>
                <th>Doctor</th>
                <th>Contact</th>
                <th>Address</th>
                <th class="text-center actions">Actions</th>
            </tr>
        </thead>
    </table>


    @can('add patient')
        <div class="modal fade" id="patientModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-slate-600 text-white">
                        <h5 class="modal-title">Add Patient</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <form id="addPatientForm" action="{{ route('patients.store') }}" method="POST"
                            enctype="multipart/form-data">
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
                                    <input type="email" name="mail" class="form-control form-control-sm">
                                </div>
                                <div class="col">
                                    <label class="form-label">Profile Picture</label>
                                    <input type="file" id="add-image" name="image" class="form-control form-control-sm">
                                    <img id="add-image-preview" width="70" height="70"
                                        class="border rounded mt-2 d-none">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col">
                                    <label class="form-label">Doctor</label>
                                    <select name="doctor_id" class="form-control form-control-sm">
                                        <option value="">Select Doctor</option>
                                        @foreach ($doctors as $doc)
                                            <option value="{{ $doc->id }}">{{ $doc->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
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
    @endcan

    {{-- Edit Patient Modal --}}
    @can('manage patient')
        <div class="modal fade" id="editPatientModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
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
                                    <input type="text" id="editPatientName" name="name"
                                        class="form-control form-control-sm" required>
                                </div>
                                <div class="col">
                                    <label class="form-label">Phone</label>
                                    <input type="text" id="editPatientPhone" name="phone"
                                        class="form-control form-control-sm">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col">
                                    <label class="form-label">Email</label>
                                    <input type="email" id="editPatientMail" name="mail"
                                        class="form-control form-control-sm">
                                </div>
                                <div class="col">
                                    <label class="form-label">Profile Picture</label>
                                    <input type="file" id="edit-image" name="image"
                                        class="form-control form-control-sm">
                                    <img id="edit-image-preview" width="70" height="70"
                                        class="border rounded mt-2 d-none">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col">
                                    <label class="form-label">Doctor</label>
                                    <select name="doctor_id" id="editPatientDoctor" class="form-control form-control-sm">
                                        <option value="">Select Doctor</option>
                                        @foreach ($doctors as $doc)
                                            <option value="{{ $doc->id }}">{{ $doc->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col">
                                    <label class="form-label">Address</label>
                                    <input type="text" id="editPatientAddress" name="address"
                                        class="form-control form-control-sm">
                                </div>
                            </div>
                            <div class="text-end">
                                <button type="submit" class="btn btn-success btn-sm">Update</button>
                                <button type="button" class="btn btn-secondary btn-sm"
                                    data-bs-dismiss="modal">Cancel</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endcan

    {{-- View Patient Modal --}}
    @can('manage patient')
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
                                <img id="viewPatientImage" width="140" height="140" class="border rounded"
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
    @endcan
@endsection

@push('scripts')
    <script>
        $(function() {
            let table = $('#patients-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('patients.index') }}",
                pageLength: 5,
                lengthMenu: [5, 10, 25, 50],
                language: {
                    paginate: {
                        previous: '&laquo;',
                        next: '&raquo;'
                    },
                    lengthMenu: "Show _MENU_ entries"
                },
                dom: '<"d-flex align-items-center justify-content-between mb-2"<"d-flex align-items-center gap-2"l<"datatable-buttons">><"d-flex"f>>rtip',
                columns: [{
                        data: 'id',
                        render: id => `<input type="checkbox" class="row-check" value="${id}">`,
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'patient',
                        name: 'patient',
                        render: function(d, t) {
                            if (typeof d === 'string') d = {
                                name: d,
                                image: null
                            };
                            if (t === 'display') {
                                let initials = '';
                                if (d.name) {
                                    let parts = d.name.trim().split(' ');
                                    initials = parts.length > 1 ? parts[0][0] + parts[parts.length -
                                        1][0] : parts[0][0];
                                    initials = initials.toUpperCase();
                                }
                                let img = d.image ? `<img src="${d.image}" alt="Patient">` :
                                    `<div class="initials">${initials}</div>`;
                                return `<div class="patient-cell">${img}<span>${d.name}</span></div>`;
                            }
                            return d.name;
                        }
                    },
                    {
                        data: 'doctor',
                        name: 'doctor'
                    },
                    {
                        data: 'contact',
                        name: 'contact',
                        render: d =>
                            `<div>${d.mail??'—'}<div class="text-muted small">${d.phone??'—'}</div></div>`
                    },
                    {
                        data: 'address',
                        name: 'address'
                    },
                    {
                        data: 'actions',
                        name: 'actions',
                        orderable: false,
                        searchable: false
                    }
                ]
            });

            $("div.datatable-buttons").html(`
            @can('bul delete')
                <button id="bulkDeleteBtn" class="btn btn-sm d-flex align-items-center d-none"
                    style="padding: 2px 8px; font-size: 0.75rem; gap: 4px; background: none; color: #dc3545; border:1px solid #dc3545; border-radius:4px;">
                    <i class="bi bi-trash" style="font-size:0.8rem;"></i> Delete
                </button>
            @endcan
                `);


            // === Bulk Select ===
            $(document).on('change', '#checkAll', function() {
                $('.row-check').prop('checked', $(this).prop('checked'));
                toggleBulkButton();
            });
            $(document).on('change', '.row-check', toggleBulkButton);

            function toggleBulkButton() {
                let checkedCount = $('.row-check:checked').length;
                if (checkedCount > 0) {
                    $('#bulkDeleteBtn').removeClass('d-none');
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
                        text: "This will delete selected patients",
                        icon: 'warning',
                        showCancelButton: true
                    })
                    .then(result => {
                        if (result.isConfirmed) {
                            $.post("{{ route('patients.bulkDelete') }}", {
                                    ids: ids,
                                    _token: "{{ csrf_token() }}"
                                })
                                .done(res => {
                                    $('#checkAll').prop('checked', false);
                                    table.ajax.reload();
                                    $('#bulkDeleteBtn').addClass('d-none');
                                    Swal.fire('Deleted!', res.success, 'success');
                                }).fail(() => Swal.fire('Error!', 'Something went wrong.', 'error'));
                        }
                    });
            });

            // === Image Previews ===
            $('#add-image').on('change', function() {
                const f = this.files[0];
                if (f) {
                    let r = new FileReader();
                    r.onload = e => $('#add-image-preview').attr('src', e.target.result).removeClass(
                        'd-none');
                    r.readAsDataURL(f);
                } else $('#add-image-preview').addClass('d-none');
            });

            $('#edit-image').on('change', function() {
                const f = this.files[0];
                if (f) {
                    let r = new FileReader();
                    r.onload = e => $('#edit-image-preview').attr('src', e.target.result).removeClass(
                        'd-none');
                    r.readAsDataURL(f);
                } else $('#edit-image-preview').addClass('d-none');
            });

            // === View Patient ===
            $(document).on('click', '.view-patient', function() {
                let p = $(this).data('patient');
                $('#viewPatientName').text(p.name);
                $('#viewPatientMail').text(p.mail ?? '—');
                $('#viewPatientPhone').text(p.phone ?? '—');
                $('#viewPatientAddress').text(p.address ?? '—');
                $('#viewPatientDoctor').text(p.doctor ?? '—');
                if (p.image) $('#viewPatientImage').attr('src', p.image).show();
                else $('#viewPatientImage').hide();
                $('#viewPatientModal').modal('show');
            });

            // === Edit Patient ===
            // $(document).on('click', '.edit-patient', function() {
            //     let p = $(this).data('patient');
            //     $('#editPatientForm').attr('action', '/patients/' + p.id);
            //     $('#editPatientName').val(p.name);
            //     $('#editPatientMail').val(p.mail);
            //     $('#editPatientPhone').val(p.phone);
            //     $('#editPatientAddress').val(p.address);
            //     $('#editPatientDoctor').val(p.doctor_id);
            //     if (p.image) $('#edit-image-preview').attr('src', p.image).removeClass('d-none');
            //     else $('#edit-image-preview').addClass('d-none');
            //     $('#editPatientModal').modal('show');
            // });

            $(document).on('click', '.edit-patient', function() {
                let p = $(this).data('patient');
                if (typeof p === 'string') {
                    p = JSON.parse(p); // fallback in case data-patient is a string
                }

                $('#editPatientForm').attr('action', '/patients/' + p.id);
                $('#editPatientName').val(p.name);
                $('#editPatientMail').val(p.mail);
                $('#editPatientPhone').val(p.phone);
                $('#editPatientAddress').val(p.address);
                $('#editPatientDoctor').val(p.doctor_id);

                if (p.image) {
                    $('#edit-image-preview').attr('src', p.image).removeClass('d-none');
                } else {
                    $('#edit-image-preview').addClass('d-none');
                }

                $('#editPatientModal').modal('show');
            });

            // === Form Submissions ===
            $('#addPatientForm').on('submit', function(e) {
                e.preventDefault();
                let formData = new FormData(this);
                let submitBtn = $(this).find('button[type="submit"]');

                submitBtn.prop('disabled', true).text('Saving...');

                $.ajax({
                    url: $(this).attr('action'),
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        $('#patientModal').modal('hide');
                        $('.modal-backdrop').remove();
                        $('body').removeClass('modal-open');

                        $('#addPatientForm')[0].reset();
                        $('#add-image-preview').addClass('d-none');

                        table.ajax.reload(null, false);
                        Swal.fire('Success!', 'Patient added successfully.', 'success');
                    },
                    error: function(xhr) {
                        let errorMessage = 'Could not add patient.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        Swal.fire('Error!', errorMessage, 'error');
                    },
                    complete: function() {
                        submitBtn.prop('disabled', false).text('Save');
                    }
                });
            });

            $('#editPatientForm').on('submit', function(e) {
                e.preventDefault();
                let formData = new FormData(this);
                let submitBtn = $(this).find('button[type="submit"]');

                submitBtn.prop('disabled', true).text('Updating...');

                $.ajax({
                    url: $(this).attr('action'),
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        $('#editPatientModal').modal('hide');
                        $('.modal-backdrop').remove();
                        $('body').removeClass('modal-open');

                        table.ajax.reload(null, false);
                        Swal.fire('Success!', 'Patient updated successfully.', 'success');
                    },
                    error: function(xhr) {
                        let errorMessage = 'Could not update patient.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        Swal.fire('Error!', errorMessage, 'error');
                    },
                    complete: function() {
                        submitBtn.prop('disabled', false).text('Update');
                    }
                });
            });

            // === Single Delete ===
            $(document).on('click', '.delete-patient', function(e) {
                e.preventDefault();
                let form = $(this).closest('form');
                Swal.fire({
                        title: 'Are you sure?',
                        icon: 'warning',
                        showCancelButton: true
                    })
                    .then(r => {
                        if (r.isConfirmed) {
                            $.post(form.attr('action'), form.serialize())
                                .done(() => {
                                    table.ajax.reload(null, false);
                                    Swal.fire('Deleted!', 'Patient deleted.', 'success');
                                })
                                .fail(() => Swal.fire('Error!', 'Something went wrong.', 'error'));
                        }
                    });
            });

            // === Modal Event Handlers ===
            $('#patientModal').on('hidden.bs.modal', function() {
                $('#addPatientForm')[0].reset();
                $('#add-image-preview').addClass('d-none');
                $('.modal-backdrop').remove();
                $('body').removeClass('modal-open');
            });

            $('#editPatientModal').on('hidden.bs.modal', function() {
                $('#edit-image-preview').addClass('d-none');
                $('.modal-backdrop').remove();
                $('body').removeClass('modal-open');
            });

            $('#viewPatientModal').on('hidden.bs.modal', function() {
                $('.modal-backdrop').remove();
                $('body').removeClass('modal-open');
            });
        });
    </script>
@endpush
