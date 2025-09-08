@extends('layouts.layout')

@section('content')
    <div class="container mt-4">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-dark text-white">
                <h4 class="mb-0">Doctor Details</h4>
            </div>
            <div class="card-body">
                <div class="row align-items-center">
                    <!-- Doctor Info -->
                    <div class="col-md-8">
                        <p class="mb-3"><strong>Name:</strong> {{ $doctor->name }}</p>
                        <p class="mb-3"><strong>Email:</strong> {{ $doctor->email }}</p>
                        <p class="mb-3"><strong>Phone:</strong> {{ $doctor->phone }}</p>
                        <p class="mb-3"><strong>Address:</strong> {{ $doctor->address }}</p>

                        <p class="mb-3"><strong>Patients:</strong></p>
                        <ul>
                            @forelse ($doctor->patients as $patient)
                                <li>{{ $patient->name }}</li>
                            @empty
                                <li>No patients assigned.</li>
                            @endforelse
                        </ul>
                    </div>

                    <!-- Doctor Image -->
                    <div class="col-md-4 text-center">
                        @if ($doctor->image)
                            <img src="{{ asset('storage/' . $doctor->image) }}" width="140" height="140"
                                class="border rounded" style="object-fit:cover;">
                        @else
                            <p class="text-muted">No Image</p>
                        @endif
                    </div>
                </div>

                <div class="mt-4 d-flex justify-content-center gap-3">
                    <a href="{{ route('doctors.index') }}" class="btn btn-secondary px-4">Back</a>
                    <a href="{{ route('doctors.edit', $doctor->id) }}" class="btn btn-primary px-4">Edit</a>
                </div>
            </div>
        </div>
    </div>
@endsection
