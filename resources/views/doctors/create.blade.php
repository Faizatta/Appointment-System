@extends('layouts.layout')

@section('content')

<div class="container mt-2 p-5 bg-light  rounded"
     style="min-height:500px; overflow:hidden;">

    <div class="text-center mb-4">
        <h3 class="fw-bold text-primary" style="font-size:26px;">Doctor Credentials</h3>
    </div>

    <form action="{{ route('doctors.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="row mb-4 align-items-center">

            <div class="col-md-6">
                <label for="name" class="form-label small">Name</label>
                <input type="text" name="name" id="name" class="form-control"
                       placeholder="Enter your name" required>
            </div>

            <div class="col-md-6 d-flex flex-column align-items-start">
                <label for="image" class="form-label small">Profile Picture</label>
                <input type="file" name="image" id="image" class="form-control mb-2"
                       accept="image/*" required>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-6">
                <label for="email" class="form-label small">Gmail</label>
                <input type="email" name="email" id="email" class="form-control"
                       placeholder="Enter your Gmail" required>
            </div>

            <div class="col-md-6">
                <label for="phone" class="form-label small">Phone</label>
                <input type="tel" name="phone" id="phone" class="form-control"
                       placeholder="Enter your phone number" required>
            </div>
        </div>

        <div class="mb-4">
            <label for="address" class="form-label small">Address</label>
            <input type="text" name="address" id="address" class="form-control"
                   placeholder="Enter Address" required>
        </div>


        <div class="text-center">
            <button type="submit" class="btn btn-primary fw-bold px-4 py-2"
                    style="font-size:13px;">Submit</button>
        </div>

    </form>

</div>

@endsection

