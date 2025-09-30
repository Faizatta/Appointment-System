<div class="modal fade" id="createUserModal" tabindex="-1" aria-labelledby="createUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="createUserModalLabel">
                    <i class="fas fa-user me-1"></i> Add New User
                </h5>
            </div>

            <form action="{{ route('users.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row">

                        {{-- Name --}}
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name" name="name" placeholder="ABCD"required>
                        </div>

                        {{-- Email --}}
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email"  placeholder="abc@gmail.com"required>
                        </div>

                        {{-- Phone --}}
                        <div class="col-md-6 mb-3">
                            <label for="phone" class="form-label">Phone Number</label>
                            <input type="text" class="form-control" id="phone" name="phone" placeholder="+92xxxxxxxxxx" required>
                        </div>

                        {{-- Password --}}
                        <div class="col-md-6 mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>

                        {{-- Confirm Password --}}
                        <div class="col-md-6 mb-3">
                            <label for="password_confirmation" class="form-label">Confirm Password</label>
                            <input type="password" class="form-control" id="password_confirmation"
                                   name="password_confirmation" required>
                        </div>

                        {{-- Roles --}}
                        <div class="col-md-12 mb-3">
                            <label class="form-label fw-semibold">Assign Roles</label>
                            <div class="d-flex flex-wrap gap-2">
                                @foreach ($roles->where('name', '!=', 'Admin') as $role)
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="roles[]"
                                               value="{{ $role->name }}" id="role{{ $role->id }}">
                                        <label class="form-check-label"
                                               for="role{{ $role->id }}">{{ $role->name }}</label>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                    </div>
                </div>

                <div class="modal-footer d-flex justify-content-end">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class=""></i> Create User
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>
