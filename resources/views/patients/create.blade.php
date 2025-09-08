@extends('layouts.layout')

@section('content')
    <div class="container mt-4">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-dark text-white">
                <h4 class="mb-0">Add Patient</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('patients.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row g-3">


                        <div class="col-md-6">
                            <label class="form-label">Name</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>


                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input type="email" name="mail" class="form-control" required>
                        </div>


                        <div class="col-md-6">
                            <label class="form-label">Phone</label>
                            <input type="text" name="phone" class="form-control" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Address</label>
                            <input type="text" name="address" class="form-control">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Image</label>
                            <input type="file" name="image" class="form-control">
                        </div>


                        <div class="col-md-6">
                            <label class="form-label">Assign Doctor</label>
                            <select name="doctor_id" class="form-select" required>
                                <option value="">-- Select Doctor --</option>
                                @foreach ($doctors as $doctor)
                                    <option value="{{ $doctor->id }}">{{ $doctor->name }}</option>
                                @endforeach
                            </select>
                        </div>

                    </div>


                    <div class="mt-4 d-flex gap-2">
                        <button class="btn btn-success">Save</button>
                        <a href="{{ route('patients.index') }}" class="btn btn-secondary">Back</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
