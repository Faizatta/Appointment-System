@extends('layouts.layout')

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<style>
/* Table */
#patients-table {
    font-size: 0.875rem;
    width: 100%;
    border-collapse: collapse;
}
#patients-table th, #patients-table td {
    vertical-align: middle;
    border: none;
}
#patients-table thead th {
    font-size: 1rem;
    font-weight: 700;
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

/* Actions column */
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

/* Remove sorting arrows for actions */
#patients-table th.actions {
    pointer-events: none;
    background-image: none !important;
}

/* Datatables input sizes */
.dataTables_wrapper .dataTables_filter input {
    height: 28px;
    width: 180px;
    font-size: 0.875rem;
    margin-left: 0.25rem;
}
.dataTables_wrapper .dataTables_length select {
    height: 28px;
    font-size: 0.875rem;
    padding: 0 5px;
}
.dataTables_wrapper .dataTables_paginate .paginate_button {
    padding: 2px 6px !important;
    font-size: 0.75rem !important;
}

/* Center modals vertically */
.modal-dialog {
    display: flex;
    align-items: center;
    min-height: calc(100% - 1rem);
}

/* Modal content */
.modal-content {
    width: 100%;
    margin: auto;
    border-radius: 0.5rem;
    padding: 10px;
}

/* Modal headers */
.modal-header {
    padding: 0.25rem 0.5rem;
    border-bottom: none;
}
.modal-title {
    font-size: 1rem;
    font-weight: 600;
}
.btn-close {
    padding: 0.25rem 0.5rem;
    margin: 0;
}

/* View Patient Modal Layout */
#viewPatientModal .modal-body {
    display: flex;
    justify-content: space-between;
    gap: 10px;
    flex-wrap: wrap;
}
#viewPatientModal .details { flex: 1; min-width: 60%; }
#viewPatientModal .details p { margin: 0.2rem 0; }
#viewPatientModal .patient-image { width: 120px; text-align: center; }
#viewPatientModal .patient-image img {
    width: 100px;
    height: 100px;
    object-fit: cover;
    border-radius: 0; /* square image */
}
#viewPatientModal .patient-image p { font-size: 0.8rem; margin-top: 0.25rem; color: #6c757d; }

/* Footer button at end */
#viewPatientModal .modal-footer,
#editPatientModal .modal-footer {
    justify-content: flex-end;
}

/* Add/Edit Modals */
.modal-body .row { display: flex; gap: 10px; flex-wrap: wrap; }
.modal-body .col-half { flex: 1; min-width: 45%; }
.modal-footer .btn { font-size: 0.875rem; padding: 0.25rem 0.5rem; }

/* Edit modal image preview */
#editPatientModal .patient-image {
    width: 120px;
    text-align: center;
    margin-bottom: 10px;
}
#editPatientModal .patient-image img {
    width: 100px;
    height: 100px;
    object-fit: cover;
    border-radius: 0.25rem;
}
</style>
@endpush

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0 fw-bold">Patients List</h4>
    <button class="btn btn-primary btn-sm"
            data-bs-toggle="modal"
            data-bs-target="#patientModal"
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
                <th class="text-center actions">Actions</th>
            </tr>
        </thead>
    </table>
<div>
/
{{-- Add Patient Modal --}}
@can('add patient')
<div class="modal fade" id="patientModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-md display-centered">
    <form id="addPatientForm" action="{{ route('patients.store') }}" method="POST" enctype="multipart/form-data">
      @csrf
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Add Patient</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-half mb-2">
              <label class="form-label">Name</label>
              <input type="text" name="name" class="form-control form-control-sm" required>
            </div>
            <div class="col-half mb-2">
              <label class="form-label">Doctor</label>
              <select name="doctor_id" class="form-control form-control-sm">
                <option value="">Select Doctor</option>
                @foreach($doctors as $doc)
                  <option value="{{ $doc->id }}">{{ $doc->name }}</option>
                @endforeach
              </select>
            </div>
            <div class="col-half mb-2">
              <label class="form-label">Address</label>
              <input type="text" name="address" class="form-control form-control-sm">
            </div>
            <div class="col-half mb-2">
              <label class="form-label">Mail</label>
              <input type="email" name="mail" class="form-control form-control-sm">
            </div>
            <div class="col-half mb-2">
              <label class="form-label">Phone</label>
              <input type="text" name="phone" class="form-control form-control-sm">
            </div>
            <div class="col-half mb-2">
              <label class="form-label">Image</label>
              <input type="file" name="image" id="add-image" class="form-control form-control-sm">
              <div class="mt-1 text-center">
                <img id="add-image-preview" src="" style="width:100px;height:100px;object-fit:cover;border-radius:0.25rem; display:none;">
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary btn-sm">Save</button>
          <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
        </div>
      </div>
    </form>
  </div>
