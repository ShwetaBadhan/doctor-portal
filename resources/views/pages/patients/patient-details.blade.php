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
                                        <img src="{{ Storage::url($patient->profile_image) }}"
                                            alt="{{ $patient->first_name }}" class="rounded">
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
                                    <a href="{{ route('patients.edit', $patient->id) }}"
                                        class="btn btn-sm btn-outline-primary">
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
                                                <p class="mb-0">
                                                    {{ $patient->dob ? $patient->dob->format('d M Y') : 'N/A' }}
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
                                                            <h6 class="fs-13 fw-bold mb-1 text-truncate">
                                                                {{ $vital['label'] }}
                                                            </h6>
                                                            <p class="mb-0 d-inline-flex align-items-center text-truncate">
                                                                <i class="ti ti-point-filled me-1 text-success fs-18"></i>
                                                                {{ $latestAppointment->{$vital['key']} }}
                                                                {{ $vital['unit'] }}
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
                                        <a href="#appointments" data-bs-toggle="tab"
                                            class="btn btn-sm btn-outline-primary">
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
                    <li class="nav-item"><a href="#reports" data-bs-toggle="tab"
                            class="nav-link bg-transparent"><span>Test
                                Reports</span></a></li>
                    <li class="nav-item"><a href="#treatment" data-bs-toggle="tab"
                            class="nav-link bg-transparent"><span>Treatment</span></a></li>
                    <li class="nav-item"><a href="#appointments" data-bs-toggle="tab"
                            class="nav-link bg-transparent"><span>Appointments</span></a></li>
                    <li class="nav-item"><a href="#vitals" data-bs-toggle="tab"
                            class="nav-link bg-transparent"><span>Vital
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
                                        <h6 class="fw-bold mb-0 text-success"><i
                                                class="ti ti-circle-check me-1"></i>Existing
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
                                        <h6 class="fw-bold mb-0 text-danger"><i
                                                class="ti ti-circle-x me-1"></i>Non-Existing
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

                    <!-- Medicines Tab -->
                    <div class="tab-pane" id="medicines">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="fw-bold mb-0"><i class="ti ti-pills me-2 text-primary"></i>Prescribed Medicines
                            </h6>
                            <div class="d-flex gap-2">
                                @can('assign-medicines-to-patients')
                                    {{-- Re-prescribe All Button --}}
                                    <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#rePrescribeAllModal">
                                        <i class="ti ti-refresh me-1"></i> Re-prescribe All
                                    </button>

                                    {{-- Assign Medicine Group Button --}}
                                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#assignMedicineModal">
                                        <i class="ti ti-plus me-1"></i> Assign Medicine Group
                                    </button>
                                @endcan
                            </div>
                        </div>

                        {{-- ✅ Show only LATEST prescriptions --}}
                        @if ($latestPatientMedicines && $latestPatientMedicines->count())
                            <div class="card mb-4">
                                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                                    <h6 class="fw-bold mb-0">Current Prescriptions (Latest)</h6>
                                    <span class="badge bg-primary">{{ $latestPatientMedicines->count() }} medicines</span>
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
                                                    <th>Period</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($latestPatientMedicines as $index => $pm)
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
                                                            <small
                                                                class="text-muted">{{ $pm->instructions ?? '-' }}</small>
                                                            @if ($pm->route)
                                                                <br><span
                                                                    class="badge bg-info text-dark fs-11">{{ $pm->route }}</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if ($pm->start_date || $pm->end_date)
                                                                <small class="text-primary">
                                                                    {{ $pm->start_date?->format('d M Y') ?? 'N/A' }}
                                                                    @if ($pm->end_date)
                                                                        <br>→ {{ $pm->end_date->format('d M Y') }}
                                                                    @else
                                                                        <br><span class="text-muted">Ongoing</span>
                                                                    @endif
                                                                </small>
                                                            @else
                                                                <small class="text-muted">No dates</small>
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

                                                    {{-- Edit Modal --}}
                                                    <div class="modal fade" id="editMedicineModal{{ $pm->id }}"
                                                        tabindex="-1">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <form
                                                                    action="{{ route('patients.medicines.update', $pm) }}"
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
                                                                                value="{{ old('custom_name', $pm->custom_name ?? ($pm->medicine->name ?? '')) }}">
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
                                                                            <select class="form-select" name="route">
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
                                                                                <label class="form-label">Start
                                                                                    Date</label>
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
                                                                        <button type="submit"
                                                                            class="btn btn-primary">Save Changes</button>
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

                            {{-- ✅ Show total history count --}}
                            @if ($allActiveMedicines->count() > $latestPatientMedicines->count())
                                <div class="alert alert-info">
                                    <i class="ti ti-info-circle me-1"></i>
                                    Showing <strong>{{ $latestPatientMedicines->count() }}</strong> latest prescriptions.
                                    Total <strong>{{ $allActiveMedicines->count() }}</strong> active prescriptions in
                                    history.
                                </div>
                            @endif
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

                        {{-- ✅ MANUAL DATE-WISE REPORT GENERATION (Original Style) --}}
                        <div class="card mb-3">
                            <div class="card-header bg-light">
                                <h6 class="fw-bold mb-0">
                                    <i class="ti ti-file-certificate me-1 text-primary"></i>Generate Diagnosis Report
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="row align-items-end g-3">
                                    {{-- Date Picker --}}
                                    <div class="col-md-6">
                                        <label class="form-label fw-medium">Report Date <span
                                                class="text-danger">*</span></label>
                                        <input type="date" id="reportDate" class="form-control"
                                            value="{{ now()->format('Y-m-d') }}">
                                        <small class="text-muted">Select the date for which you want to generate the
                                            report</small>
                                    </div>

                                    {{-- Generate Button --}}
                                    <div class="col-md-6">
                                        <button type="button" class="btn btn-primary w-100" onclick="generateReport()">
                                            <i class="ti ti-file-certificate me-1"></i> Generate & Save Report
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- ✅ Saved Reports History --}}
                        <h6 class="fw-bold mb-3 mt-4">
                            <i class="ti ti-history me-2"></i>Saved Reports History
                        </h6>

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
                            <h6 class="fw-bold mb-0"><i class="ti ti-file me-2 text-primary"></i>Test Reports & Documents
                            </h6>
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

                        {{-- ✅ Combine all reports into one collection --}}
                        @php
                            $allReports = [];

                            // Add patient reports
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

                            // Add appointment reports
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
                                                'appointment_type' => $appointment->appointment_type,
                                                'label' => 'Appointment Report',
                                                'badge_class' => 'success',
                                            ];
                                        }
                                    }
                                }
                            }

                            // Sort all reports by date (newest first)
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
                                                    {{ $fileName }}
                                                </p>
                                                <p class="fs-11 text-muted mb-2">
                                                    {{ $report['date']->format('d M Y') }}
                                                </p>

                                                {{-- Report Type Badge --}}
                                                <span
                                                    class="badge bg-{{ $report['badge_class'] }} bg-opacity-10 text-{{ $report['badge_class'] }} mb-2">
                                                    {{ $report['label'] }}
                                                </span>

                                                {{-- Appointment Info (if applicable) --}}
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

                                                    {{-- Delete Button for Both Patient and Appointment Reports --}}
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

                    {{-- ✅ Hidden Form for Delete Patient Report --}}
                    <form id="deleteReportForm" method="POST" style="display: none;">
                        @csrf
                        @method('DELETE')
                    </form>

                    {{-- ✅ Hidden Form for Delete Appointment Report --}}
                    <form id="deleteAppointmentReportForm" method="POST" style="display: none;">
                        @csrf
                        @method('DELETE')
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
                                            <h6 class="fw-bold mb-2"><i class="ti ti-history me-1"></i>Therapy History
                                            </h6>
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
                                            <h5 class="mb-0 text-warning fw-bold">{{ $latestVitals->weight ?? '-' }} kg
                                            </h5>
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
                                                                @if ($apt->kuff)
                                                                    | Delusion:{{ $apt->delusion }}
                                                                @endif
                                                                @if (!$apt->vat && !$apt->pit && !$apt->kuff && !$apt->delusion)
                                                                    -
                                                                @endif
                                                            </small>
                                                        </td>
                                                        <td>
                                                            <small class="text-muted">
                                                                @if ($apt->tongue)
                                                                    Toungue:{{ $apt->tongue }}
                                                                @endif
                                                                @if ($apt->nails)
                                                                    | Nails:{{ $apt->nails }}
                                                                @endif
                                                                @if ($apt->cerebral_fluid)
                                                                    | Cerebral:{{ ucfirst($apt->cerebral_fluid) }}
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
        <!-- Assign Medicine Group Modal (Server-side rendered) -->
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

                            <p class="text-muted mb-3">
                                Select medicines from group for <strong>{{ $patient->first_name }}
                                    {{ $patient->last_name }}</strong>
                            </p>

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
                                            @endif
                                            - {{ $group->medicines_count }} medicines
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Pre-load all group medicines (hidden by default) -->
                            @foreach ($medicineGroups as $group)
                                <div id="groupMedicines_{{ $group->id }}" class="group-medicines-content d-none">
                                    <!-- Check All Header -->
                                    <div
                                        class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom sticky-top bg-white z-1">
                                        <div class="form-check">
                                            <input class="form-check-input check-all-medicines" type="checkbox"
                                                id="checkAll_{{ $group->id }}" data-group="{{ $group->id }}">
                                            <label class="form-check-label fw-medium"
                                                for="checkAll_{{ $group->id }}">Select
                                                All</label>
                                        </div>
                                        <span class="badge bg-light text-dark" id="selectedCount_{{ $group->id }}">0
                                            selected</span>
                                    </div>

                                    <!-- Medicines List -->
                                    <div class="space-y-2"
                                        style="max-height: 300px; overflow-y: auto; padding-right: 5px;">
                                        @foreach ($group->medicines as $index => $medicine)
                                            @php
                                                $existingMed = $patient
                                                    ->patientMedicines()
                                                    ->where('medicine_id', $medicine->id)
                                                    ->where('is_active', true)
                                                    ->first();
                                            @endphp

                                            <div class="card border-0 shadow-sm mb-2 medicine-card"
                                                data-medicine-id="{{ $medicine->id }}"
                                                data-group="{{ $group->id }}">
                                                <div class="card-body py-2 px-3">
                                                    <div class="d-flex align-items-start gap-2">
                                                        {{-- ✅ FIXED: Use flat array structure --}}
                                                        <input class="form-check-input medicine-checkbox mt-1"
                                                            type="checkbox"
                                                            name="medicines[{{ $index }}][assign]" value="1"
                                                            data-medicine-id="{{ $medicine->id }}"
                                                            data-group="{{ $group->id }}"
                                                            {{ $existingMed ? 'checked' : '' }}>
                                                        <input type="hidden"
                                                            name="medicines[{{ $index }}][medicine_id]"
                                                            value="{{ $medicine->id }}">
                                                        @if ($existingMed)
                                                            <input type="hidden"
                                                                name="medicines[{{ $index }}][patient_medicine_id]"
                                                                value="{{ $existingMed->id }}">
                                                        @endif

                                                        <div class="flex-grow-1">
                                                            <input type="text"
                                                                name="medicines[{{ $index }}][custom_name]"
                                                                class="form-control form-control-sm mb-1"
                                                                placeholder="Medicine name"
                                                                value="{{ $existingMed->custom_name ?? $medicine->name }}"
                                                                required>
                                                            @if ($medicine->code)
                                                                <small class="text-muted">Code:
                                                                    {{ $medicine->code }}</small>
                                                            @endif
                                                            @if ($existingMed)
                                                                <span
                                                                    class="badge badge-soft-success fs-10 ms-1">Assigned</span>
                                                            @endif
                                                        </div>

                                                        <div class="medicine-fields" style="min-width: 200px;">
                                                            <div class="row g-1">
                                                                <div class="col-6">
                                                                    <input type="text"
                                                                        name="medicines[{{ $index }}][dosage]"
                                                                        class="form-control form-control-sm"
                                                                        placeholder="Dosage"
                                                                        value="{{ $existingMed->dosage ?? $medicine->dosage }}"
                                                                        {{ $existingMed ? '' : 'disabled' }}>
                                                                </div>
                                                                <div class="col-6">
                                                                    <input type="text"
                                                                        name="medicines[{{ $index }}][quantity]"
                                                                        class="form-control form-control-sm"
                                                                        placeholder="Qty"
                                                                        value="{{ $existingMed->quantity ?? $medicine->quantity }}"
                                                                        {{ $existingMed ? '' : 'disabled' }}>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach

                            <!-- ===== EXTRA MEDICINES SECTION ===== -->
                            <div class="mt-4 pt-3 border-top">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h6 class="fw-bold mb-0">
                                        <i class="ti ti-plus me-1 text-primary"></i>Extra Medicines
                                    </h6>
                                    <button type="button" class="btn btn-sm btn-outline-primary"
                                        onclick="addExtraMedicine()">
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

        {{-- Report Preview Modal --}}
        <div class="modal fade" id="reportPreviewModal" tabindex="-1">
            <div class="modal-dialog modal-xl modal-dialog-scrollable" style="max-width: 95%;">
                <div class="modal-content">
                    <div class="modal-header bg-light">
                        <h6 class="modal-title fw-bold">
                            <i class="ti ti-file-certificate me-2"></i>Diagnosis Report Preview
                        </h6>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-0 bg-light">
                        <iframe id="reportFrame" src=""
                            style="width: 100%; height: calc(100vh - 120px); border: none;" class="d-block">
                        </iframe>
                    </div>
                </div>
            </div>
        </div>
        {{-- ✅ Enhanced Re-prescribe Modal - XL size with proper table --}}
        <div class="modal fade" id="rePrescribeAllModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-scrollable">
                <div class="modal-content">
                    <form action="{{ route('patients.medicines.re-prescribe-all', $patient->id) }}" method="POST">
                        @csrf
                        <div class="modal-header bg-success bg-opacity-10 sticky-top">
                            <h5 class="modal-title text-success">
                                <i class="ti ti-refresh me-2"></i>Re-prescribe Medicines
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body" style="max-height: calc(100vh - 200px); overflow-y: auto;">
                            <p class="mb-3">
                                Re-prescribe medicines for <strong>{{ $patient->first_name }}
                                    {{ $patient->last_name }}</strong>.
                                You can edit any medicine before re-prescribing.
                            </p>

                            {{-- Dates + Group Filter Section --}}
                            <div class="card mb-3">
                                <div class="card-body">
                                    <div class="row g-3">
                                        <div class="col-md-4">
                                            <label class="form-label fw-medium">Start Date <span
                                                    class="text-danger">*</span></label>
                                            <input type="date" name="start_date" class="form-control"
                                                value="{{ now()->format('Y-m-d') }}" required>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label fw-medium">End Date</label>
                                            <input type="date" name="end_date" class="form-control">
                                            <small class="text-muted">Leave empty for ongoing</small>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label fw-medium">Filter by Group</label>
                                            <select name="group_id" class="form-select" id="rePrescribeGroupFilter">
                                                <option value="">All Groups</option>
                                                @php
                                                    $activeMedicines = $patient
                                                        ->patientMedicines()
                                                        ->where('is_active', true)
                                                        ->with('medicineGroup')
                                                        ->get()
                                                        ->groupBy('medicine_group_id');
                                                @endphp
                                                @foreach ($activeMedicines as $groupId => $meds)
                                                    @if ($groupId)
                                                        <option value="{{ $groupId }}">
                                                            {{ $meds->first()->medicineGroup->name ?? 'Unknown' }}
                                                            ({{ $meds->count() }})
                                                        </option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Medicines List - Proper Table --}}
                            <div class="card">
                                <div
                                    class="card-header bg-light d-flex justify-content-between align-items-center sticky-top">
                                    <div>
                                        <h6 class="fw-bold mb-0">
                                            <i class="ti ti-pills me-1"></i>Medicines to Re-prescribe
                                        </h6>
                                        <small class="text-muted">
                                            Total: <strong
                                                id="rePrescribeTotal">{{ $activeMedicines->flatten()->count() }}</strong>
                                            medicines
                                        </small>
                                    </div>
                                    <div class="d-flex align-items-center gap-3">
                                        <span class="badge bg-primary" id="rePrescribeCount">
                                            {{ $activeMedicines->flatten()->count() }} selected
                                        </span>
                                        <div class="form-check mb-0">
                                            <input class="form-check-input" type="checkbox" id="selectAllRePrescribe"
                                                checked>
                                            <label class="form-check-label fw-medium" for="selectAllRePrescribe">Select
                                                All</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body p-0">
                                    <div class="table-responsive" style="max-height: 450px; overflow-y: auto;">
                                        <table class="table table-hover align-middle mb-0">
                                            <thead class="table-light sticky-top">
                                                <tr>
                                                    <th style="width: 50px;" class="text-center">✓</th>
                                                    <th style="width: 200px;">Medicine Name</th>
                                                    <th style="width: 120px;">Group</th>
                                                    <th style="width: 120px;">Dosage</th>
                                                    <th style="width: 120px;">Quantity</th>
                                                    <th>Instructions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php
                                                    $allActiveMeds = $patient
                                                        ->patientMedicines()
                                                        ->where('is_active', true)
                                                        ->with(['medicine', 'medicineGroup'])
                                                        ->orderBy('sort_order')
                                                        ->get();
                                                @endphp
                                                @foreach ($allActiveMeds as $index => $med)
                                                    <tr class="medicine-represcribe-row"
                                                        data-group-id="{{ $med->medicine_group_id ?? 'none' }}">
                                                        <td class="text-center">
                                                            <input class="form-check-input re-prescribe-check"
                                                                type="checkbox"
                                                                name="medicines[{{ $index }}][include]"
                                                                value="1" checked>
                                                            <input type="hidden"
                                                                name="medicines[{{ $index }}][id]"
                                                                value="{{ $med->id }}">
                                                        </td>
                                                        <td>
                                                            <input type="text"
                                                                name="medicines[{{ $index }}][custom_name]"
                                                                class="form-control form-control-sm"
                                                                value="{{ $med->custom_name ?? ($med->medicine->name ?? '') }}"
                                                                placeholder="Medicine name">
                                                            @if ($med->medicine && $med->medicine->code)
                                                                <small class="text-muted">Code:
                                                                    {{ $med->medicine->code }}</small>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <span class="badge bg-light text-dark border">
                                                                {{ $med->medicineGroup->name ?? 'Individual' }}
                                                            </span>
                                                        </td>
                                                        <td>
                                                            <input type="text"
                                                                name="medicines[{{ $index }}][dosage]"
                                                                class="form-control form-control-sm"
                                                                value="{{ $med->dosage }}" placeholder="e.g., 1-0-1">
                                                        </td>
                                                        <td>
                                                            <input type="text"
                                                                name="medicines[{{ $index }}][quantity]"
                                                                class="form-control form-control-sm"
                                                                value="{{ $med->quantity }}"
                                                                placeholder="e.g., 30 tabs">
                                                        </td>
                                                        <td>
                                                            <input type="text"
                                                                name="medicines[{{ $index }}][instructions]"
                                                                class="form-control form-control-sm"
                                                                value="{{ $med->instructions }}"
                                                                placeholder="Instructions">
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div class="alert alert-warning mt-3 mb-0">
                                <i class="ti ti-alert-triangle me-1"></i>
                                <strong>Note:</strong> Original prescriptions remain unchanged. New entries will be created
                                with edited values.
                            </div>
                        </div>
                        <div class="modal-footer sticky-bottom bg-white border-top">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-success">
                                <i class="ti ti-check me-1"></i> Re-prescribe Selected
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endsection


    @push('scripts')
        <script>
            // ============================================
            // 1. ASSIGN MEDICINE MODAL (Server-side)
            // ============================================
            document.addEventListener('DOMContentLoaded', function() {
                const groupSelect = document.getElementById('medicineGroupSelect');

                if (groupSelect) {
                    groupSelect.addEventListener('change', function() {
                        const groupId = this.value;

                        // Hide ALL group contents
                        document.querySelectorAll('.group-medicines-content').forEach(el => {
                            el.classList.add('d-none');
                        });

                        // Show selected group
                        if (groupId) {
                            const selectedGroup = document.getElementById(`groupMedicines_${groupId}`);
                            if (selectedGroup) {
                                selectedGroup.classList.remove('d-none');
                            }
                        }

                        updateSubmitButton();
                    });
                }

                // Check All functionality
                document.addEventListener('change', function(e) {
                    if (e.target.classList.contains('check-all-medicines')) {
                        const groupId = e.target.dataset.group;
                        const isChecked = e.target.checked;
                        const groupDiv = document.getElementById(`groupMedicines_${groupId}`);

                        if (groupDiv) {
                            groupDiv.querySelectorAll('.medicine-checkbox').forEach(cb => {
                                cb.checked = isChecked;
                                const card = cb.closest('.medicine-card');
                                const fields = card.querySelector('.medicine-fields');
                                fields.querySelectorAll('input').forEach(input => {
                                    if (!input.name.includes('[assign]') && input.type !==
                                        'hidden') {
                                        input.disabled = !isChecked;
                                    }
                                });
                            });
                        }

                        updateSubmitButton();
                    }

                    // Individual medicine checkbox
                    if (e.target.classList.contains('medicine-checkbox')) {
                        const card = e.target.closest('.medicine-card');
                        const fields = card.querySelector('.medicine-fields');
                        const isChecked = e.target.checked;

                        fields.querySelectorAll('input').forEach(input => {
                            if (!input.name.includes('[assign]') && input.type !== 'hidden') {
                                input.disabled = !isChecked;
                            }
                        });

                        // Update check-all state
                        const groupDiv = e.target.closest('.group-medicines-content');
                        if (groupDiv) {
                            const groupId = groupDiv.id.replace('groupMedicines_', '');
                            const checkAll = document.getElementById(`checkAll_${groupId}`);
                            const allCheckboxes = groupDiv.querySelectorAll('.medicine-checkbox');
                            const checkedCount = groupDiv.querySelectorAll('.medicine-checkbox:checked').length;

                            if (checkAll) {
                                checkAll.checked = checkedCount === allCheckboxes.length;
                                checkAll.indeterminate = checkedCount > 0 && checkedCount < allCheckboxes
                                .length;
                            }
                        }

                        updateSubmitButton();
                    }

                    // Extra medicine dropdown
                    if (e.target.classList.contains('extra-medicine-select')) {
                        updateSubmitButton();
                    }
                });

                // Extra medicine name input
                document.addEventListener('input', function(e) {
                    if (e.target.classList.contains('extra-medicine-name')) {
                        updateSubmitButton();
                    }
                });

                // Form submit validation
                const assignForm = document.getElementById('assignMedicineForm');
                if (assignForm) {
                    assignForm.addEventListener('submit', function(e) {
                        const checked = document.querySelectorAll('.medicine-checkbox:checked').length;
                        let extraFilled = 0;

                        document.querySelectorAll('.extra-medicine-item').forEach(item => {
                            const select = item.querySelector('.extra-medicine-select');
                            const nameField = item.querySelector('.extra-medicine-name');
                            if ((select && select.value) || (nameField && nameField.value.trim())) {
                                extraFilled++;
                            }
                        });

                        if (checked === 0 && extraFilled === 0) {
                            e.preventDefault();
                            Swal.fire('Warning', 'Please select at least one medicine', 'warning');
                        }
                    });
                }
            });

            // Update submit button count
            function updateSubmitButton() {
                const groupChecked = document.querySelectorAll('.medicine-checkbox:checked').length;
                let extraFilled = 0;

                document.querySelectorAll('.extra-medicine-item').forEach(item => {
                    const select = item.querySelector('.extra-medicine-select');
                    const nameField = item.querySelector('.extra-medicine-name');
                    if ((select && select.value) || (nameField && nameField.value.trim())) {
                        extraFilled++;
                    }
                });

                const total = groupChecked + extraFilled;
                const submitBtn = document.getElementById('submitAssignBtn');
                const submitCount = document.getElementById('submitCount');

                if (submitCount) submitCount.textContent = total;
                if (submitBtn) submitBtn.disabled = total === 0;
            }

            // ============================================
            // 2. EXTRA MEDICINES
            // ============================================
            let extraMedicineCounter = 0;

            function addExtraMedicine() {
                extraMedicineCounter++;
                const container = document.getElementById('extraMedicinesContainer');
                const idx = extraMedicineCounter;

                const html = `
    <div class="extra-medicine-item card border-0 bg-light mb-2" id="extraMed_${idx}">
        <div class="card-body py-2 px-3">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h6 class="fw-bold mb-0 text-primary">
                    <i class="ti ti-pill me-1"></i>Extra Medicine ${idx}
                </h6>
                <button type="button" class="btn btn-sm btn-light text-danger" 
                        onclick="removeExtraMedicine(${idx})" title="Remove">
                    <i class="ti ti-x"></i>
                </button>
            </div>
            <div class="row g-2">
                <div class="col-md-4">
                    <label class="form-label small">Select Medicine</label>
                    <select name="extra_medicines[${idx}][medicine_id]" 
                            class="form-select form-select-sm extra-medicine-select" 
                            onchange="autoFillExtraMedicine(this)">
                        <option value="">-- Select medicine --</option>
                        @foreach (\App\Models\Medicine::where('is_active', true)->orderBy('name')->get() as $med)
                            <option value="{{ $med->id }}" 
                                    data-name="{{ $med->name }}"
                                    data-dosage="{{ $med->dosage }}"
                                    data-quantity="{{ $med->quantity }}"
                                    data-route="{{ $med->route }}"
                                    data-instructions="{{ $med->instructions }}">
                                {{ $med->name }} @if ($med->code)({{ $med->code }})@endif
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label small">Medicine Name <span class="text-danger">*</span></label>
                    <input type="text" 
                           name="extra_medicines[${idx}][custom_name]" 
                           class="form-control form-control-sm extra-medicine-name" 
                           placeholder="Medicine name (editable)" 
                           required>
                </div>
                <div class="col-md-2">
                    <label class="form-label small">Dosage</label>
                    <input type="text" name="extra_medicines[${idx}][dosage]" 
                           class="form-control form-control-sm extra-dosage" 
                           placeholder="e.g., 1-0-1" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label small">Quantity</label>
                    <input type="text" name="extra_medicines[${idx}][quantity]" 
                           class="form-control form-control-sm extra-quantity" 
                           placeholder="e.g., 30 tabs" required>
                </div>
            </div>
        </div>
    </div>`;

                container.insertAdjacentHTML('beforeend', html);
                updateSubmitButton();
            }

            function removeExtraMedicine(index) {
                const el = document.getElementById(`extraMed_${index}`);
                if (el) {
                    el.remove();
                    updateSubmitButton();
                }
            }

            function autoFillExtraMedicine(select) {
                const option = select.options[select.selectedIndex];
                updateSubmitButton();
                if (!option.value) return;

                const row = select.closest('.extra-medicine-item');
                const nameField = row.querySelector('.extra-medicine-name');
                const dosageField = row.querySelector('.extra-dosage');
                const qtyField = row.querySelector('.extra-quantity');

                if (nameField && option.dataset.name) nameField.value = option.dataset.name;
                if (dosageField && option.dataset.dosage) dosageField.value = option.dataset.dosage;
                if (qtyField && option.dataset.quantity) qtyField.value = option.dataset.quantity;

                if (nameField) {
                    nameField.focus();
                    nameField.select();
                }
            }

            // ============================================
            // 3. REMOVE MEDICINE CONFIRMATION
            // ============================================
            function confirmRemoveMedicine(id, name) {
                if (confirm(`Remove "${name}" from prescription?`)) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `/patient-medicines/${id}/remove`;
                    form.innerHTML = `@csrf @method('DELETE')`;
                    document.body.appendChild(form);
                    form.submit();
                }
            }

            // ============================================
            // 4. REPORTS (Date-wise Generation)
            // ============================================
            document.addEventListener('DOMContentLoaded', function() {
                loadReportsHistory();
            });

            function generateReport() {
                const date = document.getElementById('reportDate').value;

                if (!date) {
                    Swal.fire('Error', 'Please select a date', 'error');
                    return;
                }

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
                    .then(response => response.json())
                    .then(data => {
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
                        Swal.fire('Error', 'Failed to generate report: ' + error.message, 'error');
                    });
            }

            function loadReportsHistory() {
                const historyUrl = "{{ route('patients.reports.history', $patient->id) }}";
                const container = document.getElementById('reportsHistoryContainer');
                if (!container) return;

                fetch(historyUrl)
                    .then(response => response.json())
                    .then(data => {
                        if (!data.reports || data.reports.length === 0) {
                            container.innerHTML = `
                    <div class="card border-dashed">
                        <div class="card-body text-center py-5">
                            <i class="ti ti-history fs-1 text-muted opacity-50"></i>
                            <h5 class="mt-3 text-muted">No Saved Reports</h5>
                            <p class="text-muted mb-0">Generate a report to see it here.</p>
                        </div>
                    </div>`;
                            return;
                        }

                        let html = '<div class="card"><div class="card-body p-0"><div class="list-group list-group-flush">';

                        data.reports.forEach((report, index) => {
                            html += `
                    <div class="list-group-item">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center">
                                <span class="fw-bold text-primary me-3" style="min-width: 30px;">
                                    ${index + 1}.
                                </span>
                                <div>
                                    <div class="fw-medium">
                                        <i class="ti ti-file-certificate me-1 text-info"></i>
                                        Report for ${report.date}
                                    </div>
                                    ${report.medicine_count ? 
                                        `<small class="text-muted">${report.medicine_count} medicine(s)</small>` 
                                        : ''}
                                </div>
                            </div>
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-sm btn-primary" 
                                        onclick="previewReport('${report.preview_url}')">
                                    <i class="ti ti-eye me-1"></i> Preview
                                </button>
                                <a href="${report.download_url}" class="btn btn-sm btn-success" target="_blank">
                                    <i class="ti ti-download me-1"></i> Download
                                </a>
                            </div>
                        </div>
                    </div>`;
                        });

                        html += '</div></div></div>';
                        container.innerHTML = html;
                    })
                    .catch(error => {
                        container.innerHTML = `
                <div class="alert alert-danger">
                    <strong>Error loading reports!</strong><br>
                    <small>${error.message}</small>
                </div>`;
                    });
            }

            function previewReport(url) {
                document.getElementById('reportFrame').src = url;
                const modal = new bootstrap.Modal(document.getElementById('reportPreviewModal'));
                modal.show();
            }

            // ============================================
            // 5. RE-PRESCRIBE MODAL
            // ============================================
            document.addEventListener('DOMContentLoaded', function() {
                const modal = document.getElementById('rePrescribeAllModal');
                if (!modal) return;

                const selectAll = modal.querySelector('#selectAllRePrescribe');
                const groupFilter = modal.querySelector('#rePrescribeGroupFilter');
                const countBadge = modal.querySelector('#rePrescribeCount');
                const totalBadge = modal.querySelector('#rePrescribeTotal');
                const rows = modal.querySelectorAll('.medicine-represcribe-row');

                function updateCount() {
                    const checked = modal.querySelectorAll('.re-prescribe-check:checked').length;
                    if (countBadge) countBadge.textContent = checked + ' selected';
                }

                if (selectAll) {
                    selectAll.addEventListener('change', function() {
                        const isChecked = this.checked;
                        rows.forEach(row => {
                            if (!row.classList.contains('d-none')) {
                                const checkbox = row.querySelector('.re-prescribe-check');
                                checkbox.checked = isChecked;
                            }
                        });
                        updateCount();
                    });
                }

                modal.addEventListener('change', function(e) {
                    if (e.target.classList.contains('re-prescribe-check')) {
                        updateCount();
                    }
                });

                if (groupFilter) {
                    groupFilter.addEventListener('change', function() {
                        const selectedGroup = this.value;
                        let visibleCount = 0;

                        rows.forEach(row => {
                            const rowGroup = row.dataset.groupId;
                            if (!selectedGroup || rowGroup === selectedGroup) {
                                row.classList.remove('d-none');
                                visibleCount++;
                            } else {
                                row.classList.add('d-none');
                            }
                        });

                        if (totalBadge) totalBadge.textContent = visibleCount;
                        updateCount();
                    });
                }
            });

            // ============================================
            // 6. DELETE REPORTS
            // ============================================
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
                        const form = document.getElementById('deleteAppointmentReportForm');
                        form.action = `/appointments/reports/${appointmentDate}/${reportIndex}`;
                        form.submit();
                    }
                });
            }
        </script>
    @endpush
