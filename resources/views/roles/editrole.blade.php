<div class="modal fade" id="editRoleModal{{ $role->id }}" tabindex="-1"
     aria-labelledby="editRoleModalLabel{{ $role->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 500px;"> <!-- Centered + smaller width -->
        <div class="modal-content">

            <div class="modal-header py-2">
                <h6 class="modal-title">Edit Role</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form action="{{ route('roles.updaterole', $role->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body p-2">

                    <div class="mb-2">
                        <label class="form-label small">Role Name</label>
                        <input type="text"
                               name="name"
                               class="form-control form-control-sm"
                               value="{{ $role->name }}"
                               required
                               {{ strtolower($role->name) == 'admin' ? 'readonly' : '' }}>
                    </div>

                    <div class="mb-2">
                        <label class="form-label small">Permissions</label>
                        <div class="row g-1">
                            @foreach($permissions as $permission)
                                <div class="col-4">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input"
                                               name="permissions[]"
                                               value="{{ $permission->id }}"
                                               id="edit_perm_{{ $permission->id }}"
                                               {{ $role->permissions->contains('id', $permission->id) ? 'checked' : '' }}>
                                        <label class="form-check-label small" for="edit_perm_{{ $permission->id }}">
                                            {{ $permission->name }}
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <small class="text-muted">Select multiple permissions</small>
                    </div>

                </div>
                <div class="modal-footer py-2">
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-sm btn-primary">Update</button>
                </div>
            </form>

        </div>
    </div>
</div>
