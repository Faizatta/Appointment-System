@extends('layouts.layout')

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<style>
/* Table */
#doctors-table {
    font-size: 0.875rem;
    width: 100%;
    border-collapse: collapse;
    border: none;
}
#doctors-table th, #doctors-table td {
    vertical-align: middle;
    border: none;
}

/* Doctor cell */
.doctor-cell {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}
.doctor-cell img, .doctor-cell .initials {
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

/* Header alignment */
#doctors-table th.text-center {
    text-align: center !important; /* center header text */
}

/* Action icons */
.action-icons {
    display: flex;
    justify-content: flex-start; /* left align inside cell */
    align-items: flex-start;     /* align icons to top */
    gap: 3px;
    /* width: 32px; */
    padding: 0;
}
.action-icons a,
.action-icons button {
    display: flex;
    align-items: center;
    justify-content: center;
    /* width: 28px; */
    height: 28px;
    padding: 0;
    font-size: 1rem;
    background: transparent !important;
    border: none;
    color: #000;
    cursor: pointer;
}
.action-icons a:hover,
.action-icons a:focus,
.action-icons button:hover,
.action-icons button:focus {
    background: transparent !important;
    color: #000;
    box-shadow: none !important;
}

/* Make table cells vertically centered */
#doctors-table td {
    vertical-align: middle !important;
}

/* DataTables input sizes */
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
</style>
@endpush

@section('content')
<div class="container mt-2" style="max-width: 1000px;">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0 fw-bold">Doctors List</h4>
        @can('add doctor')
        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#doctorModal">
            <i class="bi bi-plus-circle me-1"></i> Add Doctor
        </button>
        @endcan
    </div>

    <table id="doctors-table" class="table table-sm align-middle">
        <thead>
            <tr>
                <th>Doctor</th>
                <th>Address</th>
                <th>Contact</th>
                <th>Patients</th>
                <th class="text-center">Actions</th>
            </tr>
        </thead>
    </table>
</div>

@can('add doctor')
@include('components.adddoctormodal', ['doctor' => null])
@endcan