</div>
@endcan

{{-- View Patient Modal --}}
<div class="modal fade" id="viewPatientModal" tabindex="-1" aria-labelledby="viewPatientLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header">
                <h5 class="modal-title" id="viewPatientLabel">Patient Details</h5>
                <button type="button" class="btn-close btn-close-sm" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="details">
                    <p><strong>Name:</strong> <span id="view-name"></span></p>
                    <p><strong>Mail:</strong> <span id="view-mail"></span></p>
                    <p><strong>Phone:</strong> <span id="view-phone"></span></p>
                    <p><strong>Address:</strong> <span id="view-address"></span></p>
                    <p><strong>Doctor:</strong> <span id="view-doctor"></span></p>
                </div>
                <div class="patient-image">
                    <img id="view-image" src="" alt="Patient Image" style="display:none;">
                    <p id="view-no-image">No Image</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm px-4" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

{{-- Edit Patient Modal --}}
<div class="modal fade" id="editPatientModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-md">
    <form id="editPatientForm" method="POST" enctype="multipart/form-data">
      @csrf
      @method('PUT')
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Edit Patient</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body d-flex flex-wrap">
          <div class="flex-grow-1 me-3">
            <div class="row">
              <div class="col-half mb-2">
                <label class="form-label">Name</label>
                <input type="text" name="name" id="edit-name" class="form-control form-control-sm" required>
              </div>
              <div class="col-half mb-2">
                <label class="form-label">Doctor</label>
                <select name="doctor_id" id="edit-doctor" class="form-control form-control-sm">
                  @foreach($doctors as $doc)
                    <option value="{{ $doc->id }}">{{ $doc->name }}</option>
                  @endforeach
                </select>
              </div>
              <div class="col-half mb-2">
                <label class="form-label">Address</label>
                <input type="text" name="address" id="edit-address" class="form-control form-control-sm">
              </div>
              <div class="col-half mb-2">
                <label class="form-label">Mail</label>
                <input type="email" name="mail" id="edit-mail" class="form-control form-control-sm">
              </div>
              <div class="col-half mb-2">
                <label class="form-label">Phone</label>
                <input type="text" name="phone" id="edit-phone" class="form-control form-control-sm">
              </div>
              <div class="col-half mb-2">
                <label class="form-label">Change Image</label>
                <input type="file" name="image" id="edit-image" class="form-control form-control-sm">
              </div>
            </div>
          </div>

          <div class="patient-image text-center">
            <label>Current Image</label><br>
            <img id="edit-image-preview" src="" alt="Patient Image" style="width:100px;height:100px;object-fit:cover;border-radius:0.25rem; display:none;">
            <p id="edit-no-image">No Image</p>
          </div>
        </div>
        <div class="modal-footer">
            <button type="submit" class="btn btn-primary btn-sm me-2">Update</button>
            <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
        </div>
      </div>
    </form>
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
        lengthMenu: [5,10,25],
        columns: [
            { data: 'name', name: 'name', render: function(data,type,row){
                if(type==='display'){
                    let img = row.image ? `<img src="/storage/${row.image}" alt="Patient">` : `<div class="initials">${row.initials}</div>`;
                    return `<div class="patient-cell">${img}<span>${data}</span></div>`;
                }
                return data;
            }},
            { data: 'doctor', name: 'doctor' },
            { data: 'address', name: 'address' },
            { data: 'contact', name: 'contact', render: function(data,type,row){
                return type==='display' ? `${data.mail??'—'}<br><span class="text-secondary">${data.phone??''}</span>` : data;
            }},
            { data: 'actions', name: 'actions', orderable:false, searchable:false, className:'action-icons' }
        ],
        dom: '<"d-flex justify-content-between mb-2"lfr>rt<"d-flex justify-content-end mt-1"p>',
        language: {
            paginate: { previous: "&laquo;", next: "&raquo;" },
            lengthMenu: "Show _MENU_ entries",
            searchPlaceholder: "Search patients..."
        }
    });

    // Image preview in Add Modal
    $('#add-image').on('change', function() {
        const file = this.files[0];
        if(file){
            const reader = new FileReader();
            reader.onload = function(e){
                $('#add-image-preview').attr('src', e.target.result).show();
            }
            reader.readAsDataURL(file);
        } else { $('#add-image-preview').hide(); }
    });

    // Add Patient AJAX
    $('#addPatientForm').submit(function(e){
        e.preventDefault();
        let form = new FormData(this);
        $.ajax({
            url: $(this).attr('action'),
            method: 'POST',
            data: form,
            processData: false,
            contentType: false,
            success: function(){
                $('#patientModal').modal('hide');
                table.ajax.reload(null,false);
                Swal.fire('Added!','Patient has been added.','success');
                $('#addPatientForm')[0].reset();
                $('#add-image-preview').hide();
            },
            error: function(){ Swal.fire('Error!','Something went wrong.','error'); }
        });
    });

    // View Patient
    $(document).on('click','.view-patient',function(){
        let p=$(this).data('patient');
        $('#view-name').text(p.name);
        $('#view-mail').text(p.contact.mail??'—');
        $('#view-phone').text(p.contact.phone??'—');
        $('#view-address').text(p.address??'—');
        $('#view-doctor').text(p.doctor??'—');
        if(p.image){
            $('#view-image').attr('src','/storage/'+p.image).show();
            $('#view-no-image').hide();
        } else {
            $('#view-image').hide();
            $('#view-no-image').show();
        }
        $('#viewPatientModal').modal('show');
    });

    // Edit Patient
    $(document).on('click','.edit-patient',function(e){
        e.preventDefault();
        let p=$(this).data('patient');
        $('#editPatientForm').attr('action','/patients/'+p.id);
        $('#edit-name').val(p.name);
        $('#edit-doctor').val(p.doctor_id);
        $('#edit-address').val(p.address);
        $('#edit-mail').val(p.contact.mail);
        $('#edit-phone').val(p.contact.phone);

        if(p.image){
            $('#edit-image-preview').attr('src','/storage/'+p.image).show();
            $('#edit-no-image').hide();
        } else {
            $('#edit-image-preview').hide();
            $('#edit-no-image').show();
        }

        $('#editPatientModal').modal('show');
    });

    // Image preview in Edit Modal
    $('#edit-image').on('change', function() {
        const file = this.files[0];
        if(file){
            const reader = new FileReader();
            reader.onload = function(e){
                $('#edit-image-preview').attr('src', e.target.result).show();
                $('#edit-no-image').hide();
            }
            reader.readAsDataURL(file);
        } else {
            $('#edit-image-preview').hide();
            $('#edit-no-image').show();
        }
    });

    // Update Patient AJAX
    $('#editPatientForm').submit(function(e){
        e.preventDefault();
        let formData = new FormData(this);
        $.ajax({
            url: $(this).attr('action'),
            method:'POST',
            data: formData,
            processData:false,
            contentType:false,
            success:function(){
                $('#editPatientModal').modal('hide');
                table.ajax.reload(null,false);
                Swal.fire('Updated!','Patient has been updated.','success');
            },
            error:function(){ Swal.fire('Error!','Something went wrong.','error'); }
        });
    });

    // Delete Patient
    $(document).on('click','.delete-patient',function(e){
        e.preventDefault();
        let btn=$(this),form=btn.closest('form');
        Swal.fire({
            title:'Are you sure?',
            text:"This action cannot be undone!",
            icon:'warning',
            showCancelButton:true,
            confirmButtonColor:'#d33',
            cancelButtonColor:'#3085d6',
            confirmButtonText:'Yes, delete it',
            cancelButtonText:'Cancel'
        }).then(result=>{
            if(result.isConfirmed){
                $.ajax({
                    url: form.attr('action'),
                    type:'POST',
                    data: form.serialize(),
                    success:function(){
                        table.ajax.reload(null,false);
                        Swal.fire('Deleted!','Patient has been deleted.','success');
                    },
                    error:function(){ Swal.fire('Error!','Something went wrong.','error'); }
                });
            }
        });
    });
});
</script>
@endpush
