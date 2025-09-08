<div class="modal fade" id="doctorModal" tabindex="-1" aria-labelledby="doctorModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg"> <!-- Centered and Large -->
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title fw-bold " id="doctorModalLabel ">Add Doctor</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="{{ route('doctors.store') }}" method="POST" enctype="multipart/form-data">
          @csrf

          <div class="row mb-3">
            <div class="col">
              <label for="name" class="form-label">Name</label>
              <input type="text" class="form-control" name="name" id="name" required>
            </div>
            <div class="col">
              <label for="email" class="form-label">Email</label>
              <input type="email" class="form-control" name="email" id="email" required>
            </div>
          </div>

          <div class="row mb-3">
            <div class="col">
              <label for="phone" class="form-label">Phone</label>
              <input type="tel" class="form-control" name="phone" id="phone" required>
            </div>
            <div class="col">
              <label for="image" class="form-label">Profile Picture</label>
              <input type="file" class="form-control" name="image" id="image" accept="image/*">
            </div>
          </div>

          <div class="row mb-3">
            <div class="col-12">
              <label for="address" class="form-label">Address</label>
              <input type="text" class="form-control" name="address" id="address" required>
            </div>
          </div>

          <div class="text-end">
            <button type="submit" class="btn btn-primary">Submit</button>
          </div>

        </form>
      </div>
    </div>
  </div>
</div>

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {

    const deleteButtons = document.querySelectorAll('.delete-doctor');
    deleteButtons.forEach(button => {
        button.addEventListener('click', function() {
            const form = this.closest('.delete-form');
            Swal.fire({
                title: 'Are you sure?',
                text: "This action cannot be undone!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });

    @if (session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: "{{ session('success') }}",
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'OK'
        });
    @endif

});
</script>
@endsection
