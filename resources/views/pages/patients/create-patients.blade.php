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

                <!-- SweetAlert Session Messages -->
                @if (session('success'))
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success!',
                                text: @json(session('success')),
                                timer: 4000,
                                timerProgressBar: true,
                                showConfirmButton: false,
                                toast: true,
                                position: 'top-end'
                            });
                        });
                    </script>
                @endif

                @if (session('error'))
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: @json(session('error')),
                                confirmButtonColor: '#dc3545'
                            });
                        });
                    </script>
                @endif

                @if ($errors->any())
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            const errorList = @json($errors->all()).map(err => `<li>${err}</li>`).join('');
                            Swal.fire({
                                icon: 'error',
                                title: 'Validation Error',
                                html: `<ul class="text-start mb-0">${errorList}</ul>`,
                                confirmButtonText: 'Got it',
                                confirmButtonColor: '#dc3545'
                            });
                        });
                    </script>
                @endif

                <form action="{{ route('patients.store') }}" method="POST" enctype="multipart/form-data" id="patientForm">
                    @csrf
                    
                    <!-- Progress Stepper -->
                    <div class="card mb-4">
                        <div class="card-body py-3">
                            <div class="progress-steps d-flex justify-content-between position-relative">
                                @php
                                    $steps = [
                                        1 => ['icon' => 'ti ti-user', 'label' => 'Basic Info'],
                                        2 => ['icon' => 'ti ti-map-pin', 'label' => 'Address'],
                                        3 => ['icon' => 'ti ti-heartbeat', 'label' => 'Vitals'],
                                        4 => ['icon' => 'ti ti-notes', 'label' => 'Symptoms'],
                                        5 => ['icon' => 'ti ti-pills', 'label' => 'Treatment'],
                                        6 => ['icon' => 'ti ti-file', 'label' => 'Documents'],
                                    ];
                                @endphp
                                @foreach($steps as $num => $step)
                                    <div class="step-item text-center {{ $num == 1 ? 'active' : '' }}" data-step="{{ $num }}">
                                        <div class="step-icon rounded-circle bg-light text-primary d-inline-flex align-items-center justify-content-center">
                                            <i class="{{ $step['icon'] }}"></i>
                                        </div>
                                        <small class="d-block mt-1 text-muted">{{ $step['label'] }}</small>
                                    </div>
                                    @if(!$loop->last)
                                        <div class="progress-line {{ $num == 1 ? 'active' : '' }}"></div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Step 1: Patient Information -->
                    <div class="card step-content active" data-step="1">
                        <div class="card-body pb-0">
                            <h6 class="fw-bold mb-3">Patient Information</h6>
                            <div class="row">
                                <!-- Profile Image -->
                                <div class="col-lg-12">
                                    <div class="mb-3 d-flex align-items-center">
                                        <label class="form-label mb-0">Profile Image</label>
                                        <div class="drag-upload-btn avatar avatar-xxl rounded-circle bg-light text-muted position-relative overflow-hidden z-1 mb-2 ms-4 p-0">
                                            <i class="ti ti-user-plus fs-16"></i>
                                            <input type="file" class="form-control image-sign" name="profile_image" accept="image/*">
                                            <div class="position-absolute bottom-0 end-0 star-0 w-100 h-25 bg-dark d-flex align-items-center justify-content-center z-n1">
                                                <a href="javascript:void(0);" class="text-white d-flex align-items-center justify-content-center">
                                                    <i class="ti ti-photo fs-14"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label mb-1 fw-medium">First Name<span class="text-danger ms-1">*</span></label>
                                        <input type="text" class="form-control @error('first_name') is-invalid @enderror" name="first_name" value="{{ old('first_name') }}" required>
                                        @error('first_name')<span class="invalid-feedback">{{ $message }}</span>@enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label mb-1 fw-medium">Last Name<span class="text-danger ms-1">*</span></label>
                                        <input type="text" class="form-control @error('last_name') is-invalid @enderror" name="last_name" value="{{ old('last_name') }}" required>
                                        @error('last_name')<span class="invalid-feedback">{{ $message }}</span>@enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label mb-1 fw-medium">Phone Number<span class="text-danger ms-1">*</span></label>
                                        <input type="tel" class="form-control @error('phone') is-invalid @enderror" name="phone" value="{{ old('phone') }}" required>
                                        @error('phone')<span class="invalid-feedback">{{ $message }}</span>@enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label mb-1 fw-medium">Email Address</label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}">
                                        @error('email')<span class="invalid-feedback">{{ $message }}</span>@enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label mb-1 fw-medium">Date of Birth<span class="text-danger ms-1">*</span></label>
                                        <input type="date" class="form-control @error('dob') is-invalid @enderror" name="dob" value="{{ old('dob') }}" required>
                                        @error('dob')<span class="invalid-feedback">{{ $message }}</span>@enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label mb-1 fw-medium">Gender<span class="text-danger ms-1">*</span></label>
                                        <select class="select @error('gender') is-invalid @enderror" name="gender" required>
                                            <option value="">Select Gender</option>
                                            <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                                            <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                                            <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Other</option>
                                        </select>
                                        @error('gender')<span class="invalid-feedback">{{ $message }}</span>@enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label mb-1 fw-medium">Blood Group</label>
                                        <select class="select @error('blood_group') is-invalid @enderror" name="blood_group">
                                            <option value="">Select Blood Group</option>
                                            @foreach(['O+','O-','A+','A-','B+','B-','AB+','AB-'] as $bg)
                                                <option value="{{ $bg }}" {{ old('blood_group') == $bg ? 'selected' : '' }}>{{ $bg }}</option>
                                            @endforeach
                                        </select>
                                        @error('blood_group')<span class="invalid-feedback">{{ $message }}</span>@enderror
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label mb-1 fw-medium">Primary Doctor</label>
                                        <input type="text" class="form-control @error('primary_doctor') is-invalid @enderror" name="primary_doctor" value="{{ old('primary_doctor') }}">
                                        @error('primary_doctor')<span class="invalid-feedback">{{ $message }}</span>@enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label mb-1 fw-medium">Status<span class="text-danger ms-1">*</span></label>
                                        <select class="select @error('status') is-invalid @enderror" name="status" required>
                                            <option value="">Select Status</option>
                                            <option value="available" {{ old('status') == 'available' ? 'selected' : '' }}>Available</option>
                                            <option value="unavailable" {{ old('status') == 'unavailable' ? 'selected' : '' }}>Unavailable</option>
                                        </select>
                                        @error('status')<span class="invalid-feedback">{{ $message }}</span>@enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Step 2: Address Information -->
                    <div class="card step-content" data-step="2">
                        <div class="card-body pb-0">
                            <h6 class="fw-bold mb-3">Address Information</h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label mb-1 fw-medium">Address 1<span class="text-danger ms-1">*</span></label>
                                        <input type="text" class="form-control @error('address_1') is-invalid @enderror" name="address_1" value="{{ old('address_1') }}" required>
                                        @error('address_1')<span class="invalid-feedback">{{ $message }}</span>@enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label mb-1 fw-medium">Address 2</label>
                                        <input type="text" class="form-control @error('address_2') is-invalid @enderror" name="address_2" value="{{ old('address_2') }}">
                                        @error('address_2')<span class="invalid-feedback">{{ $message }}</span>@enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label mb-1 fw-medium">Country<span class="text-danger ms-1">*</span></label>
                                        <input type="text" class="form-control @error('country') is-invalid @enderror" name="country" value="{{ old('country') }}" required>
                                        @error('country')<span class="invalid-feedback">{{ $message }}</span>@enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label mb-1 fw-medium">State<span class="text-danger ms-1">*</span></label>
                                        <input type="text" class="form-control @error('state') is-invalid @enderror" name="state" value="{{ old('state') }}" required>
                                        @error('state')<span class="invalid-feedback">{{ $message }}</span>@enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label mb-1 fw-medium">City<span class="text-danger ms-1">*</span></label>
                                        <input type="text" class="form-control @error('city') is-invalid @enderror" name="city" value="{{ old('city') }}" required>
                                        @error('city')<span class="invalid-feedback">{{ $message }}</span>@enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label mb-1 fw-medium">Pincode<span class="text-danger ms-1">*</span></label>
                                        <input type="text" class="form-control @error('pincode') is-invalid @enderror" name="pincode" value="{{ old('pincode') }}" required>
                                        @error('pincode')<span class="invalid-feedback">{{ $message }}</span>@enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Step 3: Vital Signs -->
                    <div class="card step-content" data-step="3">
                        <div class="card-body pb-0">
                            <h6 class="fw-bold mb-3">Vital Signs</h6>
                            <div class="row">
                                @php
                                    $vitals = ['vat','pit','kuff','bp','temp','pulse','weight','tongue','nails'];
                                @endphp
                                @foreach($vitals as $vital)
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">{{ ucfirst($vital) }}</label>
                                        <input type="text" class="form-control" name="{{ $vital }}" value="{{ old($vital) }}">
                                    </div>
                                </div>
                                @endforeach
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">Cerebral Fluid</label>
                                        <select class="select" name="cerebral_fluid">
                                            @foreach(['normal','shrink','expand'] as $opt)
                                                <option value="{{ $opt }}" {{ old('cerebral_fluid') == $opt ? 'selected' : '' }}>{{ ucfirst($opt) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Step 4: Symptoms Assessment (Checkboxes) -->
                    <div class="card step-content" data-step="4">
                        <div class="card-body">
                            <h6 class="fw-bold mb-3">Symptoms Assessment</h6>
                            <p class="text-muted small mb-3">
                                <i class="ti ti-info-circle me-1"></i>
                                Select symptoms that are present or absent in the patient.
                            </p>
                            
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

                            <div class="row">
                                <!-- Existing Symptoms Checkboxes -->
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-medium text-primary">
                                            <i class="ti ti-circle-check me-1"></i>Existing Symptoms
                                        </label>
                                        <p class="text-muted small">Symptoms PRESENT in patient</p>
                                        <div class="checkbox-grid border rounded p-3 bg-light" style="max-height: 200px; overflow-y: auto;">
                                            @foreach($allSymptoms as $symptom)
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input symptom-checkbox" type="checkbox" 
                                                       name="existing_symptoms[]" value="{{ $symptom }}" 
                                                       id="exist_{{ str_replace(' ', '_', $symptom) }}"
                                                       {{ in_array($symptom, old('existing_symptoms', [])) ? 'checked' : '' }}>
                                                <label class="form-check-label small" for="exist_{{ str_replace(' ', '_', $symptom) }}">
                                                    {{ $symptom }}
                                                </label>
                                            </div>
                                            @endforeach
                                        </div>
                                        @error('existing_symptoms')
                                            <span class="invalid-feedback d-block">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Non-Existing Symptoms Checkboxes -->
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-medium text-danger">
                                            <i class="ti ti-circle-x me-1"></i>Non-Existing Symptoms
                                        </label>
                                        <p class="text-muted small">Symptoms ABSENT in patient</p>
                                        <div class="checkbox-grid border rounded p-3 bg-light" style="max-height: 200px; overflow-y: auto;">
                                            @foreach($allSymptoms as $symptom)
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input symptom-checkbox" type="checkbox" 
                                                       name="non_existing_symptoms[]" value="{{ $symptom }}" 
                                                       id="nonexist_{{ str_replace(' ', '_', $symptom) }}"
                                                       {{ in_array($symptom, old('non_existing_symptoms', [])) ? 'checked' : '' }}>
                                                <label class="form-check-label small" for="nonexist_{{ str_replace(' ', '_', $symptom) }}">
                                                    {{ $symptom }}
                                                </label>
                                            </div>
                                            @endforeach
                                        </div>
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

                            <!-- C.P & Medical Notes -->
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
                                                      placeholder="Example: Whole of AB infection with filterization of liver...">{{ old('medical_notes') }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Step 5: Treatment -->
                    <div class="card step-content" data-step="5">
                        <div class="card-body pb-0">
                            <h6 class="fw-bold mb-3">Treatment</h6>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label class="form-label fw-medium">Medicine/Therapy</label>
                                        <textarea class="form-control" name="medicine" rows="3" >{{ old('medicine') }}</textarea>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label class="form-label fw-medium">Therapy History</label>
                                        <textarea class="form-control" name="therapy_history" rows="2" placeholder="Please Enter the History of the Patient">{{ old('therapy_history') }}</textarea>
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

                    <!-- Step 6: Documents (Test Reports) -->
                    <div class="card step-content" data-step="6">
                        <div class="card-body pb-0">
                            <h6 class="fw-bold mb-3">Test Reports & Documents</h6>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="mb-3">
                                        <label class="form-label fw-medium">Upload Reports</label>
                                        <input type="file" 
                                               class="form-control @error('test_reports.*') is-invalid @enderror" 
                                               name="test_reports[]" 
                                               multiple 
                                               accept=".pdf,.jpg,.jpeg,.png,.doc,.docx"
                                               id="testReportsInput">
                                        <small class="text-muted d-block mt-1">
                                            <i class="ti ti-info-circle me-1"></i>
                                            Allowed: PDF, JPG, PNG, DOC, DOCX (Max 5MB each)
                                        </small>
                                        @error('test_reports.*')
                                            <span class="invalid-feedback d-block">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    
                                    <!-- File Preview Area -->
                                    <div id="file-preview" class="mt-3">
                                        <!-- Preview items will be added here by JS -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Navigation Buttons -->
                    <div class="d-flex align-items-center justify-content-between mt-3">
                        <button type="button" class="btn btn-light" id="prevBtn" disabled>
                            <i class="ti ti-chevron-left me-1"></i> Previous
                        </button>
                        <div>
                            <a href="{{ route('patients.index') }}" class="btn btn-light me-2">Cancel</a>
                            <button type="button" class="btn btn-warning me-2" id="resetBtn">Reset</button>
                            <button type="button" class="btn btn-primary" id="nextBtn">
                                Next <i class="ti ti-chevron-right ms-1"></i>
                            </button>
                            <button type="submit" class="btn btn-primary d-none" id="submitBtn">
                                <i class="ti ti-plus me-1"></i> Register Patient
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Minimal CSS for Steps -->
<style>
    .progress-steps { counter-reset: step; }
    .step-item { position: relative; z-index: 1; flex: 1; }
    .step-icon { width: 40px; height: 40px; font-size: 18px; margin: 0 auto 4px; transition: all 0.2s; }
    .step-item.active .step-icon { background: #2E37A4 !important; color: #fff !important; }
    .step-item.active small { color: #2E37A4 !important; font-weight: 500; }
    .progress-line { position: absolute; top: 20px; left: 0; right: 0; height: 2px; background: #e9ecef; z-index: 0; }
    .progress-line.active { background: #2E37A4; }
    .step-content { display: none; }
    .step-content.active { display: block; }
    .checkbox-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 4px 12px; }
    .form-check-inline { margin-right: 0; margin-bottom: 2px; }
    
    /* File preview styles */
    .file-preview-item {
        display: flex;
        align-items: center;
        padding: 8px 12px;
        border: 1px solid #e9ecef;
        border-radius: 6px;
        background: #f8f9fa;
        margin-bottom: 8px;
    }
    .file-preview-item .file-icon {
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #e9ecef;
        border-radius: 4px;
        margin-right: 10px;
    }
    .file-preview-item .file-info {
        flex: 1;
        min-width: 0;
    }
    .file-preview-item .file-name {
        font-weight: 500;
        font-size: 13px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 200px;
    }
    .file-preview-item .file-size {
        font-size: 11px;
        color: #6c757d;
    }
    .file-preview-item .file-remove {
        color: #dc3545;
        cursor: pointer;
        padding: 4px;
    }
    
    @media (max-width: 768px) {
        .checkbox-grid { grid-template-columns: 1fr; }
        .progress-steps { flex-wrap: wrap; gap: 8px; }
        .step-item { flex: 0 0 30%; }
        .file-preview-item .file-name { max-width: 150px; }
    }
</style>

<!-- SweetAlert2 CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Minimal JS for Step Navigation + File Preview -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    let currentStep = 1;
    const totalSteps = 6; // ✅ FIXED: Updated from 5 to 6
    const steps = document.querySelectorAll('.step-content');
    const stepItems = document.querySelectorAll('.step-item');
    const progressLines = document.querySelectorAll('.progress-line');
    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');
    const submitBtn = document.getElementById('submitBtn');
    const resetBtn = document.getElementById('resetBtn');
    const form = document.getElementById('patientForm');
    const fileInput = document.getElementById('testReportsInput');
    const filePreview = document.getElementById('file-preview');

    function showStep(step) {
        steps.forEach(s => s.classList.remove('active'));
        document.querySelector(`.step-content[data-step="${step}"]`).classList.add('active');
        
        stepItems.forEach((item, idx) => {
            item.classList.toggle('active', idx + 1 <= step);
        });
        progressLines.forEach((line, idx) => {
            line.classList.toggle('active', idx + 1 < step);
        });

        prevBtn.disabled = (step === 1);
        if (step === totalSteps) {
            nextBtn.classList.add('d-none');
            submitBtn.classList.remove('d-none');
        } else {
            nextBtn.classList.remove('d-none');
            submitBtn.classList.add('d-none');
        }
        updateSymptomCounts();
    }

    function validateStep(step) {
        const currentCard = document.querySelector(`.step-content[data-step="${step}"]`);
        const required = currentCard.querySelectorAll('[required]');
        let valid = true;
        
        required.forEach(field => {
            if (!field.value.trim()) {
                valid = false;
                field.classList.add('is-invalid');
            } else {
                field.classList.remove('is-invalid');
            }
        });
        return valid;
    }

    function updateSymptomCounts() {
        const existCount = document.querySelectorAll('input[name="existing_symptoms[]"]:checked').length;
        const nonExistCount = document.querySelectorAll('input[name="non_existing_symptoms[]"]:checked').length;
        document.getElementById('existing-count').textContent = existCount;
        document.getElementById('non-existing-count').textContent = nonExistCount;
    }

    // File Preview Functionality
    function getFileIcon(ext) {
        const icons = {
            'pdf': 'ti ti-file-text text-danger',
            'jpg': 'ti ti-photo text-primary',
            'jpeg': 'ti ti-photo text-primary',
            'png': 'ti ti-photo text-primary',
            'doc': 'ti ti-file-text text-info',
            'docx': 'ti ti-file-text text-info',
        };
        return icons[ext.toLowerCase()] || 'ti ti-file text-muted';
    }

    function formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }

    function updateFilePreview(files) {
        filePreview.innerHTML = '';
        Array.from(files).forEach((file, index) => {
            const ext = file.name.split('.').pop();
            const iconClass = getFileIcon(ext);
            
            const item = document.createElement('div');
            item.className = 'file-preview-item';
            item.innerHTML = `
                <div class="file-icon">
                    <i class="${iconClass} fs-14"></i>
                </div>
                <div class="file-info">
                    <div class="file-name" title="${file.name}">${file.name}</div>
                    <div class="file-size">${formatFileSize(file.size)}</div>
                </div>
                <div class="file-remove" onclick="removeFile(${index})">
                    <i class="ti ti-x fs-14"></i>
                </div>
            `;
            filePreview.appendChild(item);
        });
    }

    // Make removeFile globally accessible
    window.removeFile = function(index) {
        const dt = new DataTransfer();
        const files = fileInput.files;
        for (let i = 0; i < files.length; i++) {
            if (i !== index) dt.items.add(files[i]);
        }
        fileInput.files = dt.files;
        updateFilePreview(fileInput.files);
    };

    // Event Listeners
    if (fileInput) {
        fileInput.addEventListener('change', function(e) {
            updateFilePreview(e.target.files);
        });
    }

    nextBtn.addEventListener('click', () => {
        if (validateStep(currentStep)) {
            if (currentStep < totalSteps) {
                currentStep++;
                showStep(currentStep);
            }
        } else {
            Swal.fire({
                icon: 'warning',
                title: 'Incomplete Form',
                text: 'Please fill all required fields in this step.',
                confirmButtonColor: '#2E37A4'
            });
        }
    });

    prevBtn.addEventListener('click', () => {
        if (currentStep > 1) {
            currentStep--;
            showStep(currentStep);
        }
    });

    resetBtn.addEventListener('click', () => {
        Swal.fire({
            title: 'Reset Form?',
            text: 'All entered data will be cleared.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, reset',
            cancelButtonText: 'Cancel',
            confirmButtonColor: '#ffc107',
            cancelButtonColor: '#6c757d'
        }).then((result) => {
            if (result.isConfirmed) {
                form.reset();
                currentStep = 1;
                showStep(1);
                filePreview.innerHTML = '';
                document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
                Swal.fire({
                    icon: 'success',
                    title: 'Reset!',
                    text: 'Form has been cleared.',
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 2000
                });
            }
        });
    });

    // Symptom checkbox counters
    document.querySelectorAll('.symptom-checkbox').forEach(cb => {
        cb.addEventListener('change', updateSymptomCounts);
    });

    // Initialize
    showStep(1);
});
</script>
@endsection