@extends('layout.master')
@section('content')
    <div class="page-wrapper">
        <div class="content">
            <!-- Page Header -->
            <div class="mb-4">
                <h6 class="fw-bold mb-0 d-flex align-items-center">
                    <a href="{{ route('patients.index') }}" class="text-dark">
                        <i class="ti ti-chevron-left me-1"></i>Patients
                    </a>
                    <span class="mx-2">/</span>
                    <span class="text-primary">Patient Details</span>
                </h6>
            </div>

            <!-- SweetAlert Messages -->
            @if (session('success'))
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: @json(session('success')),
                            timer: 3000,
                            showConfirmButton: false,
                            toast: true,
                            position: 'top-end'
                        });
                    });
                </script>
            @endif

            <!-- Patient Header Card -->
            <div class="card">
                <div class="row align-items-end">
                    <div class="col-xl-9 col-lg-8">
                        <div class="d-sm-flex align-items-center position-relative z-0 overflow-hidden p-3">
                            <img src="{{ asset('assets/img/icons/shape-01.svg') }}" alt="img"
                                class="z-n1 position-absolute end-0 top-0 d-none d-lg-flex">
                            <!-- Profile Image -->
                            <a href="javascript:void(0);" class="avatar avatar-xxxl patient-avatar me-2 flex-shrink-0">
                                @if ($patient->profile_image)
                                    <img src="{{ Storage::url($patient->profile_image) }}" alt="{{ $patient->first_name }}"
                                        class="rounded">
                                @else
                                    <span
                                        class="avatar-text bg-light text-muted d-flex align-items-center justify-content-center w-100 h-100 fs-3">
                                        {{ substr($patient->first_name, 0, 1) }}
                                    </span>
                                @endif
                            </a>
                            <div>
                                <p class="text-primary mb-1">#{{ $patient->patient_id }}</p>
                                <h5 class="mb-1">
                                    <a href="javascript:void(0);" class="fw-bold">
                                        {{ $patient->first_name }} {{ $patient->last_name }}
                                        <br>
                                        {{ $patient->care_of_name }}
                                    </a>
                                </h5>
                                <p class="mb-3">
                                    {{ $patient->address_1 }}{{ $patient->address_2 ? ', ' . $patient->address_2 : '' }},
                                    {{ $patient->city }}, {{ $patient->state }} {{ $patient->pincode }}
                                </p>
                                <div class="d-flex align-items-center flex-wrap">
                                    <p class="mb-0 d-inline-flex align-items-center">
                                        <i class="ti ti-phone me-1 text-dark"></i>
                                        Phone : <span class="text-dark ms-1">{{ $patient->phone }}</span>
                                    </p>
                                    @if ($patient->email)
                                        <span class="mx-2 text-light">|</span>
                                        <p class="mb-0 d-inline-flex align-items-center">
                                            <i class="ti ti-mail me-1 text-dark"></i>
                                            Email : <span class="text-dark ms-1">{{ $patient->email }}</span>
                                        </p>
                                    @endif
                                    <span class="mx-2 text-light">|</span>
                                    <p class="mb-0 d-inline-flex align-items-center">
                                        <i class="ti ti-calendar-time me-1 text-dark"></i>
                                        Last Visited : <span class="text-dark ms-1">
                                            {{ $patient->updated_at ? $patient->updated_at->format('d M Y') : $patient->created_at->format('d M Y') }}
                                        </span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Action Buttons -->
                    <div class="col-xl-3 col-lg-4">
                        <div class="p-3 text-lg-end">
                            @can('view-patient-appointment')
                                <a href="{{ route('appointment-calendar', ['patient_id' => $patient->id]) }}"
                                    class="btn btn-primary">
                                    <i class="ti ti-calendar-event me-1"></i>Book Appointment
                                </a>
                            @endcan
                        </div>
                    </div>
                </div>
            </div>

            <!-- About & Vital Signs Row -->
            <div class="row">
                <!-- About Card -->
                <div class="col-xl-5 d-flex">
                    <div class="card shadow-sm flex-fill w-100">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="fw-bold mb-0"><i class="ti ti-user-star me-1"></i>About</h5>
                            @can('edit-patients')
                                <a href="{{ route('patients.edit', $patient->id) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="ti ti-edit me-1"></i>Edit
                                </a>
                            @endcan
                        </div>
                        <div class="card-body pb-0">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="d-flex align-items-center mb-3">
                                        <span class="avatar rounded-circle bg-light text-dark flex-shrink-0 me-2">
                                            <i class="ti ti-calendar-event text-body fs-16"></i>
                                        </span>
                                        <div>
                                            <h6 class="fs-13 fw-bold mb-1">DOB</h6>
                                            <p class="mb-0">{{ $patient->dob ? $patient->dob->format('d M Y') : 'N/A' }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="d-flex align-items-center mb-3">
                                        <span class="avatar rounded-circle bg-light text-dark flex-shrink-0 me-2">
                                            <i class="ti ti-hourglass text-body fs-16"></i>
                                        </span>
                                        <div>
                                            <h6 class="fs-13 fw-bold mb-1">Age</h6>
                                            <p class="mb-0">{{ $patient->age }} Years</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="d-flex align-items-center mb-3">
                                        <span class="avatar rounded-circle bg-light text-dark flex-shrink-0 me-2">
                                            <i class="ti ti-droplet text-body fs-16"></i>
                                        </span>
                                        <div>
                                            <h6 class="fs-13 fw-bold mb-1">Blood Group</h6>
                                            <p class="mb-0">{{ $patient->blood_group ?? 'N/A' }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="d-flex align-items-center mb-3">
                                        <span class="avatar rounded-circle bg-light text-dark flex-shrink-0 me-2">
                                            <i
                                                class="ti ti-gender-{{ $patient->gender == 'male' ? 'male' : ($patient->gender == 'female' ? 'female' : 'third') }} text-body fs-16"></i>
                                        </span>
                                        <div>
                                            <h6 class="fs-13 fw-bold mb-1">Gender</h6>
                                            <p class="mb-0">{{ ucfirst($patient->gender) ?? 'N/A' }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="d-flex align-items-center mb-3">
                                        <span class="avatar rounded-circle bg-light text-dark flex-shrink-0 me-2">
                                            <i class="ti ti-mail text-body fs-16"></i>
                                        </span>
                                        <div>
                                            <h6 class="fs-13 fw-bold mb-1">Email</h6>
                                            <p class="mb-0 text-break">{{ $patient->email ?? 'N/A' }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="d-flex align-items-center mb-3">
                                        <span class="avatar rounded-circle bg-light text-dark flex-shrink-0 me-2">
                                            <i class="ti ti-user-md text-body fs-16"></i>
                                        </span>
                                        <div>
                                            <h6 class="fs-13 fw-bold mb-1">Primary Doctor</h6>
                                            <p class="mb-0">{{ $patient->primary_doctor ?? 'N/A' }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="d-flex align-items-center mb-3">
                                        <span class="avatar rounded-circle bg-light text-dark flex-shrink-0 me-2">
                                            <i class="ti ti-circle-check text-body fs-16"></i>
                                        </span>
                                        <div>
                                            <h6 class="fs-13 fw-bold mb-1">Status</h6>
                                            <p class="mb-0">
                                                @if ($patient->status == 'available')
                                                    <span
                                                        class="badge badge-soft-success rounded text-success">Available</span>
                                                @else
                                                    <span
                                                        class="badge badge-soft-danger rounded text-danger">Unavailable</span>
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Vital Signs Card -->
                <div class="col-xl-7 d-flex">
                    <div class="card shadow-sm flex-fill w-100">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="fw-bold mb-0"><i class="ti ti-heart-rate-monitor me-1"></i>Vital Signs</h5>
                            @if ($latestAppointment)
                                <small class="text-muted">
                                    From: {{ $latestAppointment->appointment_date->format('d M Y') }}
                                    @ {{ $latestAppointment->appointment_time?->format('h:i A') }}
                                </small>
                            @endif
                        </div>
                        <div class="card-body pb-0">
                            @if (
                                $latestAppointment &&
                                    ($latestAppointment->delusion ||
                                        $latestAppointment->vat ||
                                        $latestAppointment->pit ||
                                        $latestAppointment->kuff ||
                                        $latestAppointment->pulse ||
                                        $latestAppointment->weight ||
                                        $latestAppointment->bp ||
                                        $latestAppointment->temp))
                                <div class="row">
                                    @php
                                        $vitals = [
                                            [
                                                'key' => 'delusion',
                                                'label' => 'Delusion',
                                                'icon' => 'ti ti-brain',
                                                'unit' => '',
                                            ],
                                            [
                                                'key' => 'vat',
                                                'label' => 'VAT',
                                                'icon' => 'ti ti-hexagons',
                                                'unit' => '',
                                            ],
                                            [
                                                'key' => 'pit',
                                                'label' => 'PIT',
                                                'icon' => 'ti ti-activity',
                                                'unit' => '',
                                            ],
                                            [
                                                'key' => 'kuff',
                                                'label' => 'Kuff',
                                                'icon' => 'ti ti-windmill',
                                                'unit' => '',
                                            ],
                                            [
                                                'key' => 'pulse',
                                                'label' => 'Heart Rate',
                                                'icon' => 'ti ti-heart',
                                                'unit' => 'bpm',
                                            ],
                                            [
                                                'key' => 'weight',
                                                'label' => 'Weight',
                                                'icon' => 'ti ti-weight',
                                                'unit' => 'kg',
                                            ],
                                            [
                                                'key' => 'bp',
                                                'label' => 'Blood Pressure',
                                                'icon' => 'ti ti-droplet',
                                                'unit' => '',
                                            ],
                                            [
                                                'key' => 'temp',
                                                'label' => 'Temperature',
                                                'icon' => 'ti ti-temperature',
                                                'unit' => '°F',
                                            ],
                                        ];
                                    @endphp
                                    @foreach ($vitals as $vital)
                                        @if ($latestAppointment->{$vital['key']})
                                            <div class="col-sm-4">
                                                <div class="d-flex align-items-center mb-3">
                                                    <span
                                                        class="avatar rounded-2 bg-light text-dark flex-shrink-0 me-2 border">
                                                        <i class="{{ $vital['icon'] }} fs-16 text-body"></i>
                                                    </span>
                                                    <div>
                                                        <h6 class="fs-13 fw-bold mb-1 text-truncate">{{ $vital['label'] }}
                                                        </h6>
                                                        <p class="mb-0 d-inline-flex align-items-center text-truncate">
                                                            <i class="ti ti-point-filled me-1 text-success fs-18"></i>
                                                            {{ $latestAppointment->{$vital['key']} }} {{ $vital['unit'] }}
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                    @if ($latestAppointment->tongue || $latestAppointment->nails)
                                        <div class="col-sm-4">
                                            <div class="d-flex align-items-center mb-3">
                                                <span
                                                    class="avatar rounded-2 bg-light text-dark flex-shrink-0 me-2 border">
                                                    <i class="ti ti-eye fs-16 text-body"></i>
                                                </span>
                                                <div>
                                                    <h6 class="fs-13 fw-bold mb-1">Observations</h6>
                                                    <p class="mb-0 text-truncate">
                                                        @if ($latestAppointment->tongue)
                                                            Tongue: {{ $latestAppointment->tongue }}
                                                        @endif
                                                        @if ($latestAppointment->tongue && $latestAppointment->nails)
                                                            |
                                                        @endif
                                                        @if ($latestAppointment->nails)
                                                            Nails: {{ $latestAppointment->nails }}
                                                        @endif
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                    @if ($latestAppointment->cerebral_fluid)
                                        <div class="col-sm-4">
                                            <div class="d-flex align-items-center mb-3">
                                                <span
                                                    class="avatar rounded-2 bg-light text-dark flex-shrink-0 me-2 border">
                                                    <i class="ti ti-brain fs-16 text-body"></i>
                                                </span>
                                                <div>
                                                    <h6 class="fs-13 fw-bold mb-1">Cerebral Fluid</h6>
                                                    <p class="mb-0 d-inline-flex align-items-center">
                                                        <i
                                                            class="ti ti-point-filled me-1 text-{{ $latestAppointment->cerebral_fluid == 'normal' ? 'success' : 'warning' }} fs-18"></i>
                                                        {{ ucfirst($latestAppointment->cerebral_fluid) }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                    @if ($latestAppointment->vital_notes)
                                        <div class="col-12 mt-2">
                                            <small class="text-muted d-block">Notes</small>
                                            <p class="mb-0 text-muted small">{{ $latestAppointment->vital_notes }}</p>
                                        </div>
                                    @endif
                                </div>
                                <div class="mt-3 text-end">
                                    <a href="#appointments" data-bs-toggle="tab" class="btn btn-sm btn-outline-primary">
                                        <i class="ti ti-list me-1"></i> View All Visits
                                    </a>
                                </div>
                            @else
                                <div class="text-center py-4">
                                    <i class="ti ti-heart-rate-monitor fs-1 text-muted opacity-50"></i>
                                    <p class="text-muted mt-2 mb-0">No vital signs recorded yet.</p>
                                    <small class="text-muted d-block">Vitals are recorded during each appointment
                                        visit.</small>
                                    <a href="{{ route('appointments.create', ['patient_id' => $patient->id]) }}"
                                        class="btn btn-sm btn-primary mt-3">
                                        <i class="ti ti-plus me-1"></i> Schedule Appointment
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabs Navigation -->
            <ul class="nav nav-tabs nav-bordered mb-3">
                <li class="nav-item"><a href="#symptoms" data-bs-toggle="tab"
                        class="nav-link active bg-transparent"><span>Symptoms</span></a></li>
                <li class="nav-item"><a href="#medicines" data-bs-toggle="tab"
                        class="nav-link bg-transparent"><span>Medicines</span></a></li>
                <li class="nav-item"><a href="#reports" data-bs-toggle="tab" class="nav-link bg-transparent"><span>Test
                            Reports</span></a></li>
                <li class="nav-item"><a href="#treatment" data-bs-toggle="tab"
                        class="nav-link bg-transparent"><span>Treatment</span></a></li>
                <li class="nav-item"><a href="#appointments" data-bs-toggle="tab"
                        class="nav-link bg-transparent"><span>Appointments</span></a></li>
                <li class="nav-item"><a href="#vitals" data-bs-toggle="tab" class="nav-link bg-transparent"><span>Vital
                            Signs</span></a></li>
            </ul>

            <!-- Tab Content -->
            <div class="tab-content">
               <!-- Symptoms Tab -->
<div class="tab-pane show active" id="symptoms">
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-success bg-opacity-10">
                    <h6 class="fw-bold mb-0 text-success">
                        <i class="ti ti-circle-check me-1"></i>Existing Symptoms
                    </h6>
                </div>
                <div class="card-body">
                    @php
                        // Merge existing and additional symptoms, then remove any duplicates
                        $existing = is_array($patient->existing_symptoms) ? $patient->existing_symptoms : [];
                        $additional = is_array($patient->additional_symptoms) ? $patient->additional_symptoms : [];
                        $allExistingSymptoms = array_unique(array_merge($existing, $additional));
                    @endphp

                    @if (count($allExistingSymptoms) > 0)
                        <div class="d-flex flex-wrap gap-2">
                            @foreach ($allExistingSymptoms as $symptom)
                                <span class="badge bg-success bg-opacity-10 text-success border border-success">
                                    {{ $symptom }}
                                </span>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted mb-0">No existing symptoms recorded.</p>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-danger bg-opacity-10">
                    <h6 class="fw-bold mb-0 text-danger">
                        <i class="ti ti-circle-x me-1"></i>Non-Existing Symptoms
                    </h6>
                </div>
                <div class="card-body">
                    @if ($patient->non_existing_symptoms && count($patient->non_existing_symptoms) > 0)
                        <div class="d-flex flex-wrap gap-2">
                            @foreach ($patient->non_existing_symptoms as $symptom)
                                <span class="badge bg-danger bg-opacity-10 text-danger border border-danger">
                                    {{ $symptom }}
                                </span>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted mb-0">No non-existing symptoms recorded.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

                <!-- Medicines Tab -->
                <div class="tab-pane" id="medicines">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="fw-bold mb-0"><i class="ti ti-pills me-2 text-primary"></i>Prescribed Medicines</h6>
                        @can('assign-medicines-to-patients')
                            <div class="d-flex gap-2">
                                {{-- ✅ NEW: Bulk Edit All Medicines Button --}}
                                @if ($patient->patientMedicines && $patient->patientMedicines->count())
                                    <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#bulkEditMedicinesModal">
                                        <i class="ti ti-edit me-1"></i> Edit All Medicines
                                    </button>
                                @endif

                                <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#reassignMedicinesModal">
                                    <i class="ti ti-refresh me-1"></i> Reassign Medicines
                                </button>

                                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#assignMedicineModal">
                                    <i class="ti ti-plus me-1"></i> Assign Medicine Group
                                </button>
                            </div>
                        @endcan
                    </div>

                    <!-- Current Prescriptions -->
                    @if ($patient->patientMedicines && $patient->patientMedicines->count())
                        <div class="card mb-4">
                            <div class="card-header bg-light">
                                <h6 class="fw-bold mb-0">Current Prescriptions</h6>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th>#</th>
                                                <th>Medicine</th>
                                                <th>From Group</th>
                                                <th>Dosage</th>
                                                <th>Quantity</th>
                                                <th>Instructions</th>
                                                <th>Assigned On</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($patient->patientMedicines as $index => $pm)
                                                <tr>
                                                    <td class="fw-medium">{{ $loop->iteration }}</td>
                                                    <td>
                                                        <div class="fw-medium">
                                                            {{ $pm->custom_name ?? ($pm->medicine->name ?? 'Unknown') }}
                                                        </div>
                                                        @if ($pm->notes)
                                                            <small
                                                                class="text-muted d-block">{{ Str::limit($pm->notes, 40) }}</small>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if ($pm->medicineGroup)
                                                            <span
                                                                class="badge bg-light text-dark border">{{ $pm->medicineGroup->name }}</span>
                                                        @else
                                                            <span class="text-muted">Individual</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if ($pm->dosage)
                                                            <span
                                                                class="badge bg-light text-dark">{{ $pm->dosage }}</span>
                                                        @else
                                                            <span class="text-muted">-</span>
                                                        @endif
                                                    </td>
                                                    <td>{{ $pm->quantity ?? '-' }}</td>
                                                    <td>
                                                        <small class="text-muted">{{ $pm->instructions ?? '-' }}</small>
                                                        @if ($pm->route)
                                                            <br><span
                                                                class="badge bg-info text-dark fs-11">{{ $pm->route }}</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <small
                                                            class="text-muted">{{ $pm->created_at->format('d M Y') }}</small>
                                                        @if ($pm->start_date || $pm->end_date)
                                                            <br><small class="text-primary">
                                                                {{ $pm->start_date?->format('d M') }}
                                                                @if ($pm->end_date)
                                                                    → {{ $pm->end_date->format('d M') }}
                                                                @endif
                                                            </small>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <div class="btn-group btn-group-sm">
                                                            <button type="button" class="btn btn-light"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#editMedicineModal{{ $pm->id }}"
                                                                title="Edit">
                                                                <i class="ti ti-edit"></i>
                                                            </button>
                                                            <button type="button" class="btn btn-light text-danger"
                                                                onclick="confirmRemoveMedicine({{ $pm->id }}, '{{ $pm->custom_name ?? ($pm->medicine->name ?? 'Medicine') }}')"
                                                                title="Remove">
                                                                <i class="ti ti-trash"></i>
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <!-- Edit Medicine Modal -->
                                                <div class="modal fade" id="editMedicineModal{{ $pm->id }}"
                                                    tabindex="-1">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <form action="{{ route('patients.medicines.update', $pm) }}"
                                                                method="POST">
                                                                @csrf @method('PUT')
                                                                <div class="modal-header">
                                                                    <h6 class="modal-title">Edit Prescription</h6>
                                                                    <button type="button" class="btn-close"
                                                                        data-bs-dismiss="modal"></button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <div class="mb-3">
                                                                        <label class="form-label fw-medium">Medicine
                                                                            Name</label>
                                                                        <input type="text" class="form-control"
                                                                            name="custom_name"
                                                                            value="{{ old('custom_name', $pm->custom_name ?? ($pm->medicine->name ?? '')) }}"
                                                                            placeholder="Enter medicine name">
                                                                        @if ($pm->medicine)
                                                                            <small class="text-muted">Original:
                                                                                {{ $pm->medicine->name }}</small>
                                                                        @endif
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="col-6 mb-3">
                                                                            <label class="form-label">Dosage</label>
                                                                            <input type="text" class="form-control"
                                                                                name="dosage"
                                                                                value="{{ old('dosage', $pm->dosage) }}">
                                                                        </div>
                                                                        <div class="col-6 mb-3">
                                                                            <label class="form-label">Quantity</label>
                                                                            <input type="text" class="form-control"
                                                                                name="quantity"
                                                                                value="{{ old('quantity', $pm->quantity) }}">
                                                                        </div>
                                                                    </div>
                                                                    <div class="mb-3">
                                                                        <label class="form-label">Route</label>
                                                                        <select class="select" name="route">
                                                                            <option value="">Select</option>
                                                                            <option value="ORAL"
                                                                                {{ old('route', $pm->route) == 'ORAL' ? 'selected' : '' }}>
                                                                                ORAL</option>
                                                                            <option value="EXTERNAL"
                                                                                {{ old('route', $pm->route) == 'EXTERNAL' ? 'selected' : '' }}>
                                                                                EXTERNAL</option>
                                                                            <option value="GULBULES"
                                                                                {{ old('route', $pm->route) == 'GULBULES' ? 'selected' : '' }}>
                                                                                GULBULES</option>
                                                                            <option value="INJECTION"
                                                                                {{ old('route', $pm->route) == 'INJECTION' ? 'selected' : '' }}>
                                                                                INJECTION</option>
                                                                            <option value="SIP SIP"
                                                                                {{ old('route', $pm->route) == 'SIP SIP' ? 'selected' : '' }}>
                                                                                SIP SIP</option>
                                                                        </select>
                                                                    </div>
                                                                    <div class="mb-3">
                                                                        <label class="form-label">Instructions</label>
                                                                        <textarea class="form-control" name="instructions" rows="2">{{ old('instructions', $pm->instructions) }}</textarea>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="col-6 mb-3">
                                                                            <label class="form-label">Start Date</label>
                                                                            <input type="date" class="form-control"
                                                                                name="start_date"
                                                                                value="{{ old('start_date', $pm->start_date?->format('Y-m-d')) }}">
                                                                        </div>
                                                                        <div class="col-6 mb-3">
                                                                            <label class="form-label">End Date</label>
                                                                            <input type="date" class="form-control"
                                                                                name="end_date"
                                                                                value="{{ old('end_date', $pm->end_date?->format('Y-m-d')) }}">
                                                                        </div>
                                                                    </div>
                                                                    <div class="mb-3">
                                                                        <label class="form-label">Notes</label>
                                                                        <textarea class="form-control" name="notes" rows="2">{{ old('notes', $pm->notes) }}</textarea>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-light"
                                                                        data-bs-dismiss="modal">Cancel</button>
                                                                    <button type="submit" class="btn btn-primary">Save
                                                                        Changes</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="card mb-4">
                            <div class="card-body text-center py-5">
                                <i class="ti ti-pills fs-1 text-muted"></i>
                                <h5 class="mt-3">No Medicines Prescribed</h5>
                                <p class="text-muted mb-4">Assign a medicine group to get started.</p>
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#assignMedicineModal">
                                    <i class="ti ti-plus me-1"></i> Assign Medicine Group
                                </button>
                            </div>
                        </div>
                    @endif

                    <!-- Medicine History -->
                    <h6 class="fw-bold mb-3"><i class="ti ti-history me-2"></i>Medicine Assignment History</h6>
                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="row align-items-end g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-medium">Report Date</label>
                                    <input type="date" id="reportDate" class="form-control"
                                        value="{{ now()->format('Y-m-d') }}">
                                </div>
                                <div class="col-md-6">
                                    <button type="button" class="btn btn-primary w-100" onclick="generateReport()">
                                        <i class="ti ti-file-certificate me-1"></i> Generate & Save Report
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <h6 class="fw-bold mb-3 mt-4"><i class="ti ti-history me-2"></i>Saved Reports History</h6>
                    <div id="reportsHistoryContainer">
                        <div class="text-center py-4">
                            <div class="spinner-border spinner-border-sm text-primary" role="status"></div>
                            <p class="text-muted mt-2 mb-0">Loading reports...</p>
                        </div>
                    </div>
                </div>

                <!-- Test Reports Tab -->
                <div class="tab-pane" id="reports">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="fw-bold mb-0"><i class="ti ti-file me-2 text-primary"></i>Test Reports & Documents</h6>
                        @can('upload-patient-reports')
                            <form action="{{ route('reports.upload', $patient->id) }}" method="POST"
                                enctype="multipart/form-data" class="d-inline">
                                @csrf
                                <div class="input-group input-group-sm" style="max-width: 300px;">
                                    <input type="file" name="reports[]" class="form-control" multiple
                                        accept=".pdf,.jpg,.jpeg,.png,.doc,.docx" required>
                                    <button type="submit" class="btn btn-primary"><i class="ti ti-upload me-1"></i>
                                        Upload</button>
                                </div>
                                <small class="text-muted d-block mt-1">PDF, JPG, PNG, DOC (Max 5MB each)</small>
                            </form>
                        @endcan
                    </div>
                    @php
                        $allReports = [];
                        if ($patient->test_reports && count($patient->test_reports) > 0) {
                            foreach ($patient->test_reports as $index => $reportPath) {
                                $allReports[] = [
                                    'type' => 'patient',
                                    'path' => $reportPath,
                                    'date' => \Carbon\Carbon::parse(
                                        filemtime(storage_path('app/public/' . $reportPath)),
                                    ),
                                    'label' => 'Patient Report',
                                    'badge_class' => 'primary',
                                ];
                            }
                        }
                        if ($appointmentsWithReports && $appointmentsWithReports->count() > 0) {
                            foreach ($appointmentsWithReports as $appointment) {
                                if ($appointment->reports && count($appointment->reports) > 0) {
                                    foreach ($appointment->reports as $index => $reportPath) {
                                        $allReports[] = [
                                            'type' => 'appointment',
                                            'path' => $reportPath,
                                            'date' => \Carbon\Carbon::parse(
                                                filemtime(storage_path('app/public/' . $reportPath)),
                                            ),
                                            'appointment_date' => $appointment->appointment_date,
                                            'appointment_time' => $appointment->appointment_time,
                                            'label' => 'Appointment Report',
                                            'badge_class' => 'success',
                                        ];
                                    }
                                }
                            }
                        }
                        usort($allReports, function ($a, $b) {
                            return $b['date']->timestamp - $a['date']->timestamp;
                        });
                    @endphp
                    @if (count($allReports) > 0)
                        <div class="row">
                            @foreach ($allReports as $report)
                                <div class="col-md-4 col-lg-3 mb-3">
                                    <div class="card border h-100">
                                        <div class="card-body text-center">
                                            @php
                                                $ext = pathinfo($report['path'], PATHINFO_EXTENSION);
                                                $icons = [
                                                    'pdf' => 'ti ti-file-text text-danger',
                                                    'jpg' => 'ti ti-photo text-primary',
                                                    'jpeg' => 'ti ti-photo text-primary',
                                                    'png' => 'ti ti-photo text-primary',
                                                    'doc' => 'ti ti-file-text text-info',
                                                    'docx' => 'ti ti-file-text text-info',
                                                ];
                                                $icon = $icons[$ext] ?? 'ti ti-file text-muted';
                                                $fileName = basename($report['path']);
                                            @endphp
                                            <div class="avatar avatar-lg bg-light rounded-circle mb-2 mx-auto">
                                                <i class="{{ $icon }} fs-24"></i>
                                            </div>
                                            <p class="small text-truncate mb-2" title="{{ $fileName }}">
                                                {{ $fileName }}</p>
                                            <p class="fs-11 text-muted mb-2">{{ $report['date']->format('d M Y') }}</p>
                                            <span
                                                class="badge bg-{{ $report['badge_class'] }} bg-opacity-10 text-{{ $report['badge_class'] }} mb-2">
                                                {{ $report['label'] }}
                                            </span>
                                            @if ($report['type'] == 'appointment')
                                                <small class="text-muted d-block mb-2">
                                                    {{ $report['appointment_date']->format('d M Y') }}
                                                    @ {{ $report['appointment_time']->format('h:i A') }}
                                                </small>
                                            @endif
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ Storage::url($report['path']) }}" class="btn btn-light"
                                                    target="_blank" title="View">
                                                    <i class="ti ti-eye"></i>
                                                </a>
                                                <a href="{{ Storage::url($report['path']) }}" class="btn btn-light"
                                                    download title="Download">
                                                    <i class="ti ti-download"></i>
                                                </a>
                                                @if ($report['type'] == 'patient')
                                                    @can('delete-patient-reports')
                                                        <button type="button" class="btn btn-light text-danger"
                                                            title="Delete"
                                                            onclick="confirmDeletePatientReport({{ $patient->id }}, {{ $loop->index }}, '{{ $fileName }}')">
                                                            <i class="ti ti-trash"></i>
                                                        </button>
                                                    @endcan
                                                @elseif ($report['type'] == 'appointment')
                                                    @can('delete-appointments')
                                                        <button type="button" class="btn btn-light text-danger"
                                                            title="Delete"
                                                            onclick="confirmDeleteAppointmentReport({{ $report['appointment_date']->format('Y-m-d') }}, {{ $loop->index }}, '{{ $fileName }}')">
                                                            <i class="ti ti-trash"></i>
                                                        </button>
                                                    @endcan
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="card">
                            <div class="card-body text-center py-5">
                                <i class="ti ti-file fs-1 text-muted"></i>
                                <h5 class="mt-3">No Reports Uploaded</h5>
                                <p class="text-muted mb-4">Upload lab reports, scans, or prescriptions.</p>
                            </div>
                        </div>
                    @endif
                </div>

                <form id="deleteReportForm" method="POST" style="display: none;">@csrf @method('DELETE')</form>
                <form id="deleteAppointmentReportForm" method="POST" style="display: none;">@csrf @method('DELETE')
                </form>

                <!-- Treatment Tab -->
                <div class="tab-pane" id="treatment">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                @if ($patient->medicine)
                                    <div class="col-md-12 mb-3">
                                        <h6 class="fw-bold mb-2"><i class="ti ti-pills me-1"></i>Medicine/Therapy</h6>
                                        <p class="text-muted mb-0">{{ nl2br(e($patient->medicine)) }}</p>
                                    </div>
                                @endif
                                @if ($patient->therapy_history)
                                    <div class="col-md-12 mb-3">
                                        <h6 class="fw-bold mb-2"><i class="ti ti-history me-1"></i>Therapy History</h6>
                                        <p class="text-muted mb-0">{{ nl2br(e($patient->therapy_history)) }}</p>
                                    </div>
                                @endif
                                @if ($patient->remarks)
                                    <div class="col-md-12">
                                        <h6 class="fw-bold mb-2"><i class="ti ti-note me-1"></i>Remarks</h6>
                                        <p class="text-muted mb-0">{{ nl2br(e($patient->remarks)) }}</p>
                                    </div>
                                @endif
                                @if (!$patient->medicine && !$patient->therapy_history && !$patient->remarks)
                                    <p class="text-muted text-center mb-0">No treatment records found.</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Appointments Tab -->
                <div class="tab-pane" id="appointments">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="fw-bold mb-0"><i class="ti ti-calendar-event me-2 text-primary"></i>Appointments for
                            {{ $patient->first_name }}</h6>
                        <a href="{{ route('appointments.create', ['patient_id' => $patient->id]) }}"
                            class="btn btn-sm btn-primary">
                            <i class="ti ti-plus me-1"></i> New Appointment
                        </a>
                    </div>
                    @if ($patient->appointments && $patient->appointments->count())
                        <div class="card">
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Date & Time</th>
                                                <th>Type</th>
                                                <th>Reason</th>
                                                <th>Status</th>
                                                <th class="text-end">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($patient->appointments->sortByDesc('appointment_date') as $appointment)
                                                <tr>
                                                    <td>
                                                        <div class="fw-medium">
                                                            {{ $appointment->appointment_date->format('d M Y') }}</div>
                                                        <small
                                                            class="text-muted">{{ $appointment->appointment_time ?? 'N/A' }}</small>
                                                    </td>
                                                    <td><span
                                                            class="badge bg-light text-dark border">{{ ucfirst(str_replace('_', ' ', $appointment->appointment_type)) }}</span>
                                                    </td>
                                                    <td>{{ Str::limit($appointment->reason, 50) }}</td>
                                                    <td>
                                                        @php
                                                            $statusColors = [
                                                                'schedule' => 'warning',
                                                                'confirmed' => 'info',
                                                                'checked_in' => 'primary',
                                                                'checked_out' => 'success',
                                                                'cancelled' => 'secondary',
                                                            ];
                                                        @endphp
                                                        <span
                                                            class="badge bg-{{ $statusColors[$appointment->status] ?? 'light' }} bg-opacity-10 text-{{ $statusColors[$appointment->status] ?? 'dark' }} border border-{{ $statusColors[$appointment->status] ?? 'light' }}">
                                                            {{ ucfirst(str_replace('_', ' ', $appointment->status)) }}
                                                        </span>
                                                    </td>
                                                    <td class="text-end">
                                                        <div class="btn-group btn-group-sm">
                                                            <a href="{{ route('appointments.show', $appointment) }}"
                                                                class="btn btn-light" title="View"><i
                                                                    class="ti ti-eye"></i></a>
                                                            <a href="{{ route('appointments.edit', $appointment) }}"
                                                                class="btn btn-light" title="Edit"><i
                                                                    class="ti ti-edit"></i></a>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="card">
                            <div class="card-body text-center py-5">
                                <i class="ti ti-calendar-off fs-1 text-muted"></i>
                                <h5 class="mt-3">No Appointments Yet</h5>
                                <p class="text-muted mb-4">This patient has no scheduled appointments.</p>
                                <a href="{{ route('appointments.create', ['patient_id' => $patient->id]) }}"
                                    class="btn btn-primary">
                                    <i class="ti ti-plus me-1"></i> Schedule First Appointment
                                </a>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Vital Signs Tab -->
                <div class="tab-pane" id="vitals">
                    @php
                        $appointmentsWithVitals = $patient->appointments->filter(function ($apt) {
                            return $apt->bp || $apt->temp || $apt->pulse || $apt->weight;
                        });
                        $latestVitals = $appointmentsWithVitals
                            ->sortByDesc(function ($apt) {
                                return $apt->appointment_date . $apt->appointment_time;
                            })
                            ->first();
                    @endphp
                    @if ($latestVitals)
                        <div class="row mb-4">
                            <div class="col-md-3">
                                <div class="card border-primary">
                                    <div class="card-body text-center py-3">
                                        <small class="text-muted d-block">Latest BP</small>
                                        <h5 class="mb-0 text-primary fw-bold">{{ $latestVitals->bp ?? '-' }}</h5>
                                        <small
                                            class="text-muted">{{ $latestVitals->appointment_date->format('d M') }}</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card border-success">
                                    <div class="card-body text-center py-3">
                                        <small class="text-muted d-block">Latest Temp</small>
                                        <h5 class="mb-0 text-success fw-bold">{{ $latestVitals->temp ?? '-' }}°F</h5>
                                        <small
                                            class="text-muted">{{ $latestVitals->appointment_date->format('d M') }}</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card border-info">
                                    <div class="card-body text-center py-3">
                                        <small class="text-muted d-block">Latest Pulse</small>
                                        <h5 class="mb-0 text-info fw-bold">{{ $latestVitals->pulse ?? '-' }} bpm</h5>
                                        <small
                                            class="text-muted">{{ $latestVitals->appointment_date->format('d M') }}</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card border-warning">
                                    <div class="card-body text-center py-3">
                                        <small class="text-muted d-block">Latest Weight</small>
                                        <h5 class="mb-0 text-warning fw-bold">{{ $latestVitals->weight ?? '-' }} kg</h5>
                                        <small
                                            class="text-muted">{{ $latestVitals->appointment_date->format('d M') }}</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h6 class="fw-bold mb-0"><i class="ti ti-history me-2"></i>Vital Signs History</h6>
                            <span class="badge bg-light text-dark">{{ $appointmentsWithVitals->count() }} visits with
                                vitals</span>
                        </div>
                        <div class="card-body p-0">
                            @if ($appointmentsWithVitals->count())
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Date & Time</th>
                                                <th>BP</th>
                                                <th>Temp</th>
                                                <th>Pulse</th>
                                                <th>Weight</th>
                                                <th>VAT/PIT/Kuff/Delusion</th>
                                                <th>Observations</th>
                                                <th>Notes</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($appointmentsWithVitals->sortByDesc(function ($apt) {
            return $apt->appointment_date . $apt->appointment_time;
        }) as $apt)
                                                <tr>
                                                    <td>
                                                        <div class="fw-medium">
                                                            {{ $apt->appointment_date->format('d M Y') }}</div>
                                                        <small
                                                            class="text-muted">{{ $apt->appointment_time?->format('h:i A') }}</small>
                                                    </td>
                                                    <td>
                                                        @if ($apt->bp)
                                                            <span
                                                            class="badge bg-light text-dark border">{{ $apt->bp }}</span>@else<span
                                                                class="text-muted">-</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if ($apt->temp)
                                                            <span
                                                            class="badge bg-light text-dark border">{{ $apt->temp }}°F</span>@else<span
                                                                class="text-muted">-</span>
                                                        @endif
                                                    </td>
                                                    <td>{{ $apt->pulse ?? '-' }}</td>
                                                    <td>{{ $apt->weight ?? '-' }}</td>
                                                    <td><small class="text-muted">
                                                            @if ($apt->vat)
                                                                VAT:{{ $apt->vat }}
                                                                @endif @if ($apt->pit)
                                                                    | PIT:{{ $apt->pit }}
                                                                    @endif @if ($apt->kuff)
                                                                        | Kuff:{{ $apt->kuff }}
                                                                        @endif @if ($apt->delusion)
                                                                            | Delusion:{{ $apt->delusion }}
                                                                            @endif @if (!$apt->vat && !$apt->pit && !$apt->kuff && !$apt->delusion)
                                                                                -
                                                                            @endif
                                                        </small></td>
                                                    <td><small class="text-muted">
                                                            @if ($apt->tongue)
                                                                Tongue:{{ $apt->tongue }}
                                                                @endif @if ($apt->nails)
                                                                    | Nails:{{ $apt->nails }}
                                                                    @endif @if ($apt->cerebral_fluid)
                                                                        | Cerebral:{{ ucfirst($apt->cerebral_fluid) }}
                                                                        @endif @if (!$apt->tongue && !$apt->nails && !$apt->cerebral_fluid)
                                                                            -
                                                                        @endif
                                                        </small></td>
                                                    <td>
                                                        @if ($apt->vital_notes)
                                                            <span class="text-truncate d-inline-block"
                                                                style="max-width: 150px;"
                                                            title="{{ $apt->vital_notes }}">{{ Str::limit($apt->vital_notes, 30) }}</span>@else<span
                                                                class="text-muted">-</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-center py-5">
                                    <i class="ti ti-heart-rate-monitor fs-1 text-muted opacity-50"></i>
                                    <p class="text-muted mt-3 mb-0">No vital signs recorded in any appointment yet.</p>
                                    <small class="text-muted d-block">Vitals are recorded during each appointment
                                        visit.</small>
                                    <a href="{{ route('appointments.create', ['patient_id' => $patient->id]) }}"
                                        class="btn btn-sm btn-primary mt-3">
                                        <i class="ti ti-plus me-1"></i> Schedule Appointment
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            @include('components.copyright')
        </div>

        <!-- ========================================== -->
        <!-- MODALS                                     -->
        <!-- ========================================== -->

        <!-- 1. Assign Medicine Group Modal (ORIGINAL - UNCHANGED) -->
        <div class="modal fade" id="assignMedicineModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-scrollable">
                <div class="modal-content">
                    <form id="assignMedicineForm" action="{{ route('patients.medicines.assign-custom', $patient->id) }}"
                        method="POST">
                        @csrf
                        <div class="modal-header sticky-top bg-white z-3">
                            <h5 class="modal-title"><i class="ti ti-pills me-2"></i>Assign Medicines</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body" style="max-height: calc(100vh - 200px); overflow-y: auto;">
                            <p class="text-muted mb-3">Select medicines from group for <strong>{{ $patient->first_name }}
                                    {{ $patient->last_name }}</strong></p>

                            <!-- Medicine Group Selector -->
                            <div class="mb-3 sticky-top bg-white z-2 pb-2 border-bottom">
                                <label class="form-label fw-medium">Medicine Group <span
                                        class="text-danger">*</span></label>
                                <select name="medicine_group_id" id="medicineGroupSelect" class="form-select" required>
                                    <option value="">Select group...</option>
                                    @foreach ($medicineGroups as $group)
                                        <option value="{{ $group->id }}"
                                            data-count="{{ $group->medicines_count }}">
                                            {{ $group->name }} @if ($group->code)
                                                ({{ $group->code }})
                                            @endif - {{ $group->medicines_count }} medicines
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Loading State -->
                            <div id="medicinesLoading" class="text-center py-4 d-none">
                                <div class="spinner-border text-primary" role="status"></div>
                                <p class="mt-2 text-muted small">Loading medicines...</p>
                            </div>

                            <!-- Dynamic Medicines Container -->
                            <div id="medicinesContainer" class="d-none">
                                <!-- Check All Header -->
                                <div
                                    class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom sticky-top bg-white z-1">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="checkAllMedicines">
                                        <label class="form-check-label fw-medium" for="checkAllMedicines">Select
                                            All</label>
                                    </div>
                                    <span class="badge bg-light text-dark" id="selectedCount">0 selected</span>
                                </div>

                                <!-- Group Medicines List -->
                                <div id="medicinesList" class="space-y-2"
                                    style="max-height: 300px; overflow-y: auto; padding-right: 5px;">
                                    <!-- Dynamic content injected here -->
                                </div>

                                <!-- EXTRA MEDICINES SECTION -->
                                <div class="mt-4 pt-3 border-top">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h6 class="fw-bold mb-0"><i class="ti ti-plus me-1 text-primary"></i>Extra
                                            Medicines</h6>
                                        <button type="button" class="btn btn-sm btn-outline-primary"
                                            onclick="addExtraMedicine()">
                                            <i class="ti ti-plus me-1"></i> Add Extra Medicine
                                        </button>
                                    </div>
                                    <div id="extraMedicinesContainer"></div>
                                    <small class="text-muted"><i class="ti ti-info-circle me-1"></i>Add medicines that are
                                        not part of this group</small>
                                </div>
                            </div>

                            <!-- Date & Notes Section -->
                            <div class="row mt-4 pt-3 border-top">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Start Date <span class="text-danger">*</span></label>
                                    <input type="date" name="start_date" class="form-control" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">End Date <span class="text-danger">*</span></label>
                                    <input type="date" name="end_date" class="form-control" required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">General Notes</label>
                                <textarea name="notes" class="form-control" rows="2" placeholder="Optional instructions..."></textarea>
                            </div>

                            <div id="noMedicines" class="alert alert-warning d-none">
                                <i class="ti ti-alert-circle me-2"></i>This group has no medicines.
                            </div>
                        </div>
                        <div class="modal-footer sticky-bottom bg-white border-top">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary" id="submitAssignBtn" disabled>
                                <i class="ti ti-check me-1"></i> Assign (<span id="submitCount">0</span>)
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Edit All Medicines Modal (Like Assign Modal) -->
        <div class="modal fade" id="bulkEditMedicinesModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-scrollable">
                <div class="modal-content">
                    <form id="bulkEditMedicinesForm"
                        action="{{ route('patients.medicines.bulk-update', $patient->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="modal-header sticky-top bg-white z-3">
                            <h5 class="modal-title"><i class="ti ti-edit me-2 text-info"></i>Edit All Medicines</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>

                        <div class="modal-body" style="max-height: calc(100vh - 200px); overflow-y: auto;">
                            <p class="text-muted mb-3">
                                Edit existing medicines or add new ones for <strong>{{ $patient->first_name }}
                                    {{ $patient->last_name }}</strong>
                            </p>

                            <!-- Existing Medicines Section -->
                            <h6 class="fw-bold mb-3 text-primary">
                                <i class="ti ti-list me-1"></i> Current Medicines
                                ({{ $patient->patientMedicines->count() }})
                            </h6>

                            <div id="existingMedicinesContainer">
                                @if ($patient->patientMedicines && $patient->patientMedicines->count())
                                    <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="bulkCheckAll" checked>
                                            <label class="form-check-label fw-medium" for="bulkCheckAll">Keep All (Uncheck
                                                to remove)</label>
                                        </div>
                                        <span class="badge bg-info text-dark"
                                            id="bulkSelectedCount">{{ $patient->patientMedicines->count() }}
                                            selected</span>
                                    </div>

                                    <div id="existingMedicinesList">
                                        @foreach ($patient->patientMedicines as $index => $pm)
                                            <div class="card border mb-2 bulk-medicine-card"
                                                data-id="{{ $pm->id }}">
                                                <div class="card-body py-2 px-3">
                                                    <div class="d-flex align-items-start gap-2">
                                                        <input class="form-check-input bulk-medicine-checkbox mt-1"
                                                            type="checkbox" name="medicines[{{ $index }}][keep]"
                                                            value="1"
                                                            data-patient-medicine-id="{{ $pm->id }}" checked>
                                                        <input type="hidden"
                                                            name="medicines[{{ $index }}][patient_medicine_id]"
                                                            value="{{ $pm->id }}">

                                                        <div class="flex-grow-1">
                                                            <div class="d-flex align-items-center gap-2 mb-1">
                                                                <input type="text"
                                                                    name="medicines[{{ $index }}][custom_name]"
                                                                    class="form-control form-control-sm"
                                                                    placeholder="Medicine name"
                                                                    value="{{ old('custom_name', $pm->custom_name ?? ($pm->medicine->name ?? '')) }}"
                                                                    required>
                                                                @if ($pm->medicineGroup)
                                                                    <span
                                                                        class="badge bg-light text-dark border fs-10">{{ $pm->medicineGroup->name }}</span>
                                                                @endif
                                                            </div>
                                                            @if ($pm->medicine && $pm->medicine->code)
                                                                <small class="text-muted">Code:
                                                                    {{ $pm->medicine->code }}</small>
                                                            @endif
                                                        </div>

                                                        <div class="bulk-medicine-fields" style="min-width: 280px;">
                                                            <div class="row g-1">
                                                                <div class="col-6">
                                                                    <input type="text"
                                                                        name="medicines[{{ $index }}][dosage]"
                                                                        class="form-control form-control-sm"
                                                                        placeholder="Dosage"
                                                                        value="{{ old('dosage', $pm->dosage) }}">
                                                                </div>
                                                                <div class="col-6">
                                                                    <input type="text"
                                                                        name="medicines[{{ $index }}][quantity]"
                                                                        class="form-control form-control-sm"
                                                                        placeholder="Qty"
                                                                        value="{{ old('quantity', $pm->quantity) }}">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>




                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="alert alert-info">
                                        <i class="ti ti-info-circle me-2"></i>No medicines currently assigned to this
                                        patient.
                                    </div>
                                @endif
                            </div>

                            <!-- Add New Medicines Section -->
                            <div class="mt-4 pt-3 border-top">
                                <h6 class="fw-bold mb-3 text-success">
                                    <i class="ti ti-plus me-1"></i> Add New Medicines
                                </h6>
                                <!-- Medicine Group Selector -->
                                <div class="mb-3">
                                    <label class="form-label fw-medium">Select Medicine Group (Optional)</label>
                                    {{-- ✅ ADDED name="medicine_group_id" --}}
                                    <select name="medicine_group_id" id="bulkGroupSelect" class="form-select">
                                        <option value="">-- Select group to add medicines --</option>
                                        @foreach ($medicineGroups as $group)
                                            <option value="{{ $group->id }}">{{ $group->name }}
                                                ({{ $group->medicines_count }} medicines)</option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Group Medicines Container -->
                                <div id="bulkNewMedicinesContainer" class="d-none">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="bulkNewCheckAll">
                                            <label class="form-check-label" for="bulkNewCheckAll">Select All</label>
                                        </div>
                                    </div>
                                    <div id="bulkNewMedicinesList" style="max-height: 300px; overflow-y: auto;"></div>
                                </div>

                                <!-- Extra Medicines -->
                                <div class="mt-3">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <h6 class="fw-bold mb-0">Custom Medicines</h6>
                                        <button type="button" class="btn btn-sm btn-outline-success"
                                            onclick="addBulkExtraMedicine()">
                                            <i class="ti ti-plus me-1"></i> Add Custom Medicine
                                        </button>
                                    </div>
                                    <div id="bulkExtraMedicinesContainer"></div>
                                </div>
                            </div>

                            <!-- Common Dates & Notes -->
                            <div class="row mt-4 pt-3 border-top">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Common Start Date (Optional)</label>
                                    <input type="date" name="start_date" class="form-control" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Common End Date (Optional)</label>
                                    <input type="date" name="end_date" class="form-control" required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">General Notes (Optional)</label>
                                <textarea name="notes" class="form-control" rows="2"
                                    placeholder="Optional instructions for all medicines..."></textarea>
                            </div>
                        </div>

                        <div class="modal-footer sticky-bottom bg-white border-top">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-info" id="bulkSubmitBtn" disabled>
                                <i class="ti ti-check me-1"></i> Update (<span id="bulkSubmitCount">0</span>)
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- 3. Report Preview Modal -->
        <div class="modal fade" id="reportPreviewModal" tabindex="-1">
            <div class="modal-dialog modal-xl modal-dialog-scrollable" style="max-width: 95%;">
                <div class="modal-content">
                    <div class="modal-header bg-light">
                        <h6 class="modal-title fw-bold"><i class="ti ti-file-certificate me-2"></i>Diagnosis Report
                            Preview</h6>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-0 bg-light">
                        <iframe id="reportFrame" src=""
                            style="width: 100%; height: calc(100vh - 120px); border: none;" class="d-block"></iframe>
                    </div>
                </div>
            </div>
        </div>

        <!-- 4. Reassign Medicines Modal -->
        <div class="modal fade" id="reassignMedicinesModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-scrollable">
                <div class="modal-content">
                    <form id="reassignMedicinesForm" action="{{ route('patients.medicines.reassign', $patient->id) }}"
                        method="POST">
                        @csrf @method('PUT')
                        <div class="modal-header sticky-top bg-white z-3">
                            <h5 class="modal-title"><i class="ti ti-refresh me-2 text-warning"></i>Reassign / Edit
                                Medicines</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body" style="max-height: calc(100vh - 200px); overflow-y: auto;">
                            <p class="text-muted mb-3">Edit or remove medicines currently assigned to
                                <strong>{{ $patient->first_name }} {{ $patient->last_name }}</strong></p>

                            <div id="reassignLoading" class="text-center py-4">
                                <div class="spinner-border text-warning" role="status"></div>
                                <p class="mt-2 text-muted small">Loading assigned medicines...</p>
                            </div>

                            <div id="reassignMedicinesContainer" class="d-none">
                                <div
                                    class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom sticky-top bg-white z-1">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="reassignCheckAll">
                                        <label class="form-check-label fw-medium" for="reassignCheckAll">Select All (Keep
                                            Assigned)</label>
                                    </div>
                                    <span class="badge bg-warning text-dark" id="reassignSelectedCount">0 selected</span>
                                </div>
                                <div id="reassignMedicinesList"
                                    style="max-height: 400px; overflow-y: auto; padding-right: 5px;"></div>

                                <div class="mt-4 pt-3 border-top">
                                    <h6 class="fw-bold mb-3 text-primary"><i class="ti ti-plus me-1"></i> Add New
                                        Medicines</h6>
                                    <div class="mb-3">
                                        <label class="form-label fw-medium">Select Medicine Group</label>
                                        <select name="medicine_group_id" id="reassignGroupSelect" class="form-select">
                                            <option value="">-- Select group to add medicines --</option>
                                            @foreach ($medicineGroups as $group)
                                                <option value="{{ $group->id }}">{{ $group->name }}
                                                    ({{ $group->medicines_count }} medicines)</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div id="reassignNewMedicinesContainer" class="d-none">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="reassignNewCheckAll">
                                                <label class="form-check-label" for="reassignNewCheckAll">Select
                                                    All</label>
                                            </div>
                                        </div>
                                        <div id="reassignNewMedicinesList" style="max-height: 250px; overflow-y: auto;">
                                        </div>
                                    </div>
                                    <div class="mt-3">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <h6 class="fw-bold mb-0">Extra Medicines</h6>
                                            <button type="button" class="btn btn-sm btn-outline-primary"
                                                onclick="addReassignExtraMedicine()">
                                                <i class="ti ti-plus me-1"></i> Add Extra Medicine
                                            </button>
                                        </div>
                                        <div id="reassignExtraMedicinesContainer"></div>
                                    </div>
                                </div>

                                <div class="row mt-4 pt-3 border-top">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Start Date</label>
                                        <input type="date" name="start_date" class="form-control">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">End Date</label>
                                        <input type="date" name="end_date" class="form-control">
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">General Notes</label>
                                    <textarea name="notes" class="form-control" rows="2" placeholder="Optional instructions..."></textarea>
                                </div>
                            </div>

                            <div id="reassignEmpty" class="alert alert-warning d-none">
                                <i class="ti ti-alert-circle me-2"></i>No medicines are currently assigned to this patient.
                            </div>
                        </div>
                        <div class="modal-footer sticky-bottom bg-white border-top">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-warning" id="reassignSubmitBtn" disabled>
                                <i class="ti ti-check me-1"></i> Update (<span id="reassignSubmitCount">0</span>)
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    @endsection

    @push('scripts')
        <script>
            window.patientId = {{ $patient->id }};
            let extraMedicineCounter = 0;
            let reassignExtraMedicineCounter = 0;

            // ===== ASSIGN MEDICINE GROUP MODAL LOGIC (ORIGINAL - UNCHANGED) =====
            let medicineCounter = 0;
            let customMedicineCounter = 0;

            document.addEventListener('DOMContentLoaded', function() {
                const patientId = window.patientId || {{ $patient->id }};

                // Medicine Group Change Handler
                const groupSelect = document.getElementById('medicineGroupSelect');
                if (groupSelect) {
                    groupSelect.addEventListener('change', function() {
                        const groupId = this.value;
                        if (!groupId) {
                            resetMedicinesUI();
                            return;
                        }
                        showLoading();
                        fetchMedicines(groupId, patientId);
                    });
                }

                // Check All Handler
                const checkAll = document.getElementById('checkAllMedicines');
                if (checkAll) {
                    checkAll.addEventListener('change', function(e) {
                        const checked = e.target.checked;
                        document.querySelectorAll('.medicine-checkbox').forEach(cb => {
                            cb.checked = checked;
                            toggleMedicineFields(cb, checked);
                        });
                        updateSubmitButton();
                    });
                }

                // Dynamic checkbox handler
                const medicinesList = document.getElementById('medicinesList');
                if (medicinesList) {
                    medicinesList.addEventListener('change', function(e) {
                        if (e.target.classList.contains('medicine-checkbox')) {
                            toggleMedicineFields(e.target, e.target.checked);
                            updateSubmitButton();
                        }
                    });
                }

                // Listen for changes in extra medicine dropdowns and name fields
                document.addEventListener('change', function(e) {
                    if (e.target.classList.contains('extra-medicine-select')) updateSubmitButton();
                });
                document.addEventListener('input', function(e) {
                    if (e.target.classList.contains('extra-medicine-name')) updateSubmitButton();
                });

                // Form submit handler
                const assignForm = document.getElementById('assignMedicineForm');
                if (assignForm) {
                    assignForm.addEventListener('submit', function(e) {
                        const checked = document.querySelectorAll('.medicine-checkbox:checked').length;
                        let extraFilled = 0;
                        document.querySelectorAll('.extra-medicine-item').forEach(item => {
                            const select = item.querySelector('.extra-medicine-select');
                            const nameField = item.querySelector('.extra-medicine-name');
                            if ((select && select.value) || (nameField && nameField.value.trim()))
                                extraFilled++;
                        });
                        if (checked === 0 && extraFilled === 0) {
                            e.preventDefault();
                            Swal.fire('Warning', 'Please select at least one medicine', 'warning');
                        }
                    });
                }
            });

            function fetchMedicines(groupId, patientId) {
                const url = `/medicine-groups/${groupId}/medicines?patient_id=${patientId}`;
                fetch(url)
                    .then(res => {
                        if (!res.ok) throw new Error(`Server responded with status ${res.status}`);
                        return res.json();
                    })
                    .then(data => {
                        hideLoading();
                        if (!data.medicines || data.medicines.length === 0) {
                            document.getElementById('noMedicines').classList.remove('d-none');
                            document.getElementById('medicinesContainer').classList.add('d-none');
                            return;
                        }
                        renderMedicines(data.medicines);
                    })
                    .catch(err => {
                        console.error('Fetch error:', err);
                        hideLoading();
                        Swal.fire('Error', 'Failed to load medicines', 'error');
                    });
            }

            function renderMedicines(medicines) {
                hideLoading();
                const container = document.getElementById('medicinesList');
                if (!medicines || medicines.length === 0) {
                    document.getElementById('noMedicines').classList.remove('d-none');
                    document.getElementById('medicinesContainer').classList.add('d-none');
                    return;
                }
                document.getElementById('noMedicines').classList.add('d-none');
                document.getElementById('medicinesContainer').classList.remove('d-none');

                container.innerHTML = medicines.map((med, i) => {
                    const dosage = med.dosage || '';
                    const quantity = med.quantity || '';
                    const displayName = med.custom_name || med.name || 'Unknown Medicine';
                    return `
        <div class="card border-0 shadow-sm mb-2 medicine-card" data-id="${med.id}">
            <div class="card-body py-2 px-3">
                <div class="d-flex align-items-start gap-2">
                    <input class="form-check-input medicine-checkbox mt-1" type="checkbox"
                        name="medicines[${i}][assign]" value="1" data-medicine-id="${med.id}"
                        ${med.already_assigned ? 'checked' : ''}>
                    <input type="hidden" name="medicines[${i}][medicine_id]" value="${med.id}">
                    ${med.already_assigned && med.patient_medicine_id ? `<input type="hidden" name="medicines[${i}][patient_medicine_id]" value="${med.patient_medicine_id}">` : ''}
                    <div class="flex-grow-1">
                        <input type="text" name="medicines[${i}][custom_name]" class="form-control form-control-sm mb-1"
                            placeholder="Medicine name" value="${displayName}" required>
                        ${med.code ? `<small class="text-muted">Code: ${med.code}</small>` : ''}
                        ${med.already_assigned ? `<span class="badge badge-soft-success fs-10 ms-1">Assigned</span>` : ''}
                    </div>
                    <div class="medicine-fields" style="min-width: 200px;">
                        <div class="row g-1">
                            <div class="col-6">
                                <input type="text" name="medicines[${i}][dosage]" class="form-control form-control-sm"
                                    placeholder="Dosage" value="${dosage}" ${med.already_assigned ? '' : 'disabled'}>
                            </div>
                            <div class="col-6">
                                <input type="text" name="medicines[${i}][quantity]" class="form-control form-control-sm"
                                    placeholder="Qty" value="${quantity}" ${med.already_assigned ? '' : 'disabled'}>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>`;
                }).join('');
                updateSelectAllState();
                updateSubmitButton();
            }

            function toggleMedicineFields(checkbox, enable) {
                const card = checkbox.closest('.medicine-card');
                if (!card) return;
                const fields = card.querySelector('.medicine-fields');
                if (!fields) return;
                fields.querySelectorAll('input, select').forEach(input => {
                    if (!input.name.includes('[assign]')) input.disabled = !enable;
                });
            }

            function updateSubmitButton() {
                const groupChecked = document.querySelectorAll('.medicine-checkbox:checked').length;
                let extraFilled = 0;
                document.querySelectorAll('.extra-medicine-item').forEach(item => {
                    const select = item.querySelector('.extra-medicine-select');
                    const nameField = item.querySelector('.extra-medicine-name');
                    if ((select && select.value) || (nameField && nameField.value.trim())) extraFilled++;
                });
                const totalSelected = groupChecked + extraFilled;
                const selectedCountEl = document.getElementById('selectedCount');
                const submitCountEl = document.getElementById('submitCount');
                const submitBtn = document.getElementById('submitAssignBtn');
                if (selectedCountEl) selectedCountEl.textContent = `${totalSelected} selected`;
                if (submitCountEl) submitCountEl.textContent = totalSelected;
                if (submitBtn) submitBtn.disabled = totalSelected === 0;
            }

            function updateSelectAllState() {
                const all = document.querySelectorAll('.medicine-checkbox');
                const checked = document.querySelectorAll('.medicine-checkbox:checked');
                const checkAll = document.getElementById('checkAllMedicines');
                if (all.length > 0 && checkAll) {
                    checkAll.checked = all.length === checked.length;
                    checkAll.indeterminate = checked.length > 0 && checked.length < all.length;
                }
            }

            function showLoading() {
                const loading = document.getElementById('medicinesLoading');
                const container = document.getElementById('medicinesContainer');
                const noMeds = document.getElementById('noMedicines');
                if (loading) loading.classList.remove('d-none');
                if (container) container.classList.add('d-none');
                if (noMeds) noMeds.classList.add('d-none');
            }

            function hideLoading() {
                const loading = document.getElementById('medicinesLoading');
                if (loading) loading.classList.add('d-none');
            }

            function resetMedicinesUI() {
                const container = document.getElementById('medicinesContainer');
                const noMeds = document.getElementById('noMedicines');
                const list = document.getElementById('medicinesList');
                const checkAll = document.getElementById('checkAllMedicines');
                const extraContainer = document.getElementById('extraMedicinesContainer');
                if (container) container.classList.add('d-none');
                if (noMeds) noMeds.classList.add('d-none');
                if (list) list.innerHTML = '';
                if (extraContainer) extraContainer.innerHTML = '';
                if (checkAll) checkAll.checked = false;
                extraMedicineCounter = 0;
                updateSubmitButton();
            }

            function addExtraMedicine() {
                extraMedicineCounter++;
                const container = document.getElementById('extraMedicinesContainer');
                const extraIndex = extraMedicineCounter;
                const extraMedHtml = `
    <div class="extra-medicine-item card border-0 bg-light mb-2" id="extraMed_${extraIndex}">
        <div class="card-body py-2 px-3">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h6 class="fw-bold mb-0 text-primary"><i class="ti ti-pill me-1"></i>Extra Medicine ${extraIndex}</h6>
                <button type="button" class="btn btn-sm btn-light text-danger" onclick="removeExtraMedicine(${extraIndex})" title="Remove"><i class="ti ti-x"></i></button>
            </div>
            <div class="row g-2">
                <div class="col-md-4">
                    <label class="form-label small">Select Medicine</label>
                    <select name="extra_medicines[${extraIndex}][medicine_id]" class="form-select form-select-sm extra-medicine-select" onchange="autoFillExtraMedicine(this)">
                        <option value="">-- Select medicine --</option>
                        @foreach (\App\Models\Medicine::where('is_active', true)->orderBy('name')->get() as $med)
                            <option value="{{ $med->id }}" data-name="{{ $med->name }}" data-dosage="{{ $med->dosage }}" data-quantity="{{ $med->quantity }}" data-route="{{ $med->route }}" data-instructions="{{ $med->instructions }}">{{ $med->name }} @if ($med->code)({{ $med->code }})@endif</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label small">Medicine Name <span class="text-danger">*</span></label>
                    <input type="text" name="extra_medicines[${extraIndex}][custom_name]" class="form-control form-control-sm extra-medicine-name" placeholder="Medicine name (editable)" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label small">Dosage</label>
                    <input type="text" name="extra_medicines[${extraIndex}][dosage]" class="form-control form-control-sm extra-dosage" placeholder="e.g., 1-0-1" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label small">Quantity</label>
                    <input type="text" name="extra_medicines[${extraIndex}][quantity]" class="form-control form-control-sm extra-quantity" placeholder="e.g., 30 tabs" required>
                </div>
            </div>
        </div>
    </div>`;
                container.insertAdjacentHTML('beforeend', extraMedHtml);
                updateSubmitButton();
            }

            function removeExtraMedicine(index) {
                const element = document.getElementById(`extraMed_${index}`);
                if (element) {
                    element.remove();
                    updateSubmitButton();
                }
            }

            function autoFillExtraMedicine(select) {
                const selectedOption = select.options[select.selectedIndex];
                updateSubmitButton();
                if (!selectedOption.value) return;
                const row = select.closest('.extra-medicine-item');
                const nameField = row.querySelector('.extra-medicine-name');
                if (nameField && selectedOption.dataset.name) {
                    nameField.value = selectedOption.dataset.name;
                }
                const dosageField = row.querySelector('.extra-dosage');
                if (dosageField && selectedOption.dataset.dosage) {
                    dosageField.value = selectedOption.dataset.dosage;
                }
                const qtyField = row.querySelector('.extra-quantity');
                if (qtyField && selectedOption.dataset.quantity) {
                    qtyField.value = selectedOption.dataset.quantity;
                }
                if (nameField) {
                    nameField.focus();
                    nameField.select();
                }
            }

            // ===== REASSIGN MEDICINES MODAL LOGIC =====
            document.addEventListener('DOMContentLoaded', function() {
                const reassignModal = document.getElementById('reassignMedicinesModal');
                if (reassignModal) {
                    reassignModal.addEventListener('show.bs.modal', function() {
                        loadReassignMedicines();
                    });
                }
                const reassignCheckAll = document.getElementById('reassignCheckAll');
                if (reassignCheckAll) {
                    reassignCheckAll.addEventListener('change', function(e) {
                        const checked = e.target.checked;
                        document.querySelectorAll('.reassign-checkbox').forEach(cb => {
                            cb.checked = checked;
                            toggleReassignFields(cb, checked);
                        });
                        updateReassignSubmitButton();
                    });
                }
                const reassignList = document.getElementById('reassignMedicinesList');
                if (reassignList) {
                    reassignList.addEventListener('change', function(e) {
                        if (e.target.classList.contains('reassign-checkbox')) {
                            toggleReassignFields(e.target, e.target.checked);
                            updateReassignSubmitButton();
                        }
                    });
                }
                const reassignForm = document.getElementById('reassignMedicinesForm');
                if (reassignForm) {
                    reassignForm.addEventListener('submit', function(e) {
                        const checkedExisting = document.querySelectorAll('.reassign-checkbox:checked').length;
                        const checkedNew = document.querySelectorAll('.reassign-new-checkbox:checked').length;
                        let extraFilled = 0;
                        document.querySelectorAll('#reassignExtraMedicinesContainer .extra-medicine-item')
                            .forEach(item => {
                                const select = item.querySelector('.extra-medicine-select');
                                const nameField = item.querySelector('.extra-medicine-name');
                                if ((select && select.value) || (nameField && nameField.value.trim()))
                                    extraFilled++;
                            });
                        if (checkedExisting === 0 && checkedNew === 0 && extraFilled === 0) {
                            e.preventDefault();
                            Swal.fire('Warning', 'Please select at least one medicine to keep or add',
                                'warning');
                        }
                    });
                }
            });

            function loadReassignMedicines() {
                const loading = document.getElementById('reassignLoading');
                const container = document.getElementById('reassignMedicinesContainer');
                const empty = document.getElementById('reassignEmpty');
                const list = document.getElementById('reassignMedicinesList');
                loading.classList.remove('d-none');
                container.classList.add('d-none');
                empty.classList.add('d-none');
                list.innerHTML = '';
                fetch(`/patients/{{ $patient->id }}/assigned-medicines`)
                    .then(res => {
                        if (!res.ok) throw new Error(`Server responded with status ${res.status}`);
                        return res.json();
                    })
                    .then(data => {
                        loading.classList.add('d-none');
                        if (!data.medicines || data.medicines.length === 0) {
                            empty.classList.remove('d-none');
                            return;
                        }
                        renderReassignMedicines(data.medicines);
                    })
                    .catch(err => {
                        console.error('Fetch error:', err);
                        loading.classList.add('d-none');
                        empty.classList.remove('d-none');
                        empty.innerHTML = `<i class="ti ti-alert-circle me-2"></i>Failed to load medicines: ${err.message}`;
                    });
            }

            function renderReassignMedicines(medicines) {
                const container = document.getElementById('reassignMedicinesContainer');
                const list = document.getElementById('reassignMedicinesList');
                container.classList.remove('d-none');
                list.innerHTML = medicines.map((med, i) => {
                    const dosage = med.dosage || '';
                    const quantity = med.quantity || '';
                    const displayName = med.custom_name || med.medicine_name || 'Unknown Medicine';
                    const groupName = med.group_name || 'Individual';
                    return `
        <div class="card border-0 shadow-sm mb-2 reassign-card" data-id="${med.id}">
            <div class="card-body py-2 px-3">
                <div class="d-flex align-items-start gap-2">
                    <input class="form-check-input reassign-checkbox mt-1" type="checkbox" name="medicines[${i}][keep]" value="1" data-patient-medicine-id="${med.id}" checked>
                    <input type="hidden" name="medicines[${i}][patient_medicine_id]" value="${med.id}">
                    <input type="hidden" name="medicines[${i}][medicine_id]" value="${med.medicine_id || ''}">
                    <div class="flex-grow-1">
                        <div class="d-flex align-items-center gap-2 mb-1">
                            <input type="text" name="medicines[${i}][custom_name]" class="form-control form-control-sm" placeholder="Medicine name" value="${displayName}" required>
                            <span class="badge bg-light text-dark border fs-10">${groupName}</span>
                        </div>
                        ${med.medicine_code ? `<small class="text-muted">Code: ${med.medicine_code}</small>` : ''}
                    </div>
                    <div class="reassign-fields" style="min-width: 280px;">
                        <div class="row g-1">
                            <div class="col-6"><input type="text" name="medicines[${i}][dosage]" class="form-control form-control-sm" placeholder="Dosage" value="${dosage}"></div>
                            <div class="col-6"><input type="text" name="medicines[${i}][quantity]" class="form-control form-control-sm" placeholder="Qty" value="${quantity}"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>`;
                }).join('');
                updateReassignSelectAllState();
                updateReassignSubmitButton();
            }

            document.addEventListener('DOMContentLoaded', function() {
                const reassignGroupSelect = document.getElementById('reassignGroupSelect');
                if (reassignGroupSelect) {
                    reassignGroupSelect.addEventListener('change', function() {
                        const groupId = this.value;
                        const container = document.getElementById('reassignNewMedicinesContainer');
                        const list = document.getElementById('reassignNewMedicinesList');
                        if (!groupId) {
                            container.classList.add('d-none');
                            list.innerHTML = '';
                            return;
                        }
                        list.innerHTML =
                            '<div class="text-center py-3"><div class="spinner-border spinner-border-sm text-primary" role="status"></div><p class="text-muted small mt-2 mb-0">Loading medicines...</p></div>';
                        container.classList.remove('d-none');
                        const patientId = {{ $patient->id }};
                        const url = `/medicine-groups/${groupId}/medicines?patient_id=${patientId}`;
                        fetch(url)
                            .then(res => {
                                if (!res.ok) throw new Error(`HTTP error! status: ${res.status}`);
                                return res.json();
                            })
                            .then(data => {
                                if (data.medicines && data.medicines.length > 0) {
                                    renderReassignNewMedicines(data.medicines);
                                } else {
                                    list.innerHTML =
                                        '<div class="alert alert-warning small">No medicines found in this group</div>';
                                }
                            })
                            .catch(err => {
                                console.error('Fetch error:', err);
                                list.innerHTML =
                                    `<div class="alert alert-danger small"><strong>Failed to load medicines</strong><br>Error: ${err.message}</div>`;
                            });
                    });
                }
            });

            const reassignNewCheckAll = document.getElementById('reassignNewCheckAll');
            if (reassignNewCheckAll) {
                reassignNewCheckAll.addEventListener('change', function(e) {
                    const checked = e.target.checked;
                    document.querySelectorAll('.reassign-new-checkbox').forEach(cb => {
                        cb.checked = checked;
                    });
                    updateReassignSubmitButton();
                });
            }

            function renderReassignNewMedicines(medicines) {
                const container = document.getElementById('reassignNewMedicinesContainer');
                const list = document.getElementById('reassignNewMedicinesList');
                container.classList.remove('d-none');
                list.innerHTML = medicines.map((med, i) => {
                    const dosage = med.dosage || '';
                    const quantity = med.quantity || '';
                    const displayName = med.custom_name || med.name || 'Unknown Medicine';
                    const code = med.code || '';
                    return `
        <div class="card border-0 shadow-sm mb-2 reassign-new-card" data-id="${med.id}">
            <div class="card-body py-2 px-3">
                <div class="d-flex align-items-start gap-2">
                    <input class="form-check-input reassign-new-checkbox mt-1" type="checkbox" name="new_medicines[${i}][assign]" value="1" data-medicine-id="${med.id}">
                    <input type="hidden" name="new_medicines[${i}][medicine_id]" value="${med.id}">
                    <div class="flex-grow-1">
                        <input type="text" name="new_medicines[${i}][custom_name]" class="form-control form-control-sm mb-1" placeholder="Medicine name" value="${displayName}">
                        ${code ? `<small class="text-muted">Code: ${code}</small>` : ''}
                    </div>
                    <div class="reassign-new-fields" style="min-width: 200px;">
                        <div class="row g-1">
                            <div class="col-6"><input type="text" name="new_medicines[${i}][dosage]" class="form-control form-control-sm" placeholder="Dosage" value="${dosage}"></div>
                            <div class="col-6"><input type="text" name="new_medicines[${i}][quantity]" class="form-control form-control-sm" placeholder="Qty" value="${quantity}"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>`;
                }).join('');
                updateReassignNewSelectAllState();
                updateReassignSubmitButton();
            }

            function updateReassignNewSelectAllState() {
                const all = document.querySelectorAll('.reassign-new-checkbox');
                const checked = document.querySelectorAll('.reassign-new-checkbox:checked');
                const checkAll = document.getElementById('reassignNewCheckAll');
                if (all.length > 0 && checkAll) {
                    checkAll.checked = all.length === checked.length;
                    checkAll.indeterminate = checked.length > 0 && checked.length < all.length;
                }
            }

            function addReassignExtraMedicine() {
                reassignExtraMedicineCounter++;
                const container = document.getElementById('reassignExtraMedicinesContainer');
                const index = reassignExtraMedicineCounter;
                const html = `
    <div class="extra-medicine-item card border-0 bg-light mb-2" id="reassignExtraMed_${index}">
        <div class="card-body py-2 px-3">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h6 class="fw-bold mb-0 text-primary"><i class="ti ti-pill me-1"></i>Extra Medicine ${index}</h6>
                <button type="button" class="btn btn-sm btn-light text-danger" onclick="removeReassignExtraMedicine(${index})"><i class="ti ti-x"></i></button>
            </div>
            <div class="row g-2">
                <div class="col-md-4">
                    <label class="form-label small">Select Medicine</label>
                    <select name="extra_medicines[${index}][medicine_id]" class="form-select form-select-sm extra-medicine-select" onchange="autoFillReassignExtraMedicine(this)">
                        <option value="">-- Select medicine --</option>
                        @foreach (\App\Models\Medicine::where('is_active', true)->orderBy('name')->get() as $med)
                            <option value="{{ $med->id }}" data-name="{{ $med->name }}" data-dosage="{{ $med->dosage }}" data-quantity="{{ $med->quantity }}">{{ $med->name }} @if ($med->code)({{ $med->code }})@endif</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label small">Medicine Name</label>
                    <input type="text" name="extra_medicines[${index}][custom_name]" class="form-control form-control-sm extra-medicine-name" placeholder="Medicine name">
                </div>
                <div class="col-md-2">
                    <label class="form-label small">Dosage</label>
                    <input type="text" name="extra_medicines[${index}][dosage]" class="form-control form-control-sm extra-dosage" placeholder="Dosage">
                </div>
                <div class="col-md-2">
                    <label class="form-label small">Quantity</label>
                    <input type="text" name="extra_medicines[${index}][quantity]" class="form-control form-control-sm extra-quantity" placeholder="Qty">
                </div>
            </div>
        </div>
    </div>`;
                container.insertAdjacentHTML('beforeend', html);
            }

            function removeReassignExtraMedicine(index) {
                const el = document.getElementById(`reassignExtraMed_${index}`);
                if (el) el.remove();
            }

            function autoFillReassignExtraMedicine(select) {
                const selectedOption = select.options[select.selectedIndex];
                if (!selectedOption.value) return;
                const row = select.closest('.extra-medicine-item');
                const nameField = row.querySelector('.extra-medicine-name');
                if (nameField && selectedOption.dataset.name) nameField.value = selectedOption.dataset.name;
                const dosageField = row.querySelector('.extra-dosage');
                if (dosageField && selectedOption.dataset.dosage) dosageField.value = selectedOption.dataset.dosage;
                const qtyField = row.querySelector('.extra-quantity');
                if (qtyField && selectedOption.dataset.quantity) qtyField.value = selectedOption.dataset.quantity;
            }

            function toggleReassignFields(checkbox, enable) {
                const card = checkbox.closest('.reassign-card');
                if (!card) return;
                const fields = card.querySelector('.reassign-fields');
                if (!fields) return;
                fields.querySelectorAll('input, select, textarea').forEach(input => {
                    input.disabled = !enable;
                });
            }

            function updateReassignSubmitButton() {
                const checkedExisting = document.querySelectorAll('.reassign-checkbox:checked').length;
                const checkedNew = document.querySelectorAll('.reassign-new-checkbox:checked').length;
                let extraFilled = 0;
                document.querySelectorAll('#reassignExtraMedicinesContainer .extra-medicine-item').forEach(item => {
                    const select = item.querySelector('.extra-medicine-select');
                    const nameField = item.querySelector('.extra-medicine-name');
                    if ((select && select.value) || (nameField && nameField.value.trim())) extraFilled++;
                });
                const total = checkedExisting + checkedNew + extraFilled;
                const selectedCountEl = document.getElementById('reassignSelectedCount');
                const submitCountEl = document.getElementById('reassignSubmitCount');
                const submitBtn = document.getElementById('reassignSubmitBtn');
                if (selectedCountEl) selectedCountEl.textContent = `${total} selected`;
                if (submitCountEl) submitCountEl.textContent = total;
                if (submitBtn) submitBtn.disabled = total === 0;
            }

            function updateReassignSelectAllState() {
                const all = document.querySelectorAll('.reassign-checkbox');
                const checked = document.querySelectorAll('.reassign-checkbox:checked');
                const checkAll = document.getElementById('reassignCheckAll');
                if (all.length > 0 && checkAll) {
                    checkAll.checked = all.length === checked.length;
                    checkAll.indeterminate = checked.length > 0 && checked.length < all.length;
                }
            }

            function confirmRemoveMedicine(id, name) {
                Swal.fire({
                    title: 'Remove Medicine?',
                    html: `Remove <strong>${name}</strong> from this patient's prescription?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, remove',
                    cancelButtonText: 'Cancel',
                    confirmButtonColor: '#dc3545'
                }).then((result) => {
                    if (result.isConfirmed) {
                        const form = document.createElement('form');
                        form.method = 'POST';
                        form.action = `/patient-medicines/${id}/remove`;
                        form.innerHTML = `@csrf @method('DELETE')`;
                        document.body.appendChild(form);
                        form.submit();
                    }
                });
            }

            // ===== REPORTS LOGIC =====
            document.addEventListener('DOMContentLoaded', function() {
                console.log('📊 Patient ID:', {{ $patient->id }});
                console.log(' History URL:', "{{ route('patients.reports.history', $patient->id) }}");
                loadReportsHistory();
            });

            function generateReport() {
                const date = document.getElementById('reportDate').value;
                if (!date) {
                    Swal.fire('Error', 'Please select a date', 'error');
                    return;
                }
                console.log('🔄 Generating report for date:', date);
                Swal.fire({
                    title: 'Generating Report...',
                    html: 'Please wait while we generate and save the report',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                fetch("{{ route('patients.reports.generate', $patient->id) }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            date: date
                        })
                    })
                    .then(response => {
                        console.log(' Response status:', response.status);
                        return response.json();
                    })
                    .then(data => {
                        console.log('📦 Response data:', data);
                        if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success!',
                                text: data.message,
                                timer: 2000,
                                showConfirmButton: false
                            });
                            loadReportsHistory();
                        } else {
                            Swal.fire('Error', data.message || 'Failed to generate report', 'error');
                        }
                    })
                    .catch(error => {
                        console.error('❌ Error:', error);
                        Swal.fire('Error', 'Failed to generate report: ' + error.message, 'error');
                    });
            }

            function loadReportsHistory() {
                const historyUrl = "{{ route('patients.reports.history', $patient->id) }}";
                console.log(' Fetching from:', historyUrl);
                fetch(historyUrl)
                    .then(response => {
                        console.log('📥 Response status:', response.status);
                        console.log(' Response OK:', response.ok);
                        return response.json();
                    })
                    .then(data => {
                        console.log('📦 Received data:', data);
                        console.log('📦 Reports count:', data.reports ? data.reports.length : 0);
                        const container = document.getElementById('reportsHistoryContainer');
                        if (!data.reports || data.reports.length === 0) {
                            console.log('⚠️ No reports found');
                            container.innerHTML =
                                `<div class="card border-dashed"><div class="card-body text-center py-5"><i class="ti ti-history fs-1 text-muted opacity-50"></i><h5 class="mt-3 text-muted">No Saved Reports</h5><p class="text-muted mb-0">Generate a report to see it here.</p></div></div>`;
                            return;
                        }
                        console.log('✅ Rendering', data.reports.length, 'reports');
                        let html = '<div class="card"><div class="card-body p-0"><div class="list-group list-group-flush">';
                        data.reports.forEach((report, index) => {
                            console.log('📄 Report', index + 1, ':', report);
                            html +=
                                `<div class="list-group-item"><div class="d-flex align-items-center justify-content-between"><div class="d-flex align-items-center"><span class="fw-bold text-primary me-3" style="min-width: 30px;">${index + 1}.</span><div><div class="fw-medium"><i class="ti ti-file-certificate me-1 text-info"></i>Report for ${report.date}</div></div></div><div class="d-flex gap-2"><button type="button" class="btn btn-sm btn-primary" onclick="previewReport('${report.preview_url}')"><i class="ti ti-eye"></i> Preview</button><a href="${report.download_url}" class="btn btn-sm btn-success" target="_blank"><i class="ti ti-download"></i> Download</a></div></div></div>`;
                        });
                        html += '</div></div></div>';
                        container.innerHTML = html;
                    })
                    .catch(error => {
                        console.error('❌ Fetch error:', error);
                        document.getElementById('reportsHistoryContainer').innerHTML =
                            `<div class="alert alert-danger"><strong>Error loading reports!</strong><br><small>${error.message}</small><br><small class="text-muted">Check console for details</small></div>`;
                    });
            }

            function previewReport(url) {
                console.log('️ Preview URL:', url);
                document.getElementById('reportFrame').src = url;
                const modal = new bootstrap.Modal(document.getElementById('reportPreviewModal'));
                modal.show();
            }

            function confirmDeletePatientReport(patientId, reportIndex, fileName) {
                Swal.fire({
                    title: 'Delete Report?',
                    html: `Are you sure you want to delete <strong>${fileName}</strong>?`,
                    text: 'This action cannot be undone!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc3545',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: '<i class="ti ti-trash me-1"></i> Yes, Delete',
                    cancelButtonText: 'Cancel',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'Deleting...',
                            html: 'Please wait',
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });
                        const form = document.getElementById('deleteReportForm');
                        form.action = `/reports/${patientId}/${reportIndex}`;
                        form.submit();
                    }
                });
            }

            function confirmDeleteAppointmentReport(appointmentDate, reportIndex, fileName) {
                Swal.fire({
                    title: 'Delete Appointment Report?',
                    html: `Are you sure you want to delete <strong>${fileName}</strong>?`,
                    text: 'This action cannot be undone!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc3545',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: '<i class="ti ti-trash me-1"></i> Yes, Delete',
                    cancelButtonText: 'Cancel',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'Deleting...',
                            html: 'Please wait',
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });
                        const form = document.getElementById('deleteAppointmentReportForm');
                        form.action = `/appointments/reports/${appointmentDate}/${reportIndex}`;
                        form.submit();
                    }
                });
            }
            // ===== BULK EDIT MEDICINES MODAL LOGIC =====
            let bulkExtraMedicineCounter = 0;

            document.addEventListener('DOMContentLoaded', function() {
                const bulkModal = document.getElementById('bulkEditMedicinesModal');
                if (bulkModal) {
                    bulkModal.addEventListener('show.bs.modal', function() {
                        initializeBulkEditModal();
                    });
                }

                // Check All Handler for existing medicines
                const bulkCheckAll = document.getElementById('bulkCheckAll');
                if (bulkCheckAll) {
                    bulkCheckAll.addEventListener('change', function(e) {
                        const checked = e.target.checked;
                        document.querySelectorAll('.bulk-medicine-checkbox').forEach(cb => {
                            cb.checked = checked;
                            toggleBulkMedicineFields(cb, checked);
                        });
                        updateBulkSubmitButton();
                    });
                }

                // Dynamic checkbox handler for existing medicines
                const existingList = document.querySelector('#existingMedicinesList');
                if (existingList) {
                    existingList.addEventListener('change', function(e) {
                        if (e.target.classList.contains('bulk-medicine-checkbox')) {
                            toggleBulkMedicineFields(e.target, e.target.checked);
                            updateBulkSubmitButton();
                        }
                    });
                }

                // Group select handler for new medicines
                const bulkGroupSelect = document.getElementById('bulkGroupSelect');
                if (bulkGroupSelect) {
                    bulkGroupSelect.addEventListener('change', function() {
                        const groupId = this.value;
                        const container = document.getElementById('bulkNewMedicinesContainer');
                        const list = document.getElementById('bulkNewMedicinesList');
                        if (!groupId) {
                            container.classList.add('d-none');
                            list.innerHTML = '';
                            return;
                        }
                        list.innerHTML =
                            '<div class="text-center py-3"><div class="spinner-border spinner-border-sm text-primary" role="status"></div><p class="text-muted small mt-2 mb-0">Loading medicines...</p></div>';
                        container.classList.remove('d-none');
                        const patientId = {{ $patient->id }};
                        const url = `/medicine-groups/${groupId}/medicines?patient_id=${patientId}`;
                        fetch(url)
                            .then(res => {
                                if (!res.ok) throw new Error(`HTTP error! status: ${res.status}`);
                                return res.json();
                            })
                            .then(data => {
                                if (data.medicines && data.medicines.length > 0) {
                                    renderBulkNewMedicines(data.medicines);
                                } else {
                                    list.innerHTML =
                                        '<div class="alert alert-warning small">No medicines found in this group</div>';
                                }
                            })
                            .catch(err => {
                                console.error('Fetch error:', err);
                                list.innerHTML =
                                    `<div class="alert alert-danger small"><strong>Failed to load medicines</strong><br>Error: ${err.message}</div>`;
                            });
                    });
                }

                // Select All for new medicines
                const bulkNewCheckAll = document.getElementById('bulkNewCheckAll');
                if (bulkNewCheckAll) {
                    bulkNewCheckAll.addEventListener('change', function(e) {
                        const checked = e.target.checked;
                        document.querySelectorAll('.bulk-new-checkbox').forEach(cb => {
                            cb.checked = checked;
                        });
                        updateBulkSubmitButton();
                    });
                }

                // Form submit handler
                const bulkForm = document.getElementById('bulkEditMedicinesForm');
                if (bulkForm) {
                    bulkForm.addEventListener('submit', function(e) {
                        const keptExisting = document.querySelectorAll('.bulk-medicine-checkbox:checked')
                        .length;
                        const newFromGroup = document.querySelectorAll('.bulk-new-checkbox:checked').length;
                        let customFilled = 0;
                        document.querySelectorAll('#bulkExtraMedicinesContainer .bulk-extra-medicine-item')
                            .forEach(item => {
                                const select = item.querySelector('.bulk-extra-medicine-select');
                                const nameField = item.querySelector('.bulk-extra-medicine-name');
                                if ((select && select.value) || (nameField && nameField.value.trim()))
                                    customFilled++;
                            });
                        if (keptExisting === 0 && newFromGroup === 0 && customFilled === 0) {
                            e.preventDefault();
                            Swal.fire('Warning', 'Please keep at least one medicine or add a new one',
                                'warning');
                        }
                    });
                }
            });

            function initializeBulkEditModal() {
                updateBulkSubmitButton();
            }

            function toggleBulkMedicineFields(checkbox, enable) {
                const card = checkbox.closest('.bulk-medicine-card');
                if (!card) return;
                const fields = card.querySelectorAll('input:not([type="checkbox"]), select, textarea');
                fields.forEach(input => {
                    if (!input.name.includes('[keep]')) {
                        input.disabled = !enable;
                    }
                });
            }

            function removeBulkMedicine(id) {
                const card = document.querySelector(`.bulk-medicine-card[data-id="${id}"]`);
                if (card) {
                    const checkbox = card.querySelector('.bulk-medicine-checkbox');
                    if (checkbox) {
                        checkbox.checked = false;
                        toggleBulkMedicineFields(checkbox, false);
                        updateBulkSubmitButton();
                    }
                }
            }

            function renderBulkNewMedicines(medicines) {
                const container = document.getElementById('bulkNewMedicinesContainer');
                const list = document.getElementById('bulkNewMedicinesList');
                container.classList.remove('d-none');

                let html = '';
                medicines.forEach((med, i) => {
                    const dosage = med.dosage || '';
                    const quantity = med.quantity || '';
                    const displayName = med.custom_name || med.name || 'Unknown Medicine';
                    const code = med.code || '';

                    html += `
        <div class="card border-0 shadow-sm mb-2 bulk-new-medicine-card" data-id="${med.id}">
            <div class="card-body py-2 px-3">
                <div class="d-flex align-items-start gap-2">
                    <input class="form-check-input bulk-new-checkbox mt-1" type="checkbox"
                           name="new_medicines[${i}][assign]" value="1" data-medicine-id="${med.id}">
                    <input type="hidden" name="new_medicines[${i}][medicine_id]" value="${med.id}">
                    
                    <div class="flex-grow-1">
                        <input type="text" name="new_medicines[${i}][custom_name]"
                               class="form-control form-control-sm mb-1" 
                               placeholder="Medicine name" value="${displayName}">
                        ${code ? `<small class="text-muted">Code: ${code}</small>` : ''}
                    </div>
                    
                    <div class="bulk-new-fields" style="min-width: 200px;">
                        <div class="row g-1">
                            <div class="col-6">
                                <input type="text" name="new_medicines[${i}][dosage]" 
                                       class="form-control form-control-sm" 
                                       placeholder="Dosage" value="${dosage}">
                            </div>
                            <div class="col-6">
                                <input type="text" name="new_medicines[${i}][quantity]" 
                                       class="form-control form-control-sm" 
                                       placeholder="Qty" value="${quantity}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>`;
                });

                list.innerHTML = html;
                updateBulkNewSelectAllState();
                updateBulkSubmitButton();
            }

            function updateBulkNewSelectAllState() {
                const all = document.querySelectorAll('.bulk-new-checkbox');
                const checked = document.querySelectorAll('.bulk-new-checkbox:checked');
                const checkAll = document.getElementById('bulkNewCheckAll');
                if (all.length > 0 && checkAll) {
                    checkAll.checked = all.length === checked.length;
                    checkAll.indeterminate = checked.length > 0 && checked.length < all.length;
                }
            }

            function addBulkExtraMedicine() {
                bulkExtraMedicineCounter++;
                const container = document.getElementById('bulkExtraMedicinesContainer');
                const index = bulkExtraMedicineCounter;

                const html = `
    <div class="bulk-extra-medicine-item card border-0 bg-light mb-2" id="bulkExtraMed_${index}">
        <div class="card-body py-2 px-3">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h6 class="fw-bold mb-0 text-success">
                    <i class="ti ti-pill me-1"></i>Custom Medicine ${index}
                </h6>
                <button type="button" class="btn btn-sm btn-light text-danger" 
                        onclick="removeBulkExtraMedicine(${index})">
                    <i class="ti ti-x"></i>
                </button>
            </div>
            <div class="row g-2">
                <div class="col-md-4">
                    <label class="form-label small">Select Medicine</label>
                    <select name="extra_medicines[${index}][medicine_id]" 
                            class="form-select form-select-sm bulk-extra-medicine-select" 
                            onchange="autoFillBulkExtraMedicine(this)">
                        <option value="">-- Select medicine --</option>
                        @foreach (\App\Models\Medicine::where('is_active', true)->orderBy('name')->get() as $med)
                            <option value="{{ $med->id }}" 
                                    data-name="{{ $med->name }}" 
                                    data-dosage="{{ $med->dosage }}" 
                                    data-quantity="{{ $med->quantity }}">
                                {{ $med->name }} @if ($med->code)({{ $med->code }})@endif
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label small">Medicine Name</label>
                    <input type="text" name="extra_medicines[${index}][custom_name]" 
                           class="form-control form-control-sm bulk-extra-medicine-name" 
                           placeholder="Medicine name">
                </div>
                <div class="col-md-2">
                    <label class="form-label small">Dosage</label>
                    <input type="text" name="extra_medicines[${index}][dosage]" 
                           class="form-control form-control-sm bulk-extra-dosage" 
                           placeholder="Dosage">
                </div>
                <div class="col-md-2">
                    <label class="form-label small">Quantity</label>
                    <input type="text" name="extra_medicines[${index}][quantity]" 
                           class="form-control form-control-sm bulk-extra-quantity" 
                           placeholder="Qty">
                </div>
            </div>
        </div>
    </div>`;

                container.insertAdjacentHTML('beforeend', html);
                updateBulkSubmitButton();
            }

            function removeBulkExtraMedicine(index) {
                const el = document.getElementById(`bulkExtraMed_${index}`);
                if (el) {
                    el.remove();
                    updateBulkSubmitButton();
                }
            }

            function autoFillBulkExtraMedicine(select) {
                const selectedOption = select.options[select.selectedIndex];
                if (!selectedOption.value) return;

                const row = select.closest('.bulk-extra-medicine-item');
                const nameField = row.querySelector('.bulk-extra-medicine-name');
                if (nameField && selectedOption.dataset.name) {
                    nameField.value = selectedOption.dataset.name;
                }
                const dosageField = row.querySelector('.bulk-extra-dosage');
                if (dosageField && selectedOption.dataset.dosage) {
                    dosageField.value = selectedOption.dataset.dosage;
                }
                const qtyField = row.querySelector('.bulk-extra-quantity');
                if (qtyField && selectedOption.dataset.quantity) {
                    qtyField.value = selectedOption.dataset.quantity;
                }
                if (nameField) {
                    nameField.focus();
                    nameField.select();
                }
                updateBulkSubmitButton();
            }

            function updateBulkSubmitButton() {
                const keptExisting = document.querySelectorAll('.bulk-medicine-checkbox:checked').length;
                const newFromGroup = document.querySelectorAll('.bulk-new-checkbox:checked').length;
                let customFilled = 0;

                document.querySelectorAll('#bulkExtraMedicinesContainer .bulk-extra-medicine-item').forEach(item => {
                    const select = item.querySelector('.bulk-extra-medicine-select');
                    const nameField = item.querySelector('.bulk-extra-medicine-name');
                    if ((select && select.value) || (nameField && nameField.value.trim())) {
                        customFilled++;
                    }
                });

                const total = keptExisting + newFromGroup + customFilled;
                const selectedCountEl = document.getElementById('bulkSelectedCount');
                const submitCountEl = document.getElementById('bulkSubmitCount');
                const submitBtn = document.getElementById('bulkSubmitBtn');

                if (selectedCountEl) selectedCountEl.textContent = `${total} selected`;
                if (submitCountEl) submitCountEl.textContent = total;
                if (submitBtn) submitBtn.disabled = total === 0;
            }
        </script>
    @endpush
