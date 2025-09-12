<div class="modal fade" id="createRoleModal" tabindex="-1" aria-labelledby="createRoleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-md">
    <div class="modal-content">
      <form action="{{ route('roles.storerole') }}" method="POST">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title fw-bold" id="createRoleModalLabel">Create Role</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">
          <div class="mb-3">
            <label for="role_name" class="form-label">Role Name</label>
            <input type="text" name="name" id="role_name" class="form-control" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Assign Permissions</label>
            <div class="d-flex flex-wrap">
              @foreach($permissions as $permission)
                <div class="form-check me-3">
                  <input class="form-check-input" type="checkbox" name="permissions[]" value="{{ $permission->name }}" id="perm_{{ $permission->id }}">
                  <label class="form-check-label" for="perm_{{ $permission->id }}">
                    {{ $permission->name }}
                  </label>
                </div>
              @endforeach
            </div>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Create Role</button>
        </div>
      </form>
    </div>
  </div>
</div>



@push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            $('#permissionsSelect').selectpicker();
        });
    </script>
@endpush
<style>
    .bootstrap-select .dropdown-menu.inner li a {
        color: #000 !important;
        font-size: 0.875rem;
        padding: 4px 10px;
    }


    .bootstrap-select .dropdown-menu.inner {
        max-height: 120px !important;
        overflow-y: auto;
    }
</style>
