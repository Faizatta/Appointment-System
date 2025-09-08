@extends('layouts.layout')

@section('content')
    <div class="container mt-4">
        <div class="row justify-content-start">

            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card border-0 shadow-sm rounded-3 bg-slate-200">
                    <div class="card-body text-center p-3">
                        <h6 class="card-title mb-2 fw-bold text-secondary">Doctors</h6>
                        <a href="{{ url('doctors') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3">View</a>
                    </div>
                </div>

            </div>


            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card border-0 shadow-sm rounded-3 bg-slate-200">
                    <div class="card-body text-center p-3">
                        <h6 class="card-title mb-2 fw-bold text-secondary">Patients</h6>
                        <a href="{{ url('patients') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3">View</a>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
