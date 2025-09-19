@extends('layouts.layout')

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<style>
/* Table */
#doctors-table {
    font-size: 0.875rem;
    width: 100%;
    border-collapse: collapse;
}
#doctors-table th, #doctors-table td {
    vertical-align: middle;
    border: none;
}
#doctors-table thead th {
    font-size: 1rem;
    font-weight: 700;
}
/* Compact pagination buttons */
.dataTables_wrapper .dataTables_paginate .paginate_button {
    padding: 2px 4px !important;  /* smaller padding */
    font-size: 0.7rem !important; /* smaller font */
    margin: 0 2px !important;     /* reduce spacing */
    border-radius: 3px;           /* optional: slightly rounded */
}

.dataTables_wrapper .dataTables_paginate .paginate_button.current {
    background-color: #0d6efd !important; /* highlight current page */
    color: #fff !important;
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
.action-icons button:hover { color: #2c1b1b; }

#doctors-table th.actions {
    pointer-events: none;
    background-image: none !important;
}
</style>
@endpush

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0 fw-bold">Doctors List</h4>
    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#doctorModal" @cannot('add doctor') disabled @endcannot>
        <i class="bi bi-plus-circle me-1"></i> Add Doctor
    </button>
</div>

<table id="doctors-table" class="table table-sm align-middle">
    <thead>
        <tr>
            <th>Doctor</th>
            <th>Contact</th>
            <th>Address</th>
            <th class="text-center actions">Actions</th>
        </tr>
    </thead>
</table>

{{-- Add Doctor Modal --}}
<div class="modal fade" id="doctorModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-md modal-dialog-centered">
    <form id="addDoctorForm" action="{{ route('doctors.store') }}" method="POST" enctype="multipart/form-data">
      @csrf
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Add Doctor</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body row g-2">
          <div class="col-6">
            <label class="form-label">Name</label>
            <input type="text" name="name" class="form-control form-control-sm" required>
          </div>
          <div class="col-6">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control form-control-sm">
          </div>
          <div class="col-6">
            <label class="form-label">Phone</label>
            <input type="text" name="phone" class="form-control form-control-sm">
          </div>
          <div class="col-6">
            <label class="form-label">Address</label>
            <input type="text" name="address" class="form-control form-control-sm">
          </div>
          <div class="col-12">
            <label class="form-label">Image</label>
            <input type="file" name="image" id="add-image" class="form-control form-control-sm">
            <div class="mt-1 text-center">
              <img id="add-image-preview" src="" style="width:100px;height:100px;object-fit:cover;border-radius:0.25rem;display:none;">
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

{{-- View Doctor Modal --}}
<div class="modal fade" id="viewDoctorModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content shadow rounded-3">
      <div class="modal-header">
        <h5 class="modal-title fw-bold">Doctor Details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="row align-items-center">
          <div class="col-md-8">
            <div class="p-3">
              <p class="mb-2"><strong>Name:</strong> <span id="view-name"></span></p>
              <p class="mb-2"><strong>Email:</strong> <span id="view-email"></span></p>
              <p class="mb-2"><strong>Phone:</strong> <span id="view-phone"></span></p>
              <p class="mb-0"><strong>Address:</strong> <span id="view-address"></span></p>
            </div>
          </div>
          <div class="col-md-4 text-center">
            <div class="p-3">
              <img id="view-image" src=""
                   style="width:120px;height:120px;object-fit:cover;border-radius:0.5rem;display:none;">
              <p id="view-no-image" class="text-muted mt-2">No Image</p>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

{{-- Edit Doctor Modal --}}
<div class="modal fade" id="editDoctorModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-md modal-dialog-centered">
    <form id="editDoctorForm" method="POST" enctype="multipart/form-data">
      @csrf @method('PUT')
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Edit Doctor</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body row g-2">
          <div class="col-6">
            <label class="form-label">Name</label>
            <input type="text" name="name" id="edit-name" class="form-control form-control-sm" required>
          </div>
          <div class="col-6">
            <label class="form-label">Email</label>
            <input type="email" name="email" id="edit-email" class="form-control form-control-sm">
          </div>
          <div class="col-6">
            <label class="form-label">Phone</label>
            <input type="text" name="phone" id="edit-phone" class="form-control form-control-sm">
          </div>
          <div class="col-6">
            <label class="form-label">Address</label>
            <input type="text" name="address" id="edit-address" class="form-control form-control-sm">
          </div>
          <div class="col-12">
            <label class="form-label">Change Image</label>
            <input type="file" name="image" id="edit-image" class="form-control form-control-sm">
            <div class="mt-1 text-center">
              <img id="edit-image-preview" src="" style="width:100px;height:100px;object-fit:cover;border-radius:0.25rem;display:none;">
              <p id="edit-no-image">No Image</p>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary btn-sm">Update</button>
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
    var table = $('#doctors-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('doctors.index') }}",
        pageLength: 5,
        columns: [
            { data: 'doctor', name: 'doctor', render: function(data,type,row){
                if(type==='display'){
                    let img = data.image ? `<img src="${data.image}" alt="Doctor">` : `<div class="initials">${data.initials}</div>`;
                    return `<div class="doctor-cell">${img}<span>${data.name}</span></div>`;
                }
                return data.name;
            }},
            { data: 'contact', name: 'contact', render: function(data){
                let email = data.email ?? '—';
                let phone = data.phone ?? '—';
                return `<div><div>${email}</div><div class="text-muted small">${phone}</div></div>`;
            }},
            { data: 'address', name: 'address' },
            { data: 'actions', name: 'actions', orderable:false, searchable:false }
        ]
    });

    // Image preview Add
    $('#add-image').on('change',function(){
        const f=this.files[0];
        if(f){let r=new FileReader();r.onload=e=>$('#add-image-preview').attr('src',e.target.result).show();r.readAsDataURL(f);}
        else $('#add-image-preview').hide();
    });

    // Add Doctor
    $('#addDoctorForm').submit(function(e){
        e.preventDefault();
        $.ajax({
            url: $(this).attr('action'),
            method: 'POST',
            data: new FormData(this),
            processData:false,contentType:false,
            success: function(res){
                $('#doctorModal').modal('hide');
                $('body').removeClass('modal-open'); $('.modal-backdrop').remove();
                table.ajax.reload();
                Swal.fire('Added!','Doctor added.','success');
                $('#addDoctorForm')[0].reset();
                $('#add-image-preview').hide();
            },
            error: function(){ Swal.fire('Error!','Something went wrong.','error'); }
        });
    });

    // View Doctor
    $(document).on('click','.view-doctor',function(){
        let d=$(this).data('doctor');
        $('#view-name').text(d.name);
        $('#view-email').text(d.email??'—');
        $('#view-phone').text(d.phone??'—');
        $('#view-address').text(d.address??'—');
        if(d.image){ $('#view-image').attr('src',d.image).show(); $('#view-no-image').hide(); }
        else { $('#view-image').hide(); $('#view-no-image').show(); }
        $('#viewDoctorModal').modal('show');
    });

    // Edit Doctor
    $(document).on('click','.edit-doctor',function(){
        let d=$(this).data('doctor');
        $('#editDoctorForm').attr('action','/doctors/'+d.id);
        $('#edit-name').val(d.name);
        $('#edit-email').val(d.email);
        $('#edit-phone').val(d.phone);
        $('#edit-address').val(d.address);
        if(d.image){ $('#edit-image-preview').attr('src',d.image).show(); $('#edit-no-image').hide(); }
        else { $('#edit-image-preview').hide(); $('#edit-no-image').show(); }
        $('#editDoctorModal').modal('show');
    });

    // Image preview Edit
    $('#edit-image').on('change',function(){
        const f=this.files[0];
        if(f){let r=new FileReader();r.onload=e=>$('#edit-image-preview').attr('src',e.target.result).show();$('#edit-no-image').hide();r.readAsDataURL(f);}
        else{$('#edit-image-preview').hide();$('#edit-no-image').show();}
    });

    // Update Doctor
    $('#editDoctorForm').submit(function(e){
        e.preventDefault();
        $.ajax({
            url: $(this).attr('action'),
            method:'POST',
            data:new FormData(this),
            processData:false,contentType:false,
            success:function(){
                $('#editDoctorModal').modal('hide');
                $('body').removeClass('modal-open'); $('.modal-backdrop').remove();
                table.ajax.reload();
                Swal.fire('Updated!','Doctor updated.','success');
            },
            error:function(){ Swal.fire('Error!','Something went wrong.','error'); }
        });
    });

    // Delete Doctor
    $(document).on('click','.delete-doctor',function(e){
        e.preventDefault();
        let form=$(this).closest('form');
        Swal.fire({title:'Are you sure?',icon:'warning',showCancelButton:true}).then(r=>{
            if(r.isConfirmed){
                $.ajax({
                    url: form.attr('action'), type:'POST', data: form.serialize(),
                    success:()=>{ table.ajax.reload(); Swal.fire('Deleted!','Doctor deleted.','success'); },
                    error:()=> Swal.fire('Error!','Something went wrong.','error')
                });
            }
        });
    });
});
</script>
@endpush
