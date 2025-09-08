<div class="modal fade" id="editDoctorModal-{{ $doctor->id }}" tabindex="-1"
    aria-labelledby="editDoctorModalLabel-{{ $doctor->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title" id="editDoctorModalLabel-{{ $doctor->id }}">Edit Doctor</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('doctors.update', $doctor->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row mb-3">
                        <div class="col">
                            <label class="form-label">Name</label>
                            <input type="text" name="name" value="{{ old('name', $doctor->name) }}"
                                class="form-control" required>
                        </div>
                        <div class="col">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" value="{{ old('email', $doctor->email) }}"
                                class="form-control" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col">
                            <label class="form-label">Phone</label>
                            <input type="text" name="phone" value="{{ old('phone', $doctor->phone) }}"
                                class="form-control" required>
                        </div>
                        <div class="col">
                            <label class="form-label">Profile Picture</label>
                            <input type="file" name="image" class="form-control"
                                id="doctorImageInput-{{ $doctor->id }}">
                            @if ($doctor->image)
                                <img id="doctorImagePreview-{{ $doctor->id }}"
                                    src="{{ asset('storage/' . $doctor->image) }}" width="70" height="70"
                                    class="border rounded mt-2" style="object-fit:cover;">
                            @else
                                <img id="doctorImagePreview-{{ $doctor->id }}" src="" width="70"
                                    height="70" class="border rounded mt-2" style="object-fit:cover; display:none;">
                            @endif
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Address</label>
                        <input type="text" name="address" value="{{ old('address', $doctor->address) }}"
                            class="form-control" required>
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
document.addEventListener('DOMContentLoaded', function() {
    const input = document.getElementById('doctorImageInput-{{ $doctor->id }}');
    const preview = document.getElementById('doctorImagePreview-{{ $doctor->id }}');

    input.addEventListener('change', function(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = 'inline-block'; // show the image if hidden
            }
            reader.readAsDataURL(file);
        }
    });
});
</script>
