<div class="modal fade" id="editDoctorModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header">
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
              <input type="text" id="editName" name="name" class="form-control form-control-sm" required>
            </div>
            <div class="col">
              <label class="form-label">Email</label>
              <input type="email" id="editEmail" name="email" class="form-control form-control-sm" required>
            </div>
          </div>
          <div class="row mb-3">
            <div class="col">
              <label class="form-label">Phone</label>
              <input type="text" id="editPhone" name="phone" class="form-control form-control-sm" required>
            </div>
            <div class="col">
              <label class="form-label">Profile Picture</label>
              <input type="file" name="image" class="form-control form-control-sm">
              <img id="editImagePreview" width="70" height="70" class="border rounded mt-2 d-none">
            </div>
          </div>
          <div class="mb-3">
            <label class="form-label">Address</label>
            <input type="text" id="editAddress" name="address" class="form-control form-control-sm" required>
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
