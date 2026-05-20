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
        @if(session('success'))
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({ icon: 'success', title: 'Success!', text: @json(session('success')), timer: 3000, showConfirmButton: false, toast: true, position: 'top-end' });
                });
            </script>
        @endif

        <!-- Patient Header Card -->
        <div class="card">
            <div class="row align-items-end">
                <div class="col-xl-9 col-lg-8">
                    <div class="d-sm-flex align-items-center position-relative z-0 overflow-hidden p-3">
                        <img src="{{ asset('assets/img/icons/shape-01.svg') }}" alt="img" class="z-n1 position-absolute end-0 top-0 d-none d-lg-flex">

                        <!-- Profile Image -->
                        <a href="javascript:void(0);" class="avatar avatar-xxxl patient-avatar me-2 flex-shrink-0">
                            @if ($patient->profile_image)
                                <img src="{{ Storage::url($patient->profile_image) }}" alt="{{ $patient->first_name }}" class="rounded">
                            @else
                                <span class="avatar-text bg-light text-muted d-flex align-items-center justify-content-center w-100 h-100 fs-3">
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
                        <a href="{{ route('appointment-calendar', ['patient_id' => $patient->id]) }}" class="btn btn-primary">
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
                                        <p class="mb-0">{{ $patient->dob ? $patient->dob->format('d M Y') : 'N/A' }}</p>
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
                                        <i class="ti ti-gender-{{ $patient->gender == 'male' ? 'male' : ($patient->gender == 'female' ? 'female' : 'third') }} text-body fs-16"></i>
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
                                                <span class="badge badge-soft-success rounded text-success">Available</span>
                                            @else
                                                <span class="badge badge-soft-danger rounded text-danger">Unavailable</span>
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
                                    ['key' => 'bp', 'label' => 'Blood Pressure', 'icon' => 'ti ti-droplet', 'unit' => 'mmHg'],
                                    ['key' => 'pulse', 'label' => 'Heart Rate', 'icon' => 'ti ti-heart', 'unit' => 'Bpm'],
                                    ['key' => 'temp', 'label' => 'Temperature', 'icon' => 'ti ti-temperature', 'unit' => '°C'],
                                    ['key' => 'weight', 'label' => 'Weight', 'icon' => 'ti ti-weight', 'unit' => 'kg'],
                                    ['key' => 'vat', 'label' => 'VAT', 'icon' => 'ti ti-hexagons', 'unit' => ''],
                                    ['key' => 'pit', 'label' => 'PIT', 'icon' => 'ti ti-activity', 'unit' => ''],
                                ];
                            @endphp
                            @foreach ($vitals as $vital)
                                @if ($patient->{$vital['key']})
                                <div class="col-sm-4">
                                    <div class="d-flex align-items-center mb-3">
                                        <span class="avatar rounded-2 bg-light text-dark flex-shrink-0 me-2 border">
                                            <i class="{{ $vital['icon'] }} fs-16 text-body"></i>
                                        </span>
                                        <div>
                                            <h6 class="fs-13 fw-bold mb-1 text-truncate">{{ $vital['label'] }}</h6>
                                            <p class="mb-0 d-inline-flex align-items-center text-truncate">
                                                <i class="ti ti-point-filled me-1 text-success fs-18"></i>
                                                {{ $patient->{$vital['key']} }} {{ $vital['unit'] }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            @endforeach
                            @if ($patient->tongue || $patient->nails)
                            <div class="col-sm-4">
                                <div class="d-flex align-items-center mb-3">
                                    <span class="avatar rounded-2 bg-light text-dark flex-shrink-0 me-2 border">
                                        <i class="ti ti-eye fs-16 text-body"></i>
                                    </span>
                                    <div>
                                        <h6 class="fs-13 fw-bold mb-1">Observations</h6>
                                        <p class="mb-0 text-truncate">
                                            @if ($patient->tongue) Tongue: {{ $patient->tongue }} @endif
                                            @if ($patient->tongue && $patient->nails) | @endif
                                            @if ($patient->nails) Nails: {{ $patient->nails }} @endif
                                        </p>
                                    </div>
                                </div>
                            </div>
                            @endif
                            @if ($patient->cerebral_fluid)
                            <div class="col-sm-4">
                                <div class="d-flex align-items-center mb-3">
                                    <span class="avatar rounded-2 bg-light text-dark flex-shrink-0 me-2 border">
                                        <i class="ti ti-brain fs-16 text-body"></i>
                                    </span>
                                    <div>
                                        <h6 class="fs-13 fw-bold mb-1">Cerebral Fluid</h6>
                                        <p class="mb-0 d-inline-flex align-items-center">
                                            <i class="ti ti-point-filled me-1 text-{{ $patient->cerebral_fluid == 'normal' ? 'success' : 'warning' }} fs-18"></i>
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
            <li class="nav-item"><a href="#symptoms" data-bs-toggle="tab" class="nav-link active bg-transparent"><span>Symptoms</span></a></li>
            <li class="nav-item"><a href="#medicines" data-bs-toggle="tab" class="nav-link bg-transparent"><span>Medicines</span></a></li>
            <li class="nav-item"><a href="#reports" data-bs-toggle="tab" class="nav-link bg-transparent"><span>Test Reports</span></a></li>
            <li class="nav-item"><a href="#treatment" data-bs-toggle="tab" class="nav-link bg-transparent"><span>Treatment</span></a></li>
            <li class="nav-item"><a href="#appointments" data-bs-toggle="tab" class="nav-link bg-transparent"><span>Appointments</span></a></li>
        </ul>

        <!-- Tab Content -->
        <div class="tab-content">

            <!-- Symptoms Tab -->
            <div class="tab-pane show active" id="symptoms">
                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header bg-success bg-opacity-10">
                                <h6 class="fw-bold mb-0 text-success"><i class="ti ti-circle-check me-1"></i>Existing Symptoms</h6>
                            </div>
                            <div class="card-body">
                                @if ($patient->existing_symptoms && count($patient->existing_symptoms) > 0)
                                    <div class="d-flex flex-wrap gap-2">
                                        @foreach ($patient->existing_symptoms as $symptom)
                                            <span class="badge bg-success bg-opacity-10 text-success border border-success">{{ $symptom }}</span>
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
                                <h6 class="fw-bold mb-0 text-danger"><i class="ti ti-circle-x me-1"></i>Non-Existing Symptoms</h6>
                            </div>
                            <div class="card-body">
                                @if ($patient->non_existing_symptoms && count($patient->non_existing_symptoms) > 0)
                                    <div class="d-flex flex-wrap gap-2">
                                        @foreach ($patient->non_existing_symptoms as $symptom)
                                            <span class="badge bg-danger bg-opacity-10 text-danger border border-danger">{{ $symptom }}</span>
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
                                <p class="mb-2"><strong>Status:</strong> @if($patient->cp=='yes')<span class="badge bg-danger">Yes</span>@else<span class="badge bg-secondary">No</span>@endif</p>
                                @if ($patient->cp_movement && count($patient->cp_movement) > 0)
                                    <p class="mb-0"><strong>Movement:</strong><br>
                                        @foreach ($patient->cp_movement as $movement)
                                            <span class="badge bg-info text-dark">{{ str_replace('_', ' ', ucfirst($movement)) }}</span>
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
                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#assignMedicineModal">
                        <i class="ti ti-plus me-1"></i> Assign Medicine Group
                    </button>
                    @endcan
                </div>

                <!-- Current Prescriptions -->
                @if($patient->patientMedicines && $patient->patientMedicines->count())
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
                                    @foreach($patient->patientMedicines as $index => $pm)
                                    <tr>
                                        <td>{{ $pm->sort_order ?? $index + 1 }}</td>
                                        <td>
                                            <div class="fw-medium">{{ $pm->medicine->name ?? 'Unknown' }}</div>
                                            @if($pm->notes)
                                                <small class="text-muted d-block">{{ Str::limit($pm->notes, 40) }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            @if($pm->medicineGroup)
                                                <span class="badge bg-light text-dark border">{{ $pm->medicineGroup->name }}</span>
                                            @else
                                                <span class="text-muted">Individual</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($pm->dosage)
                                                <span class="badge bg-light text-dark">{{ $pm->dosage }}</span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>{{ $pm->quantity ?? '-' }}</td>
                                        <td>
                                            <small class="text-muted">{{ $pm->instructions ?? '-' }}</small>
                                            @if($pm->route)
                                                <br><span class="badge bg-info text-dark fs-11">{{ $pm->route }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <small class="text-muted">{{ $pm->created_at->format('d M Y') }}</small>
                                            @if($pm->start_date || $pm->end_date)
                                                <br><small class="text-primary">
                                                    {{ $pm->start_date?->format('d M') }} 
                                                    @if($pm->end_date) → {{ $pm->end_date->format('d M') }} @endif
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
                                    <div class="modal fade" id="editMedicineModal{{ $pm->id }}" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <form action="{{ route('patients.medicines.update', $pm) }}" method="POST">
                                                    @csrf @method('PUT')
                                                    <div class="modal-header">
                                                        <h6 class="modal-title">Edit Prescription</h6>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p class="fw-medium mb-3">{{ $pm->medicine->name ?? 'Medicine' }}</p>
                                                        <div class="row">
                                                            <div class="col-6 mb-3">
                                                                <label class="form-label">Dosage</label>
                                                                <input type="text" class="form-control" name="dosage" value="{{ old('dosage', $pm->dosage) }}">
                                                            </div>
                                                            <div class="col-6 mb-3">
                                                                <label class="form-label">Quantity</label>
                                                                <input type="text" class="form-control" name="quantity" value="{{ old('quantity', $pm->quantity) }}">
                                                            </div>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">Route</label>
                                                            <select class="select" name="route">
                                                                <option value="">Select</option>
                                                                <option value="ORAL" {{ old('route', $pm->route) == 'ORAL' ? 'selected' : '' }}>ORAL</option>
                                                                <option value="EXTERNAL" {{ old('route', $pm->route) == 'EXTERNAL' ? 'selected' : '' }}>EXTERNAL</option>
                                                                <option value="GULBULES" {{ old('route', $pm->route) == 'GULBULES' ? 'selected' : '' }}>GULBULES</option>
                                                                <option value="INJECTION" {{ old('route', $pm->route) == 'INJECTION' ? 'selected' : '' }}>INJECTION</option>
                                                                <option value="SIP SIP" {{ old('route', $pm->route) == 'SIP SIP' ? 'selected' : '' }}>SIP SIP</option>
                                                            </select>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">Instructions</label>
                                                            <textarea class="form-control" name="instructions" rows="2">{{ old('instructions', $pm->instructions) }}</textarea>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-6 mb-3">
                                                                <label class="form-label">Start Date</label>
                                                                <input type="date" class="form-control" name="start_date" value="{{ old('start_date', $pm->start_date?->format('Y-m-d')) }}">
                                                            </div>
                                                            <div class="col-6 mb-3">
                                                                <label class="form-label">End Date</label>
                                                                <input type="date" class="form-control" name="end_date" value="{{ old('end_date', $pm->end_date?->format('Y-m-d')) }}">
                                                            </div>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">Notes</label>
                                                            <textarea class="form-control" name="notes" rows="2">{{ old('notes', $pm->notes) }}</textarea>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                                                        <button type="submit" class="btn btn-primary">Save Changes</button>
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
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#assignMedicineModal">
                            <i class="ti ti-plus me-1"></i> Assign Medicine Group
                        </button>
                    </div>
                </div>
                @endif

                <!-- Medicine History -->
                <h6 class="fw-bold mb-3"><i class="ti ti-history me-2"></i>Medicine Assignment History</h6>
                <div class="card">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-sm mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Date</th>
                                        <th>Action</th>
                                        <th>Medicine/Group</th>
                                        <th>Details</th>
                                        <th>By</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        // Get all patient medicine records including inactive ones for history
                                        $allMedicines = \App\Models\PatientMedicine::where('patient_id', $patient->id)
                                            ->with(['medicine', 'medicineGroup'])
                                            ->orderBy('created_at', 'desc')
                                            ->get();
                                    @endphp
                                    @forelse($allMedicines as $history)
                                    <tr class="{{ !$history->is_active ? 'table-secondary' : '' }}">
                                        <td>
                                            <small>{{ $history->created_at->format('d M Y, h:i A') }}</small>
                                            @if(!$history->is_active)
                                                <br><span class="badge bg-secondary fs-11">Removed</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($history->is_active)
                                                <span class="badge bg-success bg-opacity-10 text-success">Assigned</span>
                                            @else
                                                <span class="badge bg-secondary">Removed</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="fw-medium">{{ $history->medicine->name ?? 'Unknown' }}</div>
                                            @if($history->medicineGroup)
                                                <small class="text-muted">From: {{ $history->medicineGroup->name }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            <small class="text-muted">
                                                @if($history->dosage) Dosage: {{ $history->dosage }} @endif
                                                @if($history->quantity) | Qty: {{ $history->quantity }} @endif
                                            </small>
                                        </td>
                                        <td><small class="text-muted">System</small></td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-3 text-muted">No history available</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Test Reports Tab (NEW) -->
            <div class="tab-pane" id="reports">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="fw-bold mb-0"><i class="ti ti-file me-2 text-primary"></i>Test Reports & Documents</h6>
                    @can('upload-patient-reports')
                    <!-- Upload Button -->
                    <form action="{{ route('reports.upload', $patient->id) }}" method="POST" enctype="multipart/form-data" class="d-inline">
                        @csrf
                        <div class="input-group input-group-sm" style="max-width: 300px;">
                            <input type="file" name="reports[]" class="form-control" multiple accept=".pdf,.jpg,.jpeg,.png,.doc,.docx" required>
                            <button type="submit" class="btn btn-primary">
                                <i class="ti ti-upload me-1"></i> Upload
                            </button>
                        </div>
                        <small class="text-muted d-block mt-1">PDF, JPG, PNG, DOC (Max 5MB each)</small>
                    </form>
                    @endcan
                </div>

                @if($patient->test_reports && count($patient->test_reports))
                <div class="row">
                    @foreach($patient->test_reports as $index => $reportPath)
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
                                
                                <p class="small text-truncate mb-2" title="{{ $fileName }}">{{ $fileName }}</p>
                                <p class="fs-11 text-muted mb-2">{{ \Carbon\Carbon::parse(filemtime(storage_path('app/public/' . $reportPath)))->format('d M Y') }}</p>
                                
                                <div class="btn-group btn-group-sm">
                                @can('view-patient-reports')
                                     <a href="{{ Storage::url($reportPath) }}" class="btn btn-light" target="_blank" title="View">
                                        <i class="ti ti-eye"></i>
                                    </a>
                                @endcan
                                   @can('download-patient-reports')
                                    <a href="{{ Storage::url($reportPath) }}" class="btn btn-light" download title="Download">
                                        <i class="ti ti-download"></i>
                                    </a>
                                    @endcan
                                    @can('delete-patient-reports')
                                    <form action="{{ route('reports.delete', [$patient->id, $index]) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this report?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-light text-danger" title="Delete">
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
            <i class="ti ti-calendar-event me-2 text-primary"></i>Appointments for {{ $patient->first_name }}
        </h6>
        <a href="{{ route('appointments.create', ['patient_id' => $patient->id]) }}" class="btn btn-sm btn-primary">
            <i class="ti ti-plus me-1"></i> New Appointment
        </a>
    </div>

    @if($patient->appointments && $patient->appointments->count())
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
                        @foreach($patient->appointments->sortByDesc('appointment_date') as $appointment)
                        <tr>
                            <td>
                                <div class="fw-medium">{{ $appointment->appointment_date->format('d M Y') }}</div>
                                <small class="text-muted">{{ $appointment->appointment_time ?? 'N/A' }}</small>
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
                                        'cancelled' => 'secondary'
                                    ];
                                @endphp
                                <span class="badge bg-{{ $statusColors[$appointment->status] ?? 'light' }} bg-opacity-10 text-{{ $statusColors[$appointment->status] ?? 'dark' }} border border-{{ $statusColors[$appointment->status] ?? 'light' }}">
                                    {{ ucfirst(str_replace('_', ' ', $appointment->status)) }}
                                </span>
                            </td>
                            <td class="text-end">
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('appointments.show', $appointment) }}" class="btn btn-light" title="View">
                                        <i class="ti ti-eye"></i>
                                    </a>
                                    <a href="{{ route('appointments.edit', $appointment) }}" class="btn btn-light" title="Edit">
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
            <a href="{{ route('appointments.create', ['patient_id' => $patient->id]) }}" class="btn btn-primary">
                <i class="ti ti-plus me-1"></i> Schedule First Appointment
            </a>
        </div>
    </div>
    @endif
</div>

        </div>
    </div>

    @include('components.copyright')
</div>

<!-- Assign Medicine Group Modal -->
<div class="modal fade" id="assignMedicineModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('patients.medicines.assign-group', $patient->id) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title"><i class="ti ti-pills me-2"></i>Assign Medicine Group</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info mb-3">
                        <i class="ti ti-info-circle me-1"></i> Select a group to auto-assign all its medicines to this patient.
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-medium">Medicine Group <span class="text-danger">*</span></label>
                        <select name="medicine_group_id" class="select" required>
                            <option value="">Select group...</option>
                            @foreach(\App\Models\MedicineGroup::where('is_active', true)->withCount('medicines')->orderBy('name')->get() as $group)
                                <option value="{{ $group->id }}">
                                    {{ $group->name }} @if($group->code)({{ $group->code }})@endif - {{ $group->medicines_count }} medicines
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="row">
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
                        <label class="form-label">Notes</label>
                        <textarea name="notes" class="form-control" rows="2" placeholder="Additional instructions..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary"><i class="ti ti-check me-1"></i> Assign Group</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
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
            form.action = `/patients/medicines/${id}`;
            form.innerHTML = `@csrf @method('DELETE')`;
            document.body.appendChild(form);
            form.submit();
        }
    });
}
</script>
@endsection