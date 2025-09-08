@extends('layouts.layout')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body text-center p-3">
                    <h6 class="card-title mb-2 fw-bold text-primary">Doctors</h6>
                    <a href="{{ url('doctors') }}" class="btn btn-outline-primary btn-sm rounded-pill px-3">View</a>
                </div>
            </div>
        </div>


        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body text-center p-3">
                    <h6 class="card-title mb-2 fw-bold text-success">Patients</h6>
                    <a href="{{ url('patients') }}" class="btn btn-outline-success btn-sm rounded-pill px-3">View</a>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
