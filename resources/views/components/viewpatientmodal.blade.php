<div class="modal fade" id="viewPatientModal-{{ $patient->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-sm">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold">Patient Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                {{-- Profile Image --}}
                <div class="text-center mb-3">
                    @if($patient->image)
                        <img src="{{ asset('storage/' . $patient->image) }}" class="rounded-circle border shadow-sm"
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
                        <div class="form-control bg-light" readonly>{{ $patient->name }}</div>
                    </div>
                    <div class="col">
                        <label class="form-label fw-semibold">Email</label>
                        <div class="form-control bg-light" readonly>{{ $patient->mail }}</div>
                    </div>
                </div>

                <div class="row mb-2">
                    <div class="col">
                        <label class="form-label fw-semibold">Phone</label>
                        <div class="form-control bg-light" readonly>{{ $patient->phone }}</div>
                    </div>
                    <div class="col">
                        <label class="form-label fw-semibold">Address</label>
                        <div class="form-control bg-light" readonly>{{ $patient->address ?? 'N/A' }}</div>
                    </div>
                </div>

                <div class="mb-2">
                    <label class="form-label fw-semibold">Assigned Doctor</label>
                    <div class="form-control bg-light" readonly>
                        {{ $patient->doctor ? $patient->doctor->name : 'No Doctor' }}
                    </div>
                </div>

            </div>

            <div class="modal-footer border-0">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
