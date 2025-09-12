@extends('layouts.layout')

@section('content')
<div class="p-2 pt-1">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="text-xl fw-bold mb-0">Patients</h2>

        {{-- Add Patient Button --}}
        @if(auth()->user()->can('add patient'))
            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#patientModal">
                <i class="bi bi-plus-circle me-1"></i> Add Patient
            </button>
        @else
            <button type="button" class="btn btn-primary btn-sm" disabled title="No permission">
                <i class="bi bi-plus-circle me-1"></i> Add Patient
            </button>
        @endif
    </div>

    <div class="table-responsive rounded">
        <table class="table mb-0" style="border-collapse: collapse;">
            <thead class="table-light">
                <tr>
                    <th class="text-start" style="padding:10px;">Name</th>
                    <th class="text-start" style="padding:10px;">Doctor</th>
                    <th class="text-start" style="padding:10px;">Address</th>
                    <th class="text-start" style="padding:10px;">Contact</th>
                    <th class="text-start" style="padding:10px; width:110px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($patients as $patient)
                    <tr>

                        {{-- Patient Image + Name --}}
                        <td class="d-flex align-items-center gap-2" style="padding:10px; white-space: nowrap;">
                            @if ($patient->image)
                                <img src="{{ asset('storage/' . $patient->image) }}?{{ time() }}"
                                     alt="{{ $patient->name }}"
                                     class="rounded-circle border border-secondary shadow-sm"
                                     style="width:45px; height:45px; object-fit: cover;">
                            @else
                                <div class="rounded-circle bg-secondary d-flex justify-content-center align-items-center"
                                     style="width:45px; height:45px; font-size:14px; font-weight:bold; color:white;">
                                    {{ strtoupper(substr($patient->name, 0, 1)) }}
                                </div>
                            @endif
                            <span class="fw-semibold text-dark" style="line-height: 1; vertical-align: middle;">
                                {{ $patient->name }}
                            </span>
                        </td>

                        {{-- Doctor --}}
                        <td class="fw-semibold text-dark align-middle" style="padding:10px;">
                            {{ $patient->doctor ? $patient->doctor->name : 'No Doctor' }}
                        </td>

                        {{-- Address --}}
                        <td class="fw-semibold text-dark align-middle" style="padding:10px;">
                            {{ $patient->address }}
                        </td>

                        {{-- Contact --}}
                        <td class="align-middle" style="padding:10px;">
                            <div class="fw-semibold text-dark">{{ $patient->mail }}</div>
                            <div class="fw-normal text-dark small">{{ $patient->phone }}</div>
                        </td>

                        {{-- Actions --}}
                        <td class="text-start align-middle" style="padding:10px;">
                            <div class="d-flex align-items-center gap-2">

                                {{-- View --}}
                                @if(auth()->user()->can('view patient'))
                                    <a href="#" class="text-dark d-flex align-items-center" title="View" data-bs-toggle="modal" data-bs-target="#viewPatientModal-{{ $patient->id }}">
                                        <i class="bi bi-eye-fill fs-6"></i>
                                    </a>
                                @else
                                    <button class="btn btn-link text-dark p-0 m-0 d-flex align-items-center" title="No permission" disabled>
                                        <i class="bi bi-eye-fill fs-6"></i>
                                    </button>
                                @endif

                                {{-- Edit --}}
                                @if(auth()->user()->can('update patient'))
                                    <a href="#" class="text-dark d-flex align-items-center" title="Edit" data-bs-toggle="modal" data-bs-target="#editPatientModal-{{ $patient->id }}">
                                        <i class="bi bi-pencil-square fs-6"></i>
                                    </a>
                                @else
                                    <button class="btn btn-link text-dark p-0 m-0 d-flex align-items-center" title="No permission" disabled>
                                        <i class="bi bi-pencil-square fs-6"></i>
                                    </button>
                                @endif

                                {{-- Delete --}}
                                @if(auth()->user()->can('delete patient'))
                                    <form action="{{ route('patients.destroy', $patient->id) }}" method="POST" class="d-inline delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-link text-dark p-0 m-0 d-flex align-items-center delete-patient" title="Delete">
                                            <i class="bi bi-trash-fill fs-6"></i>
                                        </button>
                                    </form>
                                @else
                                    <button class="btn btn-link text-dark p-0 m-0 d-flex align-items-center" title="No permission" disabled>
                                        <i class="bi bi-trash-fill fs-6"></i>
                                    </button>
                                @endif

                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-dark" style="border:1px solid #dee2e6; padding:10px;">
                            No patients found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Modals --}}
@can('add patient')
    @include('components.addpatientmodal', ['doctors' => $doctors])
@endcan

@foreach ($patients as $patient)
    @can('view patient')
        @include('components.viewpatientmodal', ['patient' => $patient])
    @endcan
    @can('update patient')
        @include('components.editpatientmodal', ['patient' => $patient, 'doctors' => $doctors])
    @endcan
@endforeach

@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const deleteButtons = document.querySelectorAll('.delete-patient');
    deleteButtons.forEach(button => {
        button.addEventListener('click', function() {
            const form = this.closest('.delete-form');
            Swal.fire({
                title: 'Are you sure?',
                text: "This action cannot be undone!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });

    @if (session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: "{{ session('success') }}",
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'OK'
        });
    @endif
});
</script>
@endsection
