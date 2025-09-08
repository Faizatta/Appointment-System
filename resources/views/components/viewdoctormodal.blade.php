<div class="modal fade" id="viewDoctorModal-{{ $doctor->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-sm">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold">Doctor Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                {{-- Profile Image --}}
                <div class="text-center mb-3">
                    @if($doctor->image)
                        <img src="{{ asset('storage/' . $doctor->image) }}" class="rounded-circle border shadow-sm"
                             width="100" height="100" style="object-fit:cover;">
                    @else
                        <div class="rounded-circle border bg-secondary d-flex justify-content-center align-items-center mx-auto"
                             style="width:100px; height:100px; font-size:14px; color:white;">
                            N/A
                        </div>
                    @endif
                </div>

                {{-- Two fields per row --}}
                <div class="row mb-2">
                    <div class="col">
                        <label class="form-label fw-semibold">Name</label>
                        <div class="form-control bg-light" readonly>{{ $doctor->name }}</div>
                    </div>
                    <div class="col">
                        <label class="form-label fw-semibold">Email</label>
                        <div class="form-control bg-light" readonly>{{ $doctor->email }}</div>
                    </div>
                </div>

                <div class="row mb-2">
                    <div class="col">
                        <label class="form-label fw-semibold">Phone</label>
                        <div class="form-control bg-light" readonly>{{ $doctor->phone }}</div>
                    </div>
                    <div class="col">
                        <label class="form-label fw-semibold">Address</label>
                        <div class="form-control bg-light" readonly>{{ $doctor->address }}</div>
                    </div>
                </div>

                {{-- Patients full width --}}
                <div class="mb-2">
                    <label class="form-label fw-semibold">Patients</label>
                    <div class="form-control bg-light" readonly>
                        @if($doctor->patients->count())
                            {{ $doctor->patients->pluck('name')->implode(', ') }}
                        @else
                            No patients
                        @endif
                    </div>
                </div>

            </div>

            <div class="modal-footer border-0">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
