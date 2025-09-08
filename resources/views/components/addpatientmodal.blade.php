<!-- Add Patient Modal -->
<div class="modal fade" id="patientModal" tabindex="-1" aria-labelledby="patientModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-dark text-white">
        <h5 class="modal-title" id="patientModalLabel">Add Patient</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <form action="{{ route('patients.store') }}" method="POST" enctype="multipart/form-data">
          @csrf

          <div class="row mb-3">
            <div class="col">
              <label class="form-label">Name</label>
              <input type="text" name="name" value="{{ old('name') }}" class="form-control" required>
            </div>
            <div class="col">
              <label class="form-label">Email</label>
              <input type="email" name="mail" value="{{ old('mail') }}" class="form-control" required>
            </div>
          </div>

          <div class="row mb-3">
            <div class="col">
              <label class="form-label">Phone</label>
              <input type="text" name="phone" value="{{ old('phone') }}" class="form-control" required>
            </div>
            <div class="col">
              <label class="form-label">Address</label>
              <input type="text" name="address" value="{{ old('address') }}" class="form-control">
            </div>
          </div>

          <div class="row mb-3">
            <div class="col">
              <label class="form-label">Profile Picture</label>
              <input type="file" name="image" class="form-control">
            </div>
            <div class="col">
              <label class="form-label">Assign Doctor</label>
              <select name="doctor_id" class="form-select">
                <option value="">-- Select Doctor --</option>
                @foreach ($doctors as $doctor)
                  <option value="{{ $doctor->id }}" {{ old('doctor_id') == $doctor->id ? 'selected' : '' }}>
                    {{ $doctor->name }}
                  </option>
                @endforeach
              </select>
            </div>
          </div>

          <div class="text-end">
            <button type="submit" class="btn btn-primary">Add</button>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
