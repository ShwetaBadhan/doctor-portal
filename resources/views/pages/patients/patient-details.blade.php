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
                            <div class="mb-4">
                                <a href="tel:{{ $patient->phone }}"
                                    class="btn btn-outline-white shadow-sm rounded-circle d-inline-flex align-items-center p-2 fs-14 me-2"
                                    title="Call">
                                    <i class="ti ti-phone"></i>
                                </a>
                                <a href="mailto:{{ $patient->email }}"
                                    class="btn btn-outline-white shadow-sm rounded-circle d-inline-flex align-items-center p-2 fs-14 me-2"
                                    title="Email">
                                    <i class="ti ti-message-circle"></i>
                                </a>
                                <a href="javascript:void(0);"
                                    class="btn btn-outline-white shadow-sm rounded-circle d-inline-flex align-items-center p-2 fs-14"
                                    title="Video">
                                    <i class="ti ti-video"></i>
                                </a>
                            </div>
                            <a href="{{ route('appointment-calendar', ['patient_id' => $patient->id]) }}"
                                class="btn btn-primary">
                                <i class="ti ti-calendar-event me-1"></i>Book Appointment
                            </a>
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
                            <a href="{{ route('patients.edit', $patient->id) }}" class="btn btn-sm btn-outline-primary">
                                <i class="ti ti-edit me-1"></i>Edit
                            </a>
                        </div>
                        <div class="card-body pb-0">
                            <div class="row">
                                <!-- DOB -->
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
                                <!-- Age -->
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
                                <!-- Blood Group -->
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
                                <!-- Gender -->
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
                                <!-- Email -->
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
                                <!-- Primary Doctor -->
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
                                <!-- Status -->
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
                        <div class="card-header">
                            <h5 class="fw-bold mb-0"><i class="ti ti-heart-rate-monitor me-1"></i>Vital Signs</h5>
                        </div>
                        <div class="card-body pb-0">
                            <div class="row">
                                @php
                                    $vitals = [
                                        [
                                            'key' => 'bp',
                                            'label' => 'Blood Pressure',
                                            'icon' => 'ti ti-droplet',
                                            'unit' => 'mmHg',
                                        ],
                                        [
                                            'key' => 'pulse',
                                            'label' => 'Heart Rate',
                                            'icon' => 'ti ti-heart',
                                            'unit' => 'Bpm',
                                        ],
                                        [
                                            'key' => 'temp',
                                            'label' => 'Temperature',
                                            'icon' => 'ti ti-temperature',
                                            'unit' => '°C',
                                        ],
                                        [
                                            'key' => 'weight',
                                            'label' => 'Weight',
                                            'icon' => 'ti ti-weight',
                                            'unit' => 'kg',
                                        ],
                                        ['key' => 'vat', 'label' => 'VAT', 'icon' => 'ti ti-hexagons', 'unit' => ''],
                                        ['key' => 'pit', 'label' => 'PIT', 'icon' => 'ti ti-activity', 'unit' => ''],
                                    ];
                                @endphp
                                @foreach ($vitals as $vital)
                                    @if ($patient->{$vital['key']})
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
                                                        {{ $patient->{$vital['key']} }} {{ $vital['unit'] }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach

                                <!-- Tongue & Nails -->
                                @if ($patient->tongue || $patient->nails)
                                    <div class="col-sm-4">
                                        <div class="d-flex align-items-center mb-3">
                                            <span class="avatar rounded-2 bg-light text-dark flex-shrink-0 me-2 border">
                                                <i class="ti ti-eye fs-16 text-body"></i>
                                            </span>
                                            <div>
                                                <h6 class="fs-13 fw-bold mb-1">Observations</h6>
                                                <p class="mb-0 text-truncate">
                                                    @if ($patient->tongue)
                                                        Tongue: {{ $patient->tongue }}
                                                    @endif
                                                    @if ($patient->tongue && $patient->nails)
                                                        |
                                                    @endif
                                                    @if ($patient->nails)
                                                        Nails: {{ $patient->nails }}
                                                    @endif
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <!-- Cerebral Fluid -->
                                @if ($patient->cerebral_fluid)
                                    <div class="col-sm-4">
                                        <div class="d-flex align-items-center mb-3">
                                            <span class="avatar rounded-2 bg-light text-dark flex-shrink-0 me-2 border">
                                                <i class="ti ti-brain fs-16 text-body"></i>
                                            </span>
                                            <div>
                                                <h6 class="fs-13 fw-bold mb-1">Cerebral Fluid</h6>
                                                <p class="mb-0 d-inline-flex align-items-center">
                                                    <i
                                                        class="ti ti-point-filled me-1 text-{{ $patient->cerebral_fluid == 'normal' ? 'success' : 'warning' }} fs-18"></i>
                                                    {{ ucfirst($patient->cerebral_fluid) }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabs Navigation -->
            <ul class="nav nav-tabs nav-bordered mb-3">
                <li class="nav-item">
                    <a href="#symptoms" data-bs-toggle="tab" class="nav-link active bg-transparent">
                        <span>Symptoms & Diagnosis</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#treatment" data-bs-toggle="tab" class="nav-link bg-transparent">
                        <span>Treatment</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#appointments" data-bs-toggle="tab" class="nav-link bg-transparent">
                        <span>Appointments</span>
                    </a>
                </li>
            </ul>

            <!-- Tab Content -->
            <div class="tab-content">

                <!-- Symptoms & Diagnosis Tab -->
                <div class="tab-pane show active" id="symptoms">
                    <div class="row">
                        <!-- Existing Symptoms -->
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header bg-success bg-opacity-10">
                                    <h6 class="fw-bold mb-0 text-success">
                                        <i class="ti ti-circle-check me-1"></i>Existing Symptoms
                                    </h6>
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

                        <!-- Non-Existing Symptoms -->
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

                    <!-- C.P & Medical Notes -->
                    <div class="row mt-3">
                        @if ($patient->cp || $patient->cp_movement)
                            <div class="col-md-4">
                                <div class="card border">
                                    <div class="card-body">
                                        <h6 class="fw-bold mb-2">C.P (Cerebral Palsy)</h6>
                                        <p class="mb-2">
                                            <strong>Status:</strong>
                                            @if ($patient->cp == 'yes')
                                                <span class="badge bg-danger">Yes</span>
                                            @else
                                                <span class="badge bg-secondary">No</span>
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

                <!-- Appointments Tab (Placeholder - Link to appointments module) -->
                <div class="tab-pane" id="appointments">
                    <div class="card">
                        <div class="card-body text-center py-5">
                            <i class="ti ti-calendar-event fs-1 text-muted"></i>
                            <h5 class="mt-3">Appointments</h5>
                            <p class="text-muted mb-4">View and manage all appointments for this patient.</p>
                            <a href="{{ route('appointment-calendar', ['patient_id' => $patient->id]) }}"
                                class="btn btn-primary">
                                <i class="ti ti-list me-1"></i>View Appointments
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        @include('components.copyright')
    </div>
@endsection
