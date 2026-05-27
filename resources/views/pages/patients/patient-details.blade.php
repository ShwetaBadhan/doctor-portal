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
                                <!-- Patient Header Card -->
                                <p class="text-primary mb-1">
                                    #{{ $patient->patient_id }}

                                </p>
                                <h5 class="mb-1">
                                    <a href="javascript:void(0);" class="fw-bold">
                                        {{ $patient->first_name }} {{ $patient->last_name }}
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
                            {{-- <div class="mb-4">
                            <a href="tel:{{ $patient->phone }}" class="btn btn-outline-white shadow-sm rounded-circle d-inline-flex align-items-center p-2 fs-14 me-2" title="Call">
                                <i class="ti ti-phone"></i>
                            </a>
                            <a href="mailto:{{ $patient->email }}" class="btn btn-outline-white shadow-sm rounded-circle d-inline-flex align-items-center p-2 fs-14 me-2" title="Email">
                                <i class="ti ti-message-circle"></i>
                            </a>
                            <a href="javascript:void(0);" class="btn btn-outline-white shadow-sm rounded-circle d-inline-flex align-items-center p-2 fs-14" title="Video">
                                <i class="ti ti-video"></i>
                            </a>
                        </div> --}}
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
                            <h5 class="fw-bold mb-0">
                                <i class="ti ti-heart-rate-monitor me-1"></i>Vital Signs
                            </h5>
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
                                    ($latestAppointment->bp || $latestAppointment->temp || $latestAppointment->pulse || $latestAppointment->weight))
                                <div class="row">
                                    @php
                                        $vitals = [
                                            [
                                                'key' => 'bp',
                                                'label' => 'Blood Pressure',
                                                'icon' => 'ti ti-droplet',
                                                'unit' => '',
                                            ],
                                            [
                                                'key' => 'pulse',
                                                'label' => 'Heart Rate',
                                                'icon' => 'ti ti-heart',
                                                'unit' => 'bpm',
                                            ],
                                            [
                                                'key' => 'temp',
                                                'label' => 'Temperature',
                                                'icon' => 'ti ti-temperature',
                                                'unit' => '°F',
                                            ],
                                            [
                                                'key' => 'weight',
                                                'label' => 'Weight',
                                                'icon' => 'ti ti-weight',
                                                'unit' => 'kg',
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

                                    <!-- Tongue, Nails, Cerebral Fluid -->
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

                                    <!-- Vital Notes -->
                                    @if ($latestAppointment->vital_notes)
                                        <div class="col-12 mt-2">
                                            <small class="text-muted d-block">Notes</small>
                                            <p class="mb-0 text-muted small">{{ $latestAppointment->vital_notes }}</p>
                                        </div>
                                    @endif
                                </div>

                                <!-- View All Appointments Link -->
                                <div class="mt-3 text-end">
                                    <a href="#appointments" data-bs-toggle="tab" class="btn btn-sm btn-outline-primary">
                                        <i class="ti ti-list me-1"></i> View All Visits
                                    </a>
                                </div>
                            @else
                                <!-- Empty State -->
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
                                    <h6 class="fw-bold mb-0 text-success"><i class="ti ti-circle-check me-1"></i>Existing
                                        Symptoms</h6>
                                </div>
                                <div class="card-body">
                                    @if ($patient->existing_symptoms && count($patient->existing_symptoms) > 0)
                                        <div class="d-flex flex-wrap gap-2">
                                            @foreach ($patient->existing_symptoms as $symptom)
                                                <span
                                                    class="badge bg-success bg-opacity-10 text-success border border-success">{{ $symptom }}</span>
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
                                    <h6 class="fw-bold mb-0 text-danger"><i class="ti ti-circle-x me-1"></i>Non-Existing
                                        Symptoms</h6>
                                </div>
                                <div class="card-body">
                                    @if ($patient->non_existing_symptoms && count($patient->non_existing_symptoms) > 0)
                                        <div class="d-flex flex-wrap gap-2">
                                            @foreach ($patient->non_existing_symptoms as $symptom)
                                                <span
                                                    class="badge bg-danger bg-opacity-10 text-danger border border-danger">{{ $symptom }}</span>
                                            @endforeach
                                        </div>
                                    @else
                                        <p class="text-muted mb-0">No non-existing symptoms recorded.</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @if ($patient->cp || $patient->medical_notes)
                        <div class="row mt-3">
                            @if ($patient->cp)
                                <div class="col-md-4">
                                    <div class="card border">
                                        <div class="card-body">
                                            <h6 class="fw-bold mb-2">C.P (Cerebral Palsy)</h6>
                                            <p class="mb-2"><strong>Status:</strong>
                                                @if ($patient->cp == 'yes')
                                                <span class="badge bg-danger">Yes</span>@else<span
                                                        class="badge bg-secondary">No</span>
                                                @endif
                                            </p>
                                            @if ($patient->cp_movement && count($patient->cp_movement) > 0)
                                                <p class="mb-0"><strong>Movement:</strong><br>
                                                    @foreach ($patient->cp_movement as $movement)
                                                        <span
                                                            class="badge bg-info text-dark">{{ str_replace('_', ' ', ucfirst($movement)) }}</span>
                                                    @endforeach
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endif
                            @if ($patient->medical_notes)
                                <div class="col-md-{{ $patient->cp ? '8' : '12' }}">
                                    <div class="card border">
                                        <div class="card-body">
                                            <h6 class="fw-bold mb-2">Medical Notes</h6>
                                            <p class="mb-0 text-muted">{{ nl2br(e($patient->medical_notes)) }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>

                <!-- Medicines Tab (NEW) -->
                <div class="tab-pane" id="medicines">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="fw-bold mb-0"><i class="ti ti-pills me-2 text-primary"></i>Prescribed Medicines</h6>
                        @can('assign-medicines-to-patients')
                            <!-- Assign Medicine Group Button -->
                            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                data-bs-target="#assignMedicineModal">
                                <i class="ti ti-plus me-1"></i> Assign Medicine Group
                            </button>
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
                                                        <div class="fw-medium">{{ $pm->medicine->name ?? 'Unknown' }}
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
                                                                onclick="confirmRemoveMedicine({{ $pm->id }}, '{{ $pm->medicine->name ?? 'Medicine' }}')"
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
                                                                    <p class="fw-medium mb-3">
                                                                        {{ $pm->medicine->name ?? 'Medicine' }}</p>
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
<h6 class="fw-bold mb-3">
    <i class="ti ti-history me-2"></i>Medicine Assignment History
</h6>

@php
    $allMedicines = \App\Models\PatientMedicine::where('patient_id', $patient->id)
        ->with(['medicine', 'medicineGroup'])
        ->orderBy('created_at', 'desc')
        ->get();
@endphp

@if($allMedicines->count())
    <div class="card">
        <div class="card-body">
            <div class="list-group list-group-flush">
                @foreach($allMedicines as $index => $history)
                    <div class="list-group-item px-0 py-2 {{ !$history->is_active ? 'bg-light' : '' }}">
                        <div class="d-flex align-items-center">
                            <!-- Serial Number -->
                            <span class="fw-bold text-primary me-3" style="min-width: 30px;">
                                {{ $loop->iteration }}.
                            </span>

                            <!-- Medicine Name -->
                            <div class="flex-grow-1">
                                <span class="fw-medium">
                                    {{ $history->medicine->name ?? 'Unknown' }}
                                </span>

                                <!-- Dosage, Quantity, Instructions -->
                                <span class="text-muted mx-2">
                                    @if($history->dosage || $history->quantity || $history->instructions)
                                        ---->
                                        @if($history->dosage) {{ $history->dosage }} @endif
                                        @if($history->quantity) ----> {{ $history->quantity }} @endif
                                        @if($history->instructions) ({{ $history->instructions }}) @endif
                                    @endif
                                </span>

                                <!-- Status Badge -->
                                @if(!$history->is_active)
                                    <span class="badge bg-secondary ms-2 fs-11">Removed</span>
                                @endif
                            </div>

                            <!-- Date -->
                            <small class="text-muted">
                                {{ $history->created_at->format('d M Y') }}
                            </small>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@else
    <div class="card border-dashed">
        <div class="card-body text-center py-5">
            <i class="ti ti-history fs-1 text-muted opacity-50"></i>
            <h5 class="mt-3 text-muted">No Medicine History</h5>
            <p class="text-muted mb-0">Medicine assignment history will appear here.</p>
        </div>
    </div>
@endif
                </div>

                <!-- Test Reports Tab (NEW) -->
                <div class="tab-pane" id="reports">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="fw-bold mb-0"><i class="ti ti-file me-2 text-primary"></i>Test Reports & Documents</h6>
                        @can('upload-patient-reports')
                            <!-- Upload Button -->
                            <form action="{{ route('reports.upload', $patient->id) }}" method="POST"
                                enctype="multipart/form-data" class="d-inline">
                                @csrf
                                <div class="input-group input-group-sm" style="max-width: 300px;">
                                    <input type="file" name="reports[]" class="form-control" multiple
                                        accept=".pdf,.jpg,.jpeg,.png,.doc,.docx" required>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="ti ti-upload me-1"></i> Upload
                                    </button>
                                </div>
                                <small class="text-muted d-block mt-1">PDF, JPG, PNG, DOC (Max 5MB each)</small>
                            </form>
                        @endcan
                    </div>

                    @if ($patient->test_reports && count($patient->test_reports))
                        <div class="row">
                            @foreach ($patient->test_reports as $index => $reportPath)
                                <div class="col-md-4 col-lg-3 mb-3">
                                    <div class="card border h-100">
                                        <div class="card-body text-center">
                                            @php
                                                $ext = pathinfo($reportPath, PATHINFO_EXTENSION);
                                                $icons = [
                                                    'pdf' => 'ti ti-file-text text-danger',
                                                    'jpg' => 'ti ti-photo text-primary',
                                                    'jpeg' => 'ti ti-photo text-primary',
                                                    'png' => 'ti ti-photo text-primary',
                                                    'doc' => 'ti ti-file-text text-info',
                                                    'docx' => 'ti ti-file-text text-info',
                                                ];
                                                $icon = $icons[$ext] ?? 'ti ti-file text-muted';
                                                $fileName = basename($reportPath);
                                            @endphp

                                            <div class="avatar avatar-lg bg-light rounded-circle mb-2 mx-auto">
                                                <i class="{{ $icon }} fs-24"></i>
                                            </div>

                                            <p class="small text-truncate mb-2" title="{{ $fileName }}">
                                                {{ $fileName }}</p>
                                            <p class="fs-11 text-muted mb-2">
                                                {{ \Carbon\Carbon::parse(filemtime(storage_path('app/public/' . $reportPath)))->format('d M Y') }}
                                            </p>

                                            <div class="btn-group btn-group-sm">
                                                @can('view-patient-reports')
                                                    <a href="{{ Storage::url($reportPath) }}" class="btn btn-light"
                                                        target="_blank" title="View">
                                                        <i class="ti ti-eye"></i>
                                                    </a>
                                                @endcan
                                                @can('download-patient-reports')
                                                    <a href="{{ Storage::url($reportPath) }}" class="btn btn-light" download
                                                        title="Download">
                                                        <i class="ti ti-download"></i>
                                                    </a>
                                                @endcan
                                                @can('delete-patient-reports')
                                                    <form action="{{ route('reports.delete', [$patient->id, $index]) }}"
                                                        method="POST" class="d-inline"
                                                        onsubmit="return confirm('Delete this report?')">
                                                        @csrf @method('DELETE')
                                                        <button type="submit" class="btn btn-light text-danger"
                                                            title="Delete">
                                                            <i class="ti ti-trash"></i>
                                                        </button>
                                                    </form>
                                                @endcan
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
                        <h6 class="fw-bold mb-0">
                            <i class="ti ti-calendar-event me-2 text-primary"></i>Appointments for
                            {{ $patient->first_name }}
                        </h6>
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
                                                    <td>
                                                        <span class="badge bg-light text-dark border">
                                                            {{ ucfirst(str_replace('_', ' ', $appointment->appointment_type)) }}
                                                        </span>
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
                                                                class="btn btn-light" title="View">
                                                                <i class="ti ti-eye"></i>
                                                            </a>
                                                            <a href="{{ route('appointments.edit', $appointment) }}"
                                                                class="btn btn-light" title="Edit">
                                                                <i class="ti ti-edit"></i>
                                                            </a>
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
                <!-- Vital Signs Tab -->
                <div class="tab-pane" id="vitals">

                    <!-- Summary Cards (Latest Vitals) -->
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

                    <!-- Vital History Table -->
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h6 class="fw-bold mb-0">
                                <i class="ti ti-history me-2"></i>Vital Signs History
                            </h6>
                            <span class="badge bg-light text-dark">
                                {{ $appointmentsWithVitals->count() }} visits with vitals
                            </span>
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
                                                <th>VAT/PIT/Kuff</th>
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
                                                                class="badge bg-light text-dark border">{{ $apt->bp }}</span>
                                                        @else
                                                            <span class="text-muted">-</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if ($apt->temp)
                                                            <span
                                                                class="badge bg-light text-dark border">{{ $apt->temp }}°F</span>
                                                        @else
                                                            <span class="text-muted">-</span>
                                                        @endif
                                                    </td>
                                                    <td>{{ $apt->pulse ?? '-' }}</td>
                                                    <td>{{ $apt->weight ?? '-' }}</td>
                                                    <td>
                                                        <small class="text-muted">
                                                            @if ($apt->vat)
                                                                VAT:{{ $apt->vat }}
                                                            @endif
                                                            @if ($apt->pit)
                                                                | PIT:{{ $apt->pit }}
                                                            @endif
                                                            @if ($apt->kuff)
                                                                | Kuff:{{ $apt->kuff }}
                                                            @endif
                                                            @if (!$apt->vat && !$apt->pit && !$apt->kuff)
                                                                -
                                                            @endif
                                                        </small>
                                                    </td>
                                                    <td>
                                                        <small class="text-muted">
                                                            @if ($apt->tongue)
                                                                👅 {{ $apt->tongue }}
                                                            @endif
                                                            @if ($apt->nails)
                                                                💅 {{ $apt->nails }}
                                                            @endif
                                                            @if ($apt->cerebral_fluid)
                                                                🧠 {{ ucfirst($apt->cerebral_fluid) }}
                                                            @endif
                                                            @if (!$apt->tongue && !$apt->nails && !$apt->cerebral_fluid)
                                                                -
                                                            @endif
                                                        </small>
                                                    </td>
                                                    <td>
                                                        @if ($apt->vital_notes)
                                                            <span class="text-truncate d-inline-block"
                                                                style="max-width: 150px;"
                                                                title="{{ $apt->vital_notes }}">
                                                                {{ Str::limit($apt->vital_notes, 30) }}
                                                            </span>
                                                        @else
                                                            <span class="text-muted">-</span>
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
        </div>

        @include('components.copyright')
    </div>
<!-- Assign Medicine Group Modal (With Extra Medicines Option) -->
<div class="modal fade" id="assignMedicineModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <form id="assignMedicineForm" action="{{ route('patients.medicines.assign-custom', $patient->id) }}" method="POST">
                @csrf
                <div class="modal-header sticky-top bg-white z-3">
                    <h5 class="modal-title"><i class="ti ti-pills me-2"></i>Assign Medicines</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" style="max-height: calc(100vh - 200px); overflow-y: auto;">

                    <p class="text-muted mb-3">
                        Select medicines from group for <strong>{{ $patient->first_name }} {{ $patient->last_name }}</strong>
                    </p>

                    <!-- Medicine Group Selector -->
                    <div class="mb-3 sticky-top bg-white z-2 pb-2 border-bottom">
                        <label class="form-label fw-medium">Medicine Group <span class="text-danger">*</span></label>
                        <select name="medicine_group_id" id="medicineGroupSelect" class="form-select" required>
                            <option value="">Select group...</option>
                            @foreach ($medicineGroups as $group)
                                <option value="{{ $group->id }}" data-count="{{ $group->medicines_count }}">
                                    {{ $group->name }} @if ($group->code)({{ $group->code }})@endif 
                                    - {{ $group->medicines_count }} medicines
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
                        <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom sticky-top bg-white z-1">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="checkAllMedicines">
                                <label class="form-check-label fw-medium" for="checkAllMedicines">Select All</label>
                            </div>
                            <span class="badge bg-light text-dark" id="selectedCount">0 selected</span>
                        </div>

                        <!-- Group Medicines List -->
                        <div id="medicinesList" class="space-y-2" style="max-height: 300px; overflow-y: auto; padding-right: 5px;">
                            <!-- Dynamic content injected here -->
                        </div>

                        <!-- ===== EXTRA MEDICINES SECTION ===== -->
                        <div class="mt-4 pt-3 border-top">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="fw-bold mb-0">
                                    <i class="ti ti-plus me-1 text-primary"></i>Extra Medicines
                                </h6>
                                <button type="button" class="btn btn-sm btn-outline-primary" onclick="addExtraMedicine()">
                                    <i class="ti ti-plus me-1"></i> Add Extra Medicine
                                </button>
                            </div>
                            
                            <!-- Extra Medicines Container -->
                            <div id="extraMedicinesContainer">
                                <!-- Extra medicines will be added here -->
                            </div>
                            <small class="text-muted">
                                <i class="ti ti-info-circle me-1"></i>
                                Add medicines that are not part of this group
                            </small>
                        </div>
                        <!-- ===== END EXTRA MEDICINES SECTION ===== -->

                        <!-- Date & Notes Section -->
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

                    <!-- Empty State -->
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


@endsection


@push('scripts')
<script>
    window.patientId = {{ $patient->id }};
    let extraMedicineCounter = 0;

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

    // Add extra medicine row
    function addExtraMedicine() {
        extraMedicineCounter++;
        const container = document.getElementById('extraMedicinesContainer');
        const extraIndex = extraMedicineCounter;
        
        const extraMedHtml = `
            <div class="extra-medicine-item card border-0 bg-light mb-2" id="extraMed_${extraIndex}">
                <div class="card-body py-2 px-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h6 class="fw-bold mb-0 text-primary">
                            <i class="ti ti-pill me-1"></i>Extra Medicine ${extraIndex}
                        </h6>
                        <button type="button" class="btn btn-sm btn-light text-danger" 
                                onclick="removeExtraMedicine(${extraIndex})" title="Remove">
                            <i class="ti ti-x"></i>
                        </button>
                    </div>
                    <div class="row g-2">
                        <div class="col-md-6">
                            <label class="form-label small">Select Medicine <span class="text-danger">*</span></label>
                            <select name="extra_medicines[${extraIndex}][medicine_id]" 
                                    class="form-select form-select-sm extra-medicine-select" 
                                    required onchange="autoFillExtraMedicine(this)">
                                <option value="">-- Select medicine --</option>
                                @foreach(\App\Models\Medicine::where('is_active', true)->orderBy('name')->get() as $med)
                                    <option value="{{ $med->id }}" 
                                            data-dosage="{{ $med->dosage }}"
                                            data-quantity="{{ $med->quantity }}"
                                            data-route="{{ $med->route }}"
                                            data-instructions="{{ $med->instructions }}">
                                        {{ $med->name }} @if($med->code)({{ $med->code }})@endif
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small">Dosage</label>
                            <input type="text" name="extra_medicines[${extraIndex}][dosage]" 
                                   class="form-control form-control-sm extra-dosage" 
                                   placeholder="e.g., 1-0-1" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small">Quantity</label>
                            <input type="text" name="extra_medicines[${extraIndex}][quantity]" 
                                   class="form-control form-control-sm extra-quantity" 
                                   placeholder="e.g., 30 tabs" required>
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        container.insertAdjacentHTML('beforeend', extraMedHtml);
        updateSubmitButton();
    }

    // Remove extra medicine
    function removeExtraMedicine(index) {
        const element = document.getElementById(`extraMed_${index}`);
        if (element) {
            element.remove();
            updateSubmitButton();
        }
    }

    // Auto-fill medicine details when selected
    function autoFillExtraMedicine(select) {
        const selectedOption = select.options[select.selectedIndex];
        updateSubmitButton();
        
        if (!selectedOption.value) return;
        
        const row = select.closest('.extra-medicine-item');
        const dosageField = row.querySelector('.extra-dosage');
        const qtyField = row.querySelector('.extra-quantity');
        
        if (dosageField && selectedOption.dataset.dosage) {
            dosageField.value = selectedOption.dataset.dosage;
        }
        if (qtyField && selectedOption.dataset.quantity) {
            qtyField.value = selectedOption.dataset.quantity;
        }
    }

    // Initialize when DOM is loaded
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

        // Listen for changes in extra medicine dropdowns
        document.addEventListener('change', function(e) {
            if (e.target.classList.contains('extra-medicine-select')) {
                updateSubmitButton();
            }
        });

        // Form submit handler
        const assignForm = document.getElementById('assignMedicineForm');
        if (assignForm) {
            assignForm.addEventListener('submit', function(e) {
                const checked = document.querySelectorAll('.medicine-checkbox:checked').length;
                const extraFilled = document.querySelectorAll('.extra-medicine-select[value!=""]').length;
                if (checked === 0 && extraFilled === 0) {
                    e.preventDefault();
                    Swal.fire('Warning', 'Please select at least one medicine', 'warning');
                }
            });
        }
    });

    // Fetch medicines via AJAX
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
            const route = med.route || '';
            const dosage = med.dosage || '';
            const quantity = med.quantity || '';
            
            return `
        <div class="card border-0 shadow-sm mb-2 medicine-card" data-id="${med.id}">
            <div class="card-body py-2 px-3">
                <div class="d-flex align-items-start gap-2">
                    <input class="form-check-input medicine-checkbox mt-1" type="checkbox" 
                           name="medicines[${i}][assign]" value="1"
                           data-medicine-id="${med.id}"
                           ${med.already_assigned ? 'checked' : ''}>
                    <input type="hidden" name="medicines[${i}][medicine_id]" value="${med.id}">
                    ${med.already_assigned && med.patient_medicine_id ? 
                        `<input type="hidden" name="medicines[${i}][patient_medicine_id]" value="${med.patient_medicine_id}">` 
                        : ''}
                    
                    <div class="flex-grow-1">
                        <h6 class="mb-0 fw-semibold small">${med.name || 'Unknown Medicine'}</h6>
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
            if (!input.name.includes('[assign]')) {
                input.disabled = !enable;
            }
        });
    }

    // Update submit button - count both group and extra medicines
    function updateSubmitButton() {
        const groupChecked = document.querySelectorAll('.medicine-checkbox:checked').length;
        
        let extraFilled = 0;
        document.querySelectorAll('.extra-medicine-select').forEach(select => {
            if (select.value && select.value !== '') extraFilled++;
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
</script>
@endpush