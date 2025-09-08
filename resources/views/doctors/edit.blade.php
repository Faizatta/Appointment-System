{{-- @extends('layouts.layout')

@section('content')
    <div class="container mt-2">
        <div class="card  border-0 rounded">
            <div class="card-header bg-dark text-white">
                <h4 class="mb-0">Edit Doctor</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('doctors.update', $doctor->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" name="name" value="{{ $doctor->name }}" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" value="{{ $doctor->email }}" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Phone</label>
                        <input type="text" name="phone" value="{{ $doctor->phone }}" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Address</label>
                        <input type="text" name="address" value="{{ $doctor->address }}" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Profile Picture</label>
                        <input type="file" name="image" class="form-control">
                        @if ($doctor->image)
                            <img src="{{ asset('storage/' . $doctor->image) }}" width="70" height="70"
                                class="border rounded" style="object-fit:cover; margin-top: 12px;">
                        @endif
                    </div>

                    <div class="text-end">
                        <button type="submit" class="btn btn-success">Update</button>
                        <a href="{{ route('doctors.index') }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection --}}

<div class="modal fade" id="editDoctorModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-dark text-white">
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
              <input type="text" name="name" id="editName" class="form-control" required>
            </div>
            <div class="col">
              <label class="form-label">Email</label>
              <input type="email" name="email" id="editEmail" class="form-control" required>
            </div>
          </div>

          <div class="row mb-3">
            <div class="col">
              <label class="form-label">Phone</label>
              <input type="text" name="phone" id="editPhone" class="form-control" required>
            </div>
            <div class="col">
              <label class="form-label">Profile Picture</label>
              <input type="file" name="image" class="form-control">
              <img id="editImagePreview" width="70" height="70" class="border rounded mt-2 d-none">
            </div>
          </div>

          <div class="mb-3">
            <label class="form-label">Address</label>
            <input type="text" name="address" id="editAddress" class="form-control" required>
          </div>

          <div class="text-end">
            <button type="submit" class="btn btn-success">Update</button>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<script>
document.addEventListener("DOMContentLoaded", function() {
    const editBtns = document.querySelectorAll(".editBtn");
    const form = document.getElementById("editDoctorForm");

    editBtns.forEach(btn => {
        btn.addEventListener("click", function() {
            let id = this.dataset.id;
            let name = this.dataset.name;
            let email = this.dataset.email;
            let phone = this.dataset.phone;
            let address = this.dataset.address;
            let image = this.dataset.image;


            document.getElementById("editName").value = name;
            document.getElementById("editEmail").value = email;
            document.getElementById("editPhone").value = phone;
            document.getElementById("editAddress").value = address;


            let preview = document.getElementById("editImagePreview");
            if(image){
                preview.src = image;
                preview.classList.remove("d-none");
            } else {
                preview.classList.add("d-none");
            }

            form.action = `/doctors/${id}`;
        });
    });
});
</script>
