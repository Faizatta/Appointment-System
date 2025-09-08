@extends('layouts.layout')

@section('content')
    <div class="container mt-4">
        <h3>Edit Patient</h3>
        <form action="{{ route('patients.update', $patient->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="mb-2">
                <label>Name</label>
                <input type="text" name="name" value="{{ $patient->name }}" class="form-control" required>
            </div>

            <div class="mb-2">
                <label>Email</label>
                <input type="email" name="mail" value="{{ $patient->mail }}" class="form-control" required>
            </div>

            <div class="mb-2">
                <label>Phone</label>
                <input type="text" name="phone" value="{{ $patient->phone }}" class="form-control" required>
            </div>

            <div class="mb-2">
                <label>Address</label>
                <input type="text" name="address" value="{{ $patient->address }}" class="form-control">
            </div>


            <div class="mb-2">
                <label>Assign Doctor</label>
                <select name="doctor_id" class="form-select" required>
                    <option value="">-- Select Doctor --</option>
                    @foreach ($doctors as $doctor)
                        <option value="{{ $doctor->id }}" {{ $patient->doctor_id == $doctor->id ? 'selected' : '' }}>
                            {{ $doctor->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-2">
                <label>Image</label><br>
                @if ($patient->image)
                    <img src="{{ asset('storage/' . $patient->image) }}" width="70" height="70" class="rounded mb-2">
                @endif
                <input type="file" name="image" class="form-control">
            </div>

            <button class="btn btn-success">Update</button>
            <a href="{{ route('patients.index') }}" class="btn btn-secondary">Back</a>
        </form>
    </div>
@endsection


@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @if (session('success'))
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: "{{ session('success') }}",
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'OK'
                });
            });
        </script>
    @endif

    @if ($errors->any())
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                let errorMessages = `
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    `;
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    html: errorMessages,
                    confirmButtonColor: '#d33',
                    confirmButtonText: 'Try Again'
                });
            });
        </script>
    @endif
@endsection
