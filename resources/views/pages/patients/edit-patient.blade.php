@extends('layout.master')

@section('content')
    <div class="page-wrapper">
        <div class="content">
            <div class="row justify-content-center">
                <div class="col-lg-10">

                    <!-- Page Header -->
                    <div class="mb-4">
                        <h6 class="fw-bold mb-0 d-flex align-items-center">
                            <a href="{{ route('patients.index') }}" class="text-dark">
                                <i class="ti ti-chevron-left me-1"></i>Patients
                            </a>
                            <span class="mx-2">/</span>
                            <span class="text-primary">Edit Patient</span>
                        </h6>
                    </div>

                    <!-- Validation Errors -->
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <!-- Form Start -->
                    <form action="{{ route('patients.update', $patient->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="card">
                            <div class="card-body pb-0">
                                <h6 class="fw-bold mb-3">Patient Information</h6>
                                <div class="row">

                                    <!-- Profile Image -->
                                    <div class="col-lg-12">
                                        <div class="mb-3 d-flex align-items-center">
                                            <label class="form-label mb-0">Profile Image</label>
                                            <div
                                                class="drag-upload-btn avatar avatar-xxl rounded-circle bg-light text-muted position-relative overflow-hidden z-1 mb-2 ms-4 p-0">
                                                @if ($patient->profile_image)
                                                    <img src="{{ Storage::url($patient->profile_image) }}"
                                                        alt="{{ $patient->first_name }}" class="position-relative z-n1"
                                                        style="width: 96px; height: 96px; object-fit: cover;">
                                                @else
                                                    <i class="ti ti-user-plus fs-16"></i>
                                                @endif
                                                <input type="file" class="form-control image-sign" name="profile_image"
                                                    accept="image/*">
                                                <div
                                                    class="position-absolute bottom-0 end-0 star-0 w-100 h-25 bg-dark d-flex align-items-center justify-content-center z-n1">
                                                    <a href="javascript:void(0);"
                                                        class="text-white d-flex align-items-center justify-content-center">
                                                        <i class="ti ti-photo fs-14"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- First Name -->
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label mb-1 fw-medium">First Name<span
                                                    class="text-danger ms-1">*</span></label>
                                            <input type="text"
                                                class="form-control @error('first_name') is-invalid @enderror"
                                                name="first_name" value="{{ old('first_name', $patient->first_name) }}"
                                                required>
                                            @error('first_name')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Last Name -->
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label mb-1 fw-medium">Last Name<span
                                                    class="text-danger ms-1">*</span></label>
                                            <input type="text"
                                                class="form-control @error('last_name') is-invalid @enderror"
                                                name="last_name" value="{{ old('last_name', $patient->last_name) }}"
                                                required>
                                            @error('last_name')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Phone -->
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label mb-1 fw-medium">Phone Number<span
                                                    class="text-danger ms-1">*</span></label>
                                            <input type="tel" class="form-control @error('phone') is-invalid @enderror"
                                                name="phone" value="{{ old('phone', $patient->phone) }}" required>
                                            @error('phone')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Email -->
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label mb-1 fw-medium">Email Address</label>
                                            <input type="email" class="form-control @error('email') is-invalid @enderror"
                                                name="email" value="{{ old('email', $patient->email) }}">
                                            @error('email')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Primary Doctor -->
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label mb-1 fw-medium">Primary Doctor</label>
                                            <input type="text"
                                                class="form-control @error('primary_doctor') is-invalid @enderror"
                                                name="primary_doctor"
                                                value="{{ old('primary_doctor', $patient->primary_doctor) }}">
                                            @error('primary_doctor')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- DOB -->
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label mb-1 fw-medium">DOB<span
                                                    class="text-danger ms-1">*</span></label>
                                            <div class="input-icon-end position-relative">
                                                <input type="date"
                                                    class="form-control @error('dob') is-invalid @enderror" name="dob"
                                                    value="{{ old('dob', $patient->dob ? $patient->dob->format('Y-m-d') : '') }}"
                                                    required>
                                                <span class="input-icon-addon"><i class="ti ti-calendar"></i></span>
                                            </div>
                                            @error('dob')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Gender -->
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label mb-1 fw-medium">Gender<span
                                                    class="text-danger ms-1">*</span></label>
                                            <select class="form-select @error('gender') is-invalid @enderror"
                                                name="gender" required>
                                                <option value="">Select</option>
                                                <option value="male"
                                                    {{ old('gender', $patient->gender) == 'male' ? 'selected' : '' }}>Male
                                                </option>
                                                <option value="female"
                                                    {{ old('gender', $patient->gender) == 'female' ? 'selected' : '' }}>
                                                    Female</option>
                                                <option value="other"
                                                    {{ old('gender', $patient->gender) == 'other' ? 'selected' : '' }}>
                                                    Other</option>
                                            </select>
                                            @error('gender')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Blood Group -->
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label mb-1 fw-medium">Blood Group</label>
                                            <select class="form-select @error('blood_group') is-invalid @enderror"
                                                name="blood_group">
                                                <option value="">Select</option>
                                                @foreach (['O+', 'O-', 'A+', 'A-', 'B+', 'B-', 'AB+', 'AB-'] as $bg)
                                                    <option value="{{ $bg }}"
                                                        {{ old('blood_group', $patient->blood_group) == $bg ? 'selected' : '' }}>
                                                        {{ $bg }}</option>
                                                @endforeach
                                            </select>
                                            @error('blood_group')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Status -->
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label mb-1 fw-medium">Status<span
                                                    class="text-danger ms-1">*</span></label>
                                            <select class="form-select @error('status') is-invalid @enderror"
                                                name="status" required>
                                                <option value="">Select</option>
                                                <option value="available"
                                                    {{ old('status', $patient->status) == 'available' ? 'selected' : '' }}>
                                                    Available</option>
                                                <option value="unavailable"
                                                    {{ old('status', $patient->status) == 'unavailable' ? 'selected' : '' }}>
                                                    Unavailable</option>
                                            </select>
                                            @error('status')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Address Information -->
                                <h6 class="fw-bold mb-3 border-top pt-4">Address Information</h6>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label mb-1 fw-medium">Address 1<span
                                                    class="text-danger ms-1">*</span></label>
                                            <input type="text"
                                                class="form-control @error('address_1') is-invalid @enderror"
                                                name="address_1" value="{{ old('address_1', $patient->address_1) }}"
                                                required>
                                            @error('address_1')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label mb-1 fw-medium">Address 2</label>
                                            <input type="text"
                                                class="form-control @error('address_2') is-invalid @enderror"
                                                name="address_2" value="{{ old('address_2', $patient->address_2) }}">
                                            @error('address_2')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label class="form-label mb-1">Country<span
                                                    class="text-danger ms-1">*</span></label>
                                            <input type="text"
                                                class="form-control @error('country') is-invalid @enderror"
                                                name="country" value="{{ old('country', $patient->country) }}" required>
                                            @error('country')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label class="form-label mb-1">State<span
                                                    class="text-danger ms-1">*</span></label>
                                            <input type="text"
                                                class="form-control @error('state') is-invalid @enderror" name="state"
                                                value="{{ old('state', $patient->state) }}" required>
                                            @error('state')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label class="form-label mb-1">City<span
                                                    class="text-danger ms-1">*</span></label>
                                            <input type="text"
                                                class="form-control @error('city') is-invalid @enderror" name="city"
                                                value="{{ old('city', $patient->city) }}" required>
                                            @error('city')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label class="form-label mb-1">Pincode<span
                                                    class="text-danger ms-1">*</span></label>
                                            <input type="text"
                                                class="form-control @error('pincode') is-invalid @enderror"
                                                name="pincode" value="{{ old('pincode', $patient->pincode) }}" required>
                                            @error('pincode')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Vital Signs -->
                                <h6 class="fw-bold mb-3 border-top pt-4">Vital Signs</h6>
                                <div class="row">
                                    @foreach (['vat' => 'Vat', 'pit' => 'Pit', 'kuff' => 'Kuff', 'bp' => 'BP', 'temp' => 'Temperature', 'pulse' => 'Pulse', 'weight' => 'Weight', 'tongue' => 'Tongue', 'nails' => 'Nails'] as $field => $label)
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label class="form-label">{{ $label }}</label>
                                                <input type="text" class="form-control" name="{{ $field }}"
                                                    value="{{ old($field, $patient->$field) }}">
                                            </div>
                                        </div>
                                    @endforeach
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">Cerebral Fluid</label>
                                            <select class="form-select" name="cerebral_fluid">
                                                @foreach (['normal' => 'Normal', 'shrink' => 'Shrink', 'expand' => 'Expand'] as $val => $txt)
                                                    <option value="{{ $val }}"
                                                        {{ old('cerebral_fluid', $patient->cerebral_fluid) == $val ? 'selected' : '' }}>
                                                        {{ $txt }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <!-- Symptoms Assessment -->
                                <h6 class="fw-bold mb-3 border-top pt-4">Symptoms Assessment</h6>
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <p class="text-muted small mb-3">
                                            <i class="ti ti-info-circle me-1"></i>
                                            <strong>Tip:</strong> Hold <kbd>Ctrl</kbd> (Windows) or <kbd>Cmd</kbd> (Mac) to
                                            select multiple.
                                        </p>
                                        <div class="row">
                                            <!-- Existing Symptoms -->
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label fw-medium text-primary">
                                                        <i class="ti ti-circle-check me-1"></i>Existing Symptoms
                                                    </label>
                                                    <p class="text-muted small">Select symptoms that are PRESENT</p>
                                                    <select
                                                        class="form-select select2-multiple @error('existing_symptoms') is-invalid @enderror"
                                                        name="existing_symptoms[]" multiple="multiple"
                                                        data-placeholder="Select...">
                                                        <option value=""></option>
                                                        @php
                                                            $allSymptoms = [
                                                                'Autism',
                                                                'ADHD',
                                                                'Speech Disorder',
                                                                'Eye Contact',
                                                                'Toe Walking',
                                                                'Stubborn',
                                                                'Repetitive Behaviour',
                                                                'Seizers',
                                                                'Hand Flapping',
                                                                'Sleep Problem',
                                                                'Choosy at Eat',
                                                                'Teeth Grinding',
                                                                'Sweating',
                                                                'Stool Trained',
                                                                'Concentration',
                                                                'Super Hyper',
                                                                'Hyperactive',
                                                                'Aggressive',
                                                                'Understanding',
                                                                'Chewing Problem',
                                                                'Command Follow',
                                                                'Socialization',
                                                                'Jumping',
                                                                'Sensory Nerves',
                                                                'Motor Nerves',
                                                                'Self Talk',
                                                                'Self Bite',
                                                                'Bite Other',
                                                                'Self Hit',
                                                                'Hit Other',
                                                                'Self Laugh',
                                                                'Self Cry',
                                                            ];
                                                        @endphp
                                                        @foreach ($allSymptoms as $symptom)
                                                            <option value="{{ $symptom }}"
                                                                {{ in_array($symptom, old('existing_symptoms', $patient->existing_symptoms ?? [])) ? 'selected' : '' }}>
                                                                {{ $symptom }}</option>
                                                        @endforeach
                                                    </select>
                                                    @error('existing_symptoms')
                                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <!-- Non-Existing Symptoms -->
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label fw-medium text-danger">
                                                        <i class="ti ti-circle-x me-1"></i>Non-Existing Symptoms
                                                    </label>
                                                    <p class="text-muted small">Select symptoms that are ABSENT</p>
                                                    <select
                                                        class="form-select select2-multiple @error('non_existing_symptoms') is-invalid @enderror"
                                                        name="non_existing_symptoms[]" multiple="multiple"
                                                        data-placeholder="Select...">
                                                        <option value=""></option>
                                                        @foreach ($allSymptoms as $symptom)
                                                            <option value="{{ $symptom }}"
                                                                {{ in_array($symptom, old('non_existing_symptoms', $patient->non_existing_symptoms ?? [])) ? 'selected' : '' }}>
                                                                {{ $symptom }}</option>
                                                        @endforeach
                                                    </select>
                                                    @error('non_existing_symptoms')
                                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Quick Stats -->
                                        <div class="row mt-3">
                                            <div class="col-md-4">
                                                <div class="card border-primary">
                                                    <div class="card-body py-2 px-3"><small class="text-muted">Total
                                                            Symptoms</small>
                                                        <h5 class="mb-0 text-primary">{{ count($allSymptoms) }}</h5>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="card border-success">
                                                    <div class="card-body py-2 px-3"><small class="text-muted">Selected
                                                            Existing</small>
                                                        <h5 class="mb-0 text-success" id="existing-count">0</h5>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="card border-danger">
                                                    <div class="card-body py-2 px-3"><small class="text-muted">Selected
                                                            Non-Existing</small>
                                                        <h5 class="mb-0 text-danger" id="non-existing-count">0</h5>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- C.P & Medical Notes -->
                                <div class="row mt-4">
                                    <div class="col-md-4">
                                        <div class="card border">
                                            <div class="card-body">
                                                <h6 class="fw-bold mb-2">C.P (Cerebral Palsy)</h6>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="cp"
                                                        value="yes"
                                                        {{ old('cp', $patient->cp) == 'yes' ? 'checked' : '' }}
                                                        id="cp_yes">
                                                    <label class="form-check-label" for="cp_yes">YES</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="cp"
                                                        value="no"
                                                        {{ old('cp', $patient->cp) == 'no' ? 'checked' : '' }}
                                                        id="cp_no">
                                                    <label class="form-check-label" for="cp_no">NO</label>
                                                </div>
                                                <div class="mt-3">
                                                    <small class="text-muted fw-medium">Movement Affected:</small>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox"
                                                            name="cp_movement[]" value="upper_limb"
                                                            {{ in_array('upper_limb', old('cp_movement', $patient->cp_movement ?? [])) ? 'checked' : '' }}
                                                            id="cp_upper">
                                                        <label class="form-check-label" for="cp_upper">Upper Limb</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox"
                                                            name="cp_movement[]" value="lower_limb"
                                                            {{ in_array('lower_limb', old('cp_movement', $patient->cp_movement ?? [])) ? 'checked' : '' }}
                                                            id="cp_lower">
                                                        <label class="form-check-label" for="cp_lower">Lower Limb</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="card border">
                                            <div class="card-body">
                                                <h6 class="fw-bold mb-2">Medical Notes</h6>
                                                <textarea class="form-control" name="medical_notes" rows="5" placeholder="Enter medical notes...">{{ old('medical_notes', $patient->medical_notes) }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Treatment -->
                                <h6 class="fw-bold mb-3 border-top pt-4">Treatment</h6>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label class="form-label fw-medium">Medicine/Therapy</label>
                                            <textarea class="form-control" name="medicine" rows="3">{{ old('medicine', $patient->medicine) }}</textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label class="form-label fw-medium">Therapy History</label>
                                            <textarea class="form-control" name="therapy_history" rows="2">{{ old('therapy_history', $patient->therapy_history) }}</textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label class="form-label fw-medium">Remarks</label>
                                            <textarea class="form-control" name="remarks" rows="2">{{ old('remarks', $patient->remarks) }}</textarea>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex align-items-center justify-content-end mt-3">
                            <a href="{{ route('patients.index') }}" class="btn btn-light me-2">Cancel</a>
                            <button type="reset" class="btn btn-warning me-2">Reset</button>
                            <button type="submit" class="btn btn-primary">
                                <i class="ti ti-device-floppy me-1"></i> Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
