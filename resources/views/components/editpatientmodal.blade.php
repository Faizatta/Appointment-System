<div class="modal fade" id="editPatientModal-{{ $patient->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-sm">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title">Edit Patient</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('patients.update', $patient->id) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Name</label>
                            <input type="text" name="name" class="form-control"
                                value="{{ old('name', $patient->name) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input type="email" name="mail" class="form-control"
                                value="{{ old('mail', $patient->mail) }}" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Phone</label>
                            <input type="text" name="phone" class="form-control"
                                value="{{ old('phone', $patient->phone) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Address</label>
                            <input type="text" name="address" class="form-control"
                                value="{{ old('address', $patient->address) }}">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Assign Doctor</label>
                            <select name="doctor_id" class="form-select">
                                <option value="">Select Doctor</option>
                                @foreach ($doctors as $doctor)
                                    <option value="{{ $doctor->id }}"
                                        {{ $patient->doctor_id == $doctor->id ? 'selected' : '' }}>
                                        {{ $doctor->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Profile Picture</label>
                            <input type="file" name="image" class="form-control"
                                id="patientImageInput-{{ $patient->id }}">

                            @if ($patient->image)
                                <img id="patientImagePreview-{{ $patient->id }}"
                                    src="{{ asset('storage/' . $patient->image) }}" width="70" height="70"
                                    class="rounded border mt-2" style="object-fit:cover;">
                            @else
                                <img id="patientImagePreview-{{ $patient->id }}" width="70" height="70"
                                    class="rounded border mt-2" style="object-fit:cover; display:none;">
                            @endif
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <button type="submit" class="btn btn-success">Update</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const input = document.getElementById('patientImageInput-{{ $patient->id }}');
    const preview = document.getElementById('patientImagePreview-{{ $patient->id }}');

    input.addEventListener('change', function(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = 'inline-block';
            }
            reader.readAsDataURL(file);
        }
    });
});
</script>
