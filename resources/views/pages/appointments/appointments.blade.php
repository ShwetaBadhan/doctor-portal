@extends('layout.master')

@section('content')
<div class="page-wrapper">
    <div class="content">

        <!-- Page Header -->
        <div class="d-flex align-items-sm-center flex-sm-row flex-column gap-2 pb-3 mb-3 border-1 border-bottom">
            <div class="flex-grow-1">
                <h4 class="fw-semibold mb-0">
                    Appointments 
                    <span class="badge badge-soft-primary fw-medium border py-1 px-2 border-primary fs-13 ms-1">
                        Total: {{ $appointments->total() ?? $appointments->count() }}
                    </span>
                </h4>
            </div>
            <div class="text-end d-flex">
                <!-- Export Dropdown -->
                <div class="dropdown me-1">
                    <a href="javascript:void(0);" class="btn btn-md fs-14 fw-normal border bg-white rounded text-dark d-inline-flex align-items-center" data-bs-toggle="dropdown">
                        Export<i class="ti ti-chevron-down ms-2"></i>
                    </a>
                    <ul class="dropdown-menu p-2">
                        <li><a class="dropdown-item" href="#">Download as PDF</a></li>
                        <li><a class="dropdown-item" href="#">Download as Excel</a></li>
                    </ul>
                </div>
                
                <!-- View Toggle -->
                <div class="bg-white border shadow-sm rounded px-1 pb-0 text-center d-flex align-items-center justify-content-center">
                    <a href="{{ route('appointments.index') }}" class="bg-light rounded p-1 d-flex align-items-center justify-content-center">
                        <i class="ti ti-list fs-14 text-body"></i>
                    </a>
                    <a href="{{ route('appointment-calendar') }}" class="bg-white rounded p-1 d-flex align-items-center justify-content-center">
                        <i class="ti ti-calendar-event fs-14 text-body"></i>
                    </a>
                </div>
                 @can('create-appointments')
                      <!-- Create Button -->
                <a href="{{ route('appointments.create') }}" class="btn btn-primary ms-2 fs-13 btn-md">
                    <i class="ti ti-plus me-1"></i> New Appointment
                </a>
                 @endcan
               
            </div>
        </div>

        <!-- SweetAlert Session Messages -->
        @if (session('success'))
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        icon: 'success', title: 'Success!', text: @json(session('success')),
                        timer: 4000, timerProgressBar: true, showConfirmButton: false,
                        toast: true, position: 'top-end'
                    });
                });
            </script>
        @endif

        @if (session('error'))
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({ icon: 'error', title: 'Error!', text: @json(session('error')), confirmButtonColor: '#dc3545' });
                });
            </script>
        @endif

        @if ($errors->any())
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const errorList = @json($errors->all()).map(err => `<li>${err}</li>`).join('');
                    Swal.fire({ icon: 'error', title: 'Validation Error', html: `<ul class="text-start mb-0">${errorList}</ul>`, confirmButtonText: 'Got it', confirmButtonColor: '#dc3545' });
                });
            </script>
        @endif

        <!-- Dynamic Table -->
        <div class="table-responsive">
            <table class="table datatable table-nowrap">
                <thead>
                    <tr>
                        <th class="no-sort">Date & Time</th>
                        <th>Patient</th>
                        <th>Mode</th>
                        <th>Status</th>
                        <th>Reason</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($appointments as $appointment)
                        <tr>
                            <!-- Date & Time -->
                            <td>
                                <div class="d-flex flex-column">
                                    <span class="fw-medium">{{ $appointment->appointment_date->format('d M Y') }}</span>
                                    <small class="text-muted">{{ $appointment->appointment_time->format('h:i A') }}</small>
                                </div>
                            </td>

                            <!-- Patient Info -->
                            <td>
                                <div class="d-flex align-items-center">
                                    <a href="{{ route('patients.show', $appointment->patient_id) }}" class="avatar avatar-md me-2">
                                        @if($appointment->patient->profile_image)
                                            <img src="{{ Storage::url($appointment->patient->profile_image) }}" alt="{{ $appointment->patient->first_name }}" class="rounded-circle">
                                        @else
                                            <span class="avatar-text bg-light text-muted rounded-circle d-flex align-items-center justify-content-center w-100 h-100">
                                                {{ substr($appointment->patient->first_name, 0, 1) }}
                                            </span>
                                        @endif
                                    </a>
                                    <div>
                                        <a href="{{ route('patients.show', $appointment->patient_id) }}" class="text-dark fw-semibold">
                                            {{ $appointment->patient->first_name }} {{ $appointment->patient->last_name }}
                                        </a>
                                        <span class="text-body fs-13 fw-normal d-block">{{ $appointment->patient->phone }}</span>
                                    </div>
                                </div>
                            </td>

                            <!-- Mode -->
                            <td>
                                @if($appointment->appointment_type == 'in_person')
                                    <span class="badge badge-soft-info rounded text-info fs-13 fw-medium">In-Person</span>
                                @else
                                    <span class="badge badge-soft-primary rounded text-primary fs-13 fw-medium">Online</span>
                                @endif
                            </td>

                            <!-- Status Badge -->
                            <td>
                                @php
                                    $statusClass = match($appointment->status) {
                                        'checked_out' => 'badge-soft-info text-info',
                                        'checked_in' => 'badge-soft-warning text-warning',
                                        'cancelled' => 'badge-soft-danger text-danger',
                                        'schedule' => 'badge-soft-primary text-primary',
                                        'confirmed' => 'badge-soft-success text-success',
                                        default => 'badge-soft-secondary text-secondary',
                                    };
                                @endphp
                                <span class="badge {{ $statusClass }} rounded fs-13 fw-medium">
                                    {{ ucfirst(str_replace('_', ' ', $appointment->status)) }}
                                </span>
                            </td>

                            <!-- Reason (Truncated) -->
                            <td>
                                <span class="text-muted" title="{{ $appointment->reason }}">
                                    {{ Str::limit($appointment->reason, 30) }}
                                </span>
                            </td>

                            <!-- Actions -->
                            <td class="action-item">
                                <div class="d-flex align-items-center gap-1">
                                    {{-- <a href="javascript:void(0);" class="shadow-sm fs-14 d-inline-flex border rounded-2 p-1 me-1" title="Appointment">
                                        <i class="ti ti-calendar-cog"></i>
                                    </a> --}}
                                    <div class="dropdown">
                                        <a href="javascript:void(0);" class="shadow-sm fs-14 d-inline-flex border rounded-2 p-1" data-bs-toggle="dropdown" title="More">
                                            <i class="ti ti-dots-vertical"></i>
                                        </a>
                                        <ul class="dropdown-menu p-2">
                                            <li>
                                            @can('edit-appointments')
                                                 <a href="{{ route('appointments.edit', $appointment->id) }}" class="dropdown-item d-flex align-items-center">
                                                    <i class="ti ti-edit me-2 fs-14"></i> Edit
                                                </a>
                                            @endcan
                                               
                                            </li>
                                            <li>
                                            @can('view-appointment-details')
                                                <a href="javascript:void(0);" class="dropdown-item d-flex align-items-center" 
                                                   data-bs-toggle="offcanvas" 
                                                   data-bs-target="#view_details_{{ $appointment->id }}">
                                                    <i class="ti ti-eye me-2 fs-14"></i> View
                                                </a>
                                                 @endcan
                                            </li>
                                            <li>
                                             @can('delete-appointments')
                                                <a href="javascript:void(0);" class="dropdown-item d-flex align-items-center text-danger" 
                                                   data-bs-toggle="modal" 
                                                   data-bs-target="#delete_modal_{{ $appointment->id }}">
                                                    <i class="ti ti-trash me-2 fs-14"></i> Delete
                                                </a>
                                                @endcan
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </td>
                        </tr>

                        <!-- View Details Offcanvas (Unique per appointment) -->
                        <div class="offcanvas offcanvas-offset offcanvas-end" tabindex="-1" id="view_details_{{ $appointment->id }}">
                            <div class="offcanvas-header d-block pb-0 px-0">
                                <div class="border-bottom d-flex align-items-center justify-content-between pb-3 px-3">
                                    <h5 class="offcanvas-title fs-18 fw-bold">
                                        Appointment Details 
                                        <span class="badge badge-soft-primary border pt-1 px-2 border-primary fw-medium ms-2">
                                            #{{ $appointment->appointment_id }}
                                        </span>
                                    </h5>
                                    <button type="button" class="btn-close custom-btn-close opacity-100" data-bs-dismiss="offcanvas" aria-label="Close">
                                        <i class="ti ti-x bg-white fs-16 text-dark"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="offcanvas-body pt-0 px-0">
                                <h6 class="bg-light py-2 px-3 fw-bold">When & Where</h6>
                                <div class="px-3 my-4">
                                    <div class="bg-light p-3 mb-3 border rounded-3">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div>
                                                <h6 class="fw-semibold mb-1">
                                                    {{ $appointment->patient->first_name }} {{ $appointment->patient->last_name }}
                                                </h6>
                                                <p class="mb-0 text-muted fs-13">{{ $appointment->patient->phone }}</p>
                                            </div>
                                            <div class="flex-shrink-0">
                                                @if($appointment->appointment_type == 'online')
                                                    <a href="javascript:void(0);" class="btn btn-outline-white bg-white fs-14 d-inline-flex border rounded-2 p-1 me-1">
                                                        <i class="ti ti-video"></i>
                                                    </a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <p class="text-dark mb-3 fw-semibold d-flex align-items-center justify-content-between">
                                        Appointment On 
                                        <span class="text-body fw-normal">{{ $appointment->appointment_date->format('l, d M Y') }}</span>
                                    </p>
                                    <p class="text-dark mb-3 fw-semibold d-flex align-items-center justify-content-between">
                                        Time 
                                        <span class="text-body fw-normal">{{ $appointment->appointment_time->format('h:i A') }}</span>
                                    </p>
                                    <p class="text-dark mb-3 fw-semibold d-flex align-items-center justify-content-between">
                                        Type 
                                        <span class="text-body fw-normal">{{ ucfirst(str_replace('_', ' ', $appointment->appointment_type)) }}</span>
                                    </p>
                                    <p class="text-dark mb-3 fw-semibold">Reason</p>
                                    <p class="text-muted">{{ $appointment->reason }}</p>
                                </div>

                                <h6 class="bg-light py-2 px-3 text-dark fw-bold">Status</h6>
                                <div class="px-3 my-4">
                                    <form action="{{ route('appointments.update', $appointment->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="row align-items-center">
                                            <div class="col-lg-6 col-md-6">
                                                <p class="text-dark mb-0">Update Status</p>
                                            </div>
                                            <div class="col-lg-6 col-md-6">
                                                <div class="mb-3">
                                                    <select name="status" class="select form-select-sm" onchange="this.form.submit()">
                                                        @foreach(['schedule' => 'Schedule', 'confirmed' => 'Confirmed', 'checked_in' => 'Checked In', 'checked_out' => 'Checked Out', 'cancelled' => 'Cancelled'] as $val => $label)
                                                            <option value="{{ $val }}" {{ $appointment->status == $val ? 'selected' : '' }}>{{ $label }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Delete Modal (Unique per appointment) -->
                        <div class="modal fade" id="delete_modal_{{ $appointment->id }}">
                            <div class="modal-dialog modal-dialog-centered modal-sm">
                                <div class="modal-content">
                                    <div class="modal-body text-center position-relative">
                                        <img src="{{ asset('assets/img/bg/delete-modal-bg-01.png') }}" alt="" class="img-fluid position-absolute top-0 start-0 z-0">
                                        <img src="{{ asset('assets/img/bg/delete-modal-bg-02.png') }}" alt="" class="img-fluid position-absolute bottom-0 end-0 z-0">
                                        <div class="mb-3 position-relative z-1">
                                            <span class="avatar avatar-lg bg-danger text-white"><i class="ti ti-trash fs-24"></i></span>
                                        </div>
                                        <h5 class="fw-bold mb-1 position-relative z-1">Delete Confirmation</h5>
                                        <p class="mb-3 position-relative z-1">
                                            Are you sure you want to delete appointment <strong>#{{ $appointment->appointment_id }}</strong>?
                                        </p>
                                        <div class="d-flex justify-content-center">
                                            <a href="javascript:void(0);" class="btn btn-light position-relative z-1 me-3" data-bs-dismiss="modal">Cancel</a>
                                            <form action="{{ route('appointments.destroy', $appointment->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger position-relative z-1">Yes, Delete</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <!-- Empty State -->
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <div class="text-muted">
                                    <i class="ti ti-calendar-off fs-1 text-secondary"></i>
                                    <h5 class="mt-3 mb-1">No Appointments Found</h5>
                                    <p class="mb-3">Get started by scheduling your first appointment.</p>
                                    <a href="{{ route('appointments.create') }}" class="btn btn-primary">
                                        <i class="ti ti-plus me-1"></i> New Appointment
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($appointments->hasPages())
            <div class="mt-3">
                {{ $appointments->links('pagination::bootstrap-5') }}
            </div>
        @endif

    </div>
    
    @include('components.copyright')
</div>
@endsection