<!-- Edit Doctor Modal -->
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
            <div class="col"><label class="form-label">Name</label><input type="text" id="editName" name="name" class="form-control form-control-sm" required></div>
            <div class="col"><label class="form-label">Email</label><input type="email" id="editEmail" name="email" class="form-control form-control-sm" required></div>
          </div>
          <div class="row mb-3">
            <div class="col"><label class="form-label">Phone</label><input type="text" id="editPhone" name="phone" class="form-control form-control-sm" required></div>
            <div class="col">
                <label class="form-label">Profile Picture</label>
                <input type="file" name="image" class="form-control form-control-sm">
                <img id="editImagePreview" width="70" height="70" class="border rounded mt-2 d-none">
            </div>
          </div>
          <div class="mb-3"><label class="form-label">Address</label><input type="text" id="editAddress" name="address" class="form-control form-control-sm" required></div>
          <div class="text-end">
            <button type="submit" class="btn btn-success btn-sm">Update</button>
            <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- View Doctor Modal -->
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
                <p><strong>Name:</strong> <span id="viewName"></span></p>
                <p><strong>Email:</strong> <span id="viewEmail"></span></p>
                <p><strong>Phone:</strong> <span id="viewPhone"></span></p>
                <p><strong>Address:</strong> <span id="viewAddress"></span></p>
                <p><strong>Patients:</strong></p>
                <ul id="viewPatients"></ul>
            </div>
            <div class="col-md-4 text-center">
                <img id="viewImage" width="140" height="140" class="border rounded" style="object-fit:cover;">
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
    var table = $('#doctors-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('doctors.index') }}",
        pageLength: 5,
        lengthMenu: [5,10,25],
        columns: [
            {
                data: 'doctor',
                name: 'doctor',
                render: function(data,type,row){
                    if(type === 'display'){
                        if(row.image){
                            return `<div class="doctor-cell">
                                        <img src="${row.image}" alt="Doctor">
                                        <span>${data}</span>
                                    </div>`;
                        } else {
                            return `<div class="doctor-cell">
                                        <div class="initials">${row.initials}</div>
                                        <span>${data}</span>
                                    </div>`;
                        }
                    }
                    return data;
                }
            },
            { data:'address', name:'address' },
            {
                data:'contact.email',
                name:'contact.email',
                render: function(data,type,row){
                    if(type === 'display'){
                        return `<div>${data}<br><span class="text-secondary">${row.contact.phone}</span></div>`;
                    }
                    return data;
                }
            },
            {
                data:'patients',
                name:'patients',
                render: function(data,type,row){
                    if(type === 'display'){
                        return data.length ? data.join(', ') : '<span class="text-secondary">No patients</span>';
                    }
                    return data.length;
                }
            },
            {
                data:'actions',
                name:'actions',
                orderable:false,
                searchable:false,
                className:'action-icons'
            }
        ],
        dom: '<"d-flex justify-content-between mb-2"lfr>rt<"d-flex justify-content-end mt-1"p>',
        language: {
            paginate: { previous:"&laquo;", next:"&raquo;" },
            lengthMenu: "_MENU_",
            searchPlaceholder: "Search doctors..."
        }
    });

    // Delete doctor
    $(document).on('click','.delete-doctor',function(e){
        e.preventDefault();
        let btn = $(this);
        let form = btn.closest('form');
        Swal.fire({
            title:'Are you sure?',
            text:"This action cannot be undone!",
            icon:'warning',
            showCancelButton:true,
            confirmButtonColor:'#d33',
            cancelButtonColor:'#3085d6',
            confirmButtonText:'Yes, delete it',
            cancelButtonText:'Cancel'
        }).then((result)=>{
            if(result.isConfirmed){
                $.ajax({
                    url: form.attr('action'),
                    type: 'POST',
                    data: form.serialize(),
                    success: function(){
                        table.ajax.reload(null,false);
                        Swal.fire('Deleted!','Doctor has been deleted.','success');
                    },
                    error: function(){
                        Swal.fire('Error!','Something went wrong.','error');
                    }
                });
            }
        });
    });

    // Edit doctor
    $(document).on('click','.editDoctor',function(){
        let btn=$(this);
        $('#editName').val(btn.data('name'));
        $('#editEmail').val(btn.data('email'));
        $('#editPhone').val(btn.data('phone'));
        $('#editAddress').val(btn.data('address'));
        let img=btn.data('image');
        if(img){$('#editImagePreview').attr('src',img).removeClass('d-none');}
        else{$('#editImagePreview').addClass('d-none');}
        $('#editDoctorForm').attr('action','/doctors/'+btn.data('id'));
        new bootstrap.Modal(document.getElementById('editDoctorModal')).show();
    });

    // View doctor
    $(document).on('click','.viewDoctor',function(){
        let btn=$(this);
        $('#viewName').text(btn.data('name'));
        $('#viewEmail').text(btn.data('email'));
        $('#viewPhone').text(btn.data('phone'));
        $('#viewAddress').text(btn.data('address'));
        let patientsList=$('#viewPatients'); patientsList.empty();
        let patients=btn.data('patients');
        if(patients){
            try{JSON.parse(patients).forEach(p=>patientsList.append('<li>'+p+'</li>')); }
            catch(e){patientsList.append('<li>No patients</li>');}
        } else { patientsList.append('<li>No patients</li>'); }
        let img=btn.data('image');
        if(img){$('#viewImage').attr('src',img).removeClass('d-none');}
        else{$('#viewImage').attr('src','{{ asset("default-avatar.png") }}').removeClass('d-none');}
        new bootstrap.Modal(document.getElementById('viewDoctorModal')).show();
    });
});
</script>
@endpush
