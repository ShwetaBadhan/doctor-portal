@extends('layout.master')

@section('content')
    <div class="page-wrapper">
        <div class="content">
            <div class="row justify-content-center">
                <div class="col-lg-12">
                    <!-- Page Header -->
                    <div class="mb-4">
                        <h6 class="fw-bold mb-0 d-flex align-items-center">
                            <a href="{{ route('patients.index') }}" class="text-dark">
                                <i class="ti ti-chevron-left me-1"></i>Patients
                            </a>
                            <span class="mx-2">/</span>
                            <span class="text-primary">Register New Patient</span>
                        </h6>
                    </div>

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

                    <form action="{{ route('patients.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <!-- Patient Information Card -->
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
                                                <i class="ti ti-user-plus fs-16"></i>
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

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label mb-1 fw-medium">First Name<span
                                                    class="text-danger ms-1">*</span></label>
                                            <input type="text"
                                                class="form-control @error('first_name') is-invalid @enderror"
                                                name="first_name" value="{{ old('first_name') }}" required>
                                            @error('first_name')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label mb-1 fw-medium">Last Name<span
                                                    class="text-danger ms-1">*</span></label>
                                            <input type="text"
                                                class="form-control @error('last_name') is-invalid @enderror"
                                                name="last_name" value="{{ old('last_name') }}" required>
                                            @error('last_name')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label mb-1 fw-medium">Phone Number<span
                                                    class="text-danger ms-1">*</span></label>
                                            <input type="tel" class="form-control @error('phone') is-invalid @enderror"
                                                name="phone" value="{{ old('phone') }}" required>
                                            @error('phone')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label mb-1 fw-medium">Email Address</label>
                                            <input type="email" class="form-control @error('email') is-invalid @enderror"
                                                name="email" value="{{ old('email') }}">
                                            @error('email')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label mb-1 fw-medium">Date of Birth<span
                                                    class="text-danger ms-1">*</span></label>
                                            <input type="date" class="form-control @error('dob') is-invalid @enderror"
                                                name="dob" value="{{ old('dob') }}" required>
                                            @error('dob')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label mb-1 fw-medium">Gender<span
                                                    class="text-danger ms-1">*</span></label>
                                            <select class="form-select @error('gender') is-invalid @enderror" name="gender"
                                                required>
                                                <option value="">Select Gender</option>
                                                <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male
                                                </option>
                                                <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>
                                                    Female</option>
                                                <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>
                                                    Other</option>
                                            </select>
                                            @error('gender')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label mb-1 fw-medium">Blood Group</label>
                                            <select class="form-select @error('blood_group') is-invalid @enderror"
                                                name="blood_group">
                                                <option value="">Select Blood Group</option>
                                                <option value="O+" {{ old('blood_group') == 'O+' ? 'selected' : '' }}>
                                                    O+</option>
                                                <option value="O-" {{ old('blood_group') == 'O-' ? 'selected' : '' }}>
                                                    O-</option>
                                                <option value="A+" {{ old('blood_group') == 'A+' ? 'selected' : '' }}>
                                                    A+</option>
                                                <option value="A-" {{ old('blood_group') == 'A-' ? 'selected' : '' }}>
                                                    A-</option>
                                                <option value="B+" {{ old('blood_group') == 'B+' ? 'selected' : '' }}>
                                                    B+</option>
                                                <option value="B-" {{ old('blood_group') == 'B-' ? 'selected' : '' }}>
                                                    B-</option>
                                                <option value="AB+"
                                                    {{ old('blood_group') == 'AB+' ? 'selected' : '' }}>AB+</option>
                                                <option value="AB-"
                                                    {{ old('blood_group') == 'AB-' ? 'selected' : '' }}>AB-</option>
                                            </select>
                                            @error('blood_group')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label mb-1 fw-medium">Primary Doctor</label>
                                            <input type="text"
                                                class="form-control @error('primary_doctor') is-invalid @enderror"
                                                name="primary_doctor" value="{{ old('primary_doctor') }}">
                                            @error('primary_doctor')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label mb-1 fw-medium">Status<span
                                                    class="text-danger ms-1">*</span></label>
                                            <select class="form-select @error('status') is-invalid @enderror"
                                                name="status" required>
                                                <option value="">Select Status</option>
                                                <option value="available"
                                                    {{ old('status') == 'available' ? 'selected' : '' }}>Available</option>
                                                <option value="unavailable"
                                                    {{ old('status') == 'unavailable' ? 'selected' : '' }}>Unavailable
                                                </option>
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
                                                name="address_1" value="{{ old('address_1') }}" required>
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
                                                name="address_2" value="{{ old('address_2') }}">
                                            @error('address_2')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label mb-1 fw-medium">Country<span
                                                    class="text-danger ms-1">*</span></label>
                                            <input type="text"
                                                class="form-control @error('country') is-invalid @enderror"
                                                name="country" value="{{ old('country') }}" required>
                                            @error('country')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label mb-1 fw-medium">State<span
                                                    class="text-danger ms-1">*</span></label>
                                            <input type="text"
                                                class="form-control @error('state') is-invalid @enderror" name="state"
                                                value="{{ old('state') }}" required>
                                            @error('state')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label mb-1 fw-medium">City<span
                                                    class="text-danger ms-1">*</span></label>
                                            <input type="text"
                                                class="form-control @error('city') is-invalid @enderror" name="city"
                                                value="{{ old('city') }}" required>
                                            @error('city')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label mb-1 fw-medium">Pincode<span
                                                    class="text-danger ms-1">*</span></label>
                                            <input type="text"
                                                class="form-control @error('pincode') is-invalid @enderror"
                                                name="pincode" value="{{ old('pincode') }}" required>
                                            @error('pincode')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Vital Signs -->
                                <h6 class="fw-bold mb-3 border-top pt-4">Vital Signs</h6>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">Vat</label>
                                            <input type="text" class="form-control" name="vat"
                                                value="{{ old('vat') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">Pit</label>
                                            <input type="text" class="form-control" name="pit"
                                                value="{{ old('pit') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">Kuff</label>
                                            <input type="text" class="form-control" name="kuff"
                                                value="{{ old('kuff') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">BP</label>
                                            <input type="text" class="form-control" name="bp"
                                                value="{{ old('bp') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">Temperature</label>
                                            <input type="text" class="form-control" name="temp"
                                                value="{{ old('temp') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">Pulse</label>
                                            <input type="text" class="form-control" name="pulse"
                                                value="{{ old('pulse') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">Weight</label>
                                            <input type="text" class="form-control" name="weight"
                                                value="{{ old('weight') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">Tongue</label>
                                            <input type="text" class="form-control" name="tongue"
                                                value="{{ old('tongue') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">Nails</label>
                                            <input type="text" class="form-control" name="nails"
                                                value="{{ old('nails') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">Cerebral Fluid</label>
                                            <select class="form-select" name="cerebral_fluid">
                                                <option value="normal"
                                                    {{ old('cerebral_fluid') == 'normal' ? 'selected' : '' }}>Normal
                                                </option>
                                                <option value="shrink"
                                                    {{ old('cerebral_fluid') == 'shrink' ? 'selected' : '' }}>Shrink
                                                </option>
                                                <option value="expand"
                                                    {{ old('cerebral_fluid') == 'expand' ? 'selected' : '' }}>Expand
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                           <!-- Symptoms Assessment Section -->
<h6 class="fw-bold mb-3 border-top pt-4">Symptoms Assessment</h6>
<div class="card bg-light">
    <div class="card-body">
        <p class="text-muted small mb-3">
            <i class="ti ti-info-circle me-1"></i>
            <strong>Tip:</strong> Hold <kbd>Ctrl</kbd> (Windows) or <kbd>Cmd</kbd> (Mac) to select multiple symptoms.
        </p>
        
        <div class="row">
            <!-- Existing Symptoms Multi-Select -->
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label fw-medium text-primary">
                        <i class="ti ti-circle-check me-1"></i>Existing Symptoms
                    </label>
                    <p class="text-muted small">Select symptoms that are PRESENT in patient</p>
                    <select class="form-select select2-multiple @error('existing_symptoms') is-invalid @enderror" 
                            name="existing_symptoms[]" 
                            multiple="multiple"
                            data-placeholder="Select existing symptoms...">
                        <option value=""></option>
                        @php
                            $allSymptoms = [
                                'Autism', 'ADHD', 'Speech Disorder', 'Eye Contact', 'Toe Walking',
                                'Stubborn', 'Repetitive Behaviour', 'Seizers', 'Hand Flapping', 
                                'Sleep Problem', 'Choosy at Eat', 'Teeth Grinding', 'Sweating',
                                'Stool Trained', 'Concentration', 'Super Hyper', 'Hyperactive',
                                'Aggressive', 'Understanding', 'Chewing Problem', 'Command Follow',
                                'Socialization', 'Jumping', 'Sensory Nerves', 'Motor Nerves',
                                'Self Talk', 'Self Bite', 'Bite Other', 'Self Hit', 'Hit Other',
                                'Self Laugh', 'Self Cry'
                            ];
                        @endphp
                        @foreach($allSymptoms as $symptom)
                            <option value="{{ $symptom }}" 
                                {{ in_array($symptom, old('existing_symptoms', [])) ? 'selected' : '' }}>
                                {{ $symptom }}
                            </option>
                        @endforeach
                    </select>
                    @error('existing_symptoms')
                        <span class="invalid-feedback d-block">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <!-- Non-Existing Symptoms Multi-Select -->
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label fw-medium text-danger">
                        <i class="ti ti-circle-x me-1"></i>Non-Existing Symptoms
                    </label>
                    <p class="text-muted small">Select symptoms that are ABSENT in patient</p>
                    <select class="form-select select2-multiple @error('non_existing_symptoms') is-invalid @enderror" 
                            name="non_existing_symptoms[]" 
                            multiple="multiple"
                            data-placeholder="Select non-existing symptoms...">
                        <option value=""></option>
                        @foreach($allSymptoms as $symptom)
                            <option value="{{ $symptom }}" 
                                {{ in_array($symptom, old('non_existing_symptoms', [])) ? 'selected' : '' }}>
                                {{ $symptom }}
                            </option>
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
                    <div class="card-body py-2 px-3">
                        <small class="text-muted">Total Symptoms</small>
                        <h5 class="mb-0 text-primary">{{ count($allSymptoms) }}</h5>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-success">
                    <div class="card-body py-2 px-3">
                        <small class="text-muted">Selected Existing</small>
                        <h5 class="mb-0 text-success" id="existing-count">0</h5>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-danger">
                    <div class="card-body py-2 px-3">
                        <small class="text-muted">Selected Non-Existing</small>
                        <h5 class="mb-0 text-danger" id="non-existing-count">0</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Cerebral Palsy & Medical Notes (Keep this separate) -->
