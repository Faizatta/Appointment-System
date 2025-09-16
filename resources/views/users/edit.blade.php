<div class="modal fade" id="editUserModal{{ $user->id }}" tabindex="-1"
    aria-labelledby="editUserModalLabel{{ $user->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-s">
        <div class="modal-content border-0 shadow">
            <div class="modal-header">
                <h6 class="modal-title fw-bold" id="editUserModalLabel{{ $user->id }}">
                    Edit User
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form action="{{ route('users.update', $user->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="modal-body">


                    <div class="mb-2">
                        <label class="form-label">Name</label>
                        <input type="text" name="name" value="{{ $user->name }}"
                            class="form-control form-control-sm" required>
                    </div>


                    <div class="mb-2">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" value="{{ $user->email }}"
                            class="form-control form-control-sm" required>
                    </div>


                    <div class="mb-2">
                        <label class="form-label">Assign Roles</label>
                        @foreach ($roles->where('name', '!=', 'admin') as $role)
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="roles[]"
                                    value="{{ $role->name }}"
                                    {{ $user->roles->contains('name', $role->name) ? 'checked' : '' }}>
                                <label class="form-check-label">{{ $role->name }}</label>
                            </div>
                        @endforeach
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-sm">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
