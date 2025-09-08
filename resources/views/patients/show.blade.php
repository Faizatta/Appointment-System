@extends('layouts.layout')

@section('content')
    <div class="container mt-4">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-dark text-white">
                <h4 class="mb-0">Patient Details</h4>
            </div>
            <div class="card-body">
                <div class="row align-items-center">

                    <div class="col-md-8">
                        <p class="mb-3"><strong>Name:</strong> {{ $patient->name }}</p>
                        <p class="mb-3"><strong>Email:</strong> {{ $patient->mail }}</p>
                        <p class="mb-3"><strong>Phone:</strong> {{ $patient->phone }}</p>
                        <p class="mb-3"><strong>Address:</strong> {{ $patient->address }}</p>
                        <p class="mb-3"><strong>Doctor:</strong>
                            {{ $patient->doctor ? $patient->doctor->name : 'No Doctor' }}</p>
                    </div>


                    <div class="col-md-4 text-center">
                        @if ($patient->image)
                            <img src="{{ asset('storage/' . $patient->image) }}" width="140" height="140"
                                class="border rounded" style="object-fit:cover;">
                        @else
                            <p class="text-muted">No Image</p>
                        @endif
                    </div>
                </div>


                <div class="mt-4 d-flex justify-content-center gap-3">
                    <a href="{{ route('patients.index') }}" class="btn btn-secondary px-4">Back</a>
                    <a href="{{ route('patients.edit', $patient->id) }}" class="btn btn-primary px-4">Edit</a>
                </div>
            </div>
        </div>
    </div>
@endsection
