@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Create New Role</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('roles.storerole') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="name" class="form-label">Role Name</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Assign Permissions</label>
            <div>
                @foreach($permissions as $permission)
                    <div class="form-check form-check-inline">
                        <input type="checkbox" class="form-check-input" name="permissions[]" value="{{ $permission->name }}" id="perm_{{ $permission->id }}">
                        <label class="form-check-label" for="perm_{{ $permission->id }}">{{ $permission->name }}</label>
                    </div>
                @endforeach
            </div>
        </div>

        <button type="submit" class="btn btn-primary">Create Role</button>
    </form>
</div>
@endsection