<div class="row mt-4">
    <div class="col-md-4">
        <div class="card border">
            <div class="card-body">
                <h6 class="fw-bold mb-2">C.P (Cerebral Palsy)</h6>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="cp" value="yes" 
                           {{ old('cp') == 'yes' ? 'checked' : '' }} id="cp_yes">
                    <label class="form-check-label" for="cp_yes">YES</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="cp" value="no" 
                           {{ old('cp') == 'no' ? 'checked' : '' }} id="cp_no">
                    <label class="form-check-label" for="cp_no">NO</label>
                </div>
                <div class="mt-3">
                    <small class="text-muted fw-medium">Movement Affected:</small>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="cp_movement[]" value="upper_limb" 
                               {{ in_array('upper_limb', old('cp_movement', [])) ? 'checked' : '' }} id="cp_upper">
                        <label class="form-check-label" for="cp_upper">Upper Limb</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="cp_movement[]" value="lower_limb" 
                               {{ in_array('lower_limb', old('cp_movement', [])) ? 'checked' : '' }} id="cp_lower">
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
                <textarea class="form-control" name="medical_notes" rows="5" 
                          placeholder="Example: Whole of AB infection with filterization of liver, pre and pro biotics, neuro fever, dry cough, deficiency of O2 and RBC in brain...">{{ old('medical_notes') }}</textarea>
            </div>
        </div>
    </div>
</div>

                         

                                <!-- Medicine & Remarks -->
                                <h6 class="fw-bold mb-3 border-top pt-4">Treatment</h6>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label class="form-label fw-medium">Medicine/Therapy</label>
                                            <textarea class="form-control" name="medicine" rows="3">{{ old('medicine') }}</textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label class="form-label fw-medium">Therapy History</label>
                                            <textarea class="form-control" name="therapy_history" rows="2">{{ old('therapy_history') }}</textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label class="form-label fw-medium">Remarks</label>
                                            <textarea class="form-control" name="remarks" rows="2">{{ old('remarks') }}</textarea>
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
                                <i class="ti ti-plus me-1"></i> Register Patient
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
