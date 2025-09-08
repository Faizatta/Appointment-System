@extends('layouts.layout')

@section('content')
    <div class="pt-2">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="h5 fw-bold mb-0 text-start">Doctors</h2>
            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#doctorModal">
                Add Doctor
            </button>
        </div>

   <div class="table-responsive rounded">
    <table class="table mb-0" style="border-collapse: collapse;">
        <thead class="table-light">
            <tr>
                <th class="text-start" style="padding:10px; ">Doctor</th>
                <th class="text-start" style="padding:10px; ">Address</th>
                <th class="text-start" style="padding:10px">Contact</th>
                <th class="text-start" style="padding:10px">Patients</th>
                <th class="text-start" style="padding:10px; width:110px">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($doctors as $doctor)
                <tr>
                    <!-- Doctor + Image -->
                    <td class="d-flex align-items-center gap-2" style="padding:10px;  white-space: nowrap;">
                        @if ($doctor->image)
                            <img src="{{ asset('storage/' . $doctor->image) }}" alt="{{ $doctor->name }}"
                                 class="rounded-circle border border-secondary shadow-sm"
                                 style="width:45px; height:45px; object-fit: cover;">
                        @else
                            <div class="rounded-circle bg-secondary d-flex justify-content-center align-items-center"
                                 style="width:45px; height:45px; font-size:14px; font-weight:bold; color:white;">
                                {{ strtoupper(substr($doctor->name, 0, 1)) ?? 'N/A' }}
                            </div>
                        @endif
                        <span class="fw-semibold text-dark" style="line-height:1; vertical-align: middle;">
                            {{ $doctor->name }}
                        </span>
                    </td>

                    <!-- Address -->
                    <td class="fw-semibold text-dark align-middle" style="padding:10px; ">
                        {{ $doctor->address }}
                    </td>

                    <!-- Contact -->
                    <td class="align-middle" style="padding:10px; ">
                        <div class="fw-semibold text-dark">{{ $doctor->email }}</div>
                        <div class="text-secondary small fw-medium">{{ $doctor->phone }}</div>
                    </td>

                    <!-- Patients -->
                    <td class="fw-semibold text-dark align-middle" style="padding:10px; ">
                        @if ($doctor->patients->count())
                            {{ $doctor->patients->pluck('name')->implode(', ') }}
                        @else
                            <span class="text-dark">No patients</span>
                        @endif
                    </td>

                    <!-- Actions -->
                    <td class="text-start align-middle" style="padding:10px; ">
                        <div class="d-flex align-items-center gap-2">
                            <a href="#" class="text-dark d-flex align-items-center" title="View" data-bs-toggle="modal" data-bs-target="#viewDoctorModal-{{ $doctor->id }}">
                                <i class="bi bi-eye-fill fs-6"></i>
                            </a>
                            <a href="#" class="text-dark d-flex align-items-center" title="Edit" data-bs-toggle="modal" data-bs-target="#editDoctorModal-{{ $doctor->id }}">
                                <i class="bi bi-pencil-square fs-6"></i>
                            </a>
                            <form action="{{ route('doctors.destroy', $doctor->id) }}" method="POST" class="d-inline delete-form">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn btn-link text-dark p-0 m-0 d-flex align-items-center delete-doctor" title="Delete">
                                    <i class="bi bi-trash-fill fs-6"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center text-dark" style="padding:10px; border:1px solid #dee2e6;">No doctors found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

    </div>

    @foreach ($doctors as $doctor)
        @include('components.viewdoctormodal', ['doctor' => $doctor])
        @include('components.editdoctormodal', ['doctor' => $doctor])
    @endforeach

    @include('components.adddoctormodal')
@endsection
