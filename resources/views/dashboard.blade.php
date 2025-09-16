@extends('layouts.layout')

@section('content')
    <div class="container mt-2">
        <div class="row g-3">


            <div class="col-auto">
                <div class="card border rounded-3 bg-light" style="width: 200px; min-height: 100px;">
                    <div class="card-body p-2 d-flex flex-column justify-content-between">

                        <div class="d-flex justify-content-between align-items-start">
                            <h6 class="fw-semibold text-muted mb-0">
                                <i class="fas fa-user-md me-1 text-dark"></i> Doctors
                            </h6>
                            <span class="badge bg-light text-dark border">
                                {{ $doctorCount ?? 0 }}
                            </span>
                        </div>

                        <div class="text-center mt-2">
                            @if (auth()->user()->hasRole('admin') ||
                                    auth()->user()->canany(['view doctor', 'create doctor', 'edit doctor', 'delete doctor']))
                                <a href="{{ url('doctors') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
                                    View
                                </a>
                            @else
                                <button class="btn btn-outline-secondary btn-sm rounded-pill px-3" disabled
                                    title="No permission">
                                    View
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>


            <div class="col-auto">
                <div class="card border rounded-3 bg-light" style="width: 200px; min-height: 100px;">
                    <div class="card-body p-2 d-flex flex-column justify-content-between">

                        <div class="d-flex justify-content-between align-items-start">
                            <h6 class="fw-semibold text-muted mb-0">
                                <i class="fas fa-users me-1 text-dark"></i> Patients
                            </h6>
                            <span class="badge bg-light text-dark border">
                                {{ $patientCount ?? 0 }}
                            </span>
                        </div>

                        <div class="text-center mt-2">
                            @if (auth()->user()->hasRole('admin') ||
                                    auth()->user()->canany(['view patient', 'create patient', 'edit patient', 'delete patient']))
                                <a href="{{ url('patients') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
                                    View
                                </a>
                            @else
                                <button class="btn btn-outline-secondary btn-sm rounded-pill px-3" disabled
                                    title="No permission">
                                    View
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
