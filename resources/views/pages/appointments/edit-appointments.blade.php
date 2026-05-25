@extends('layout.master')

@section('content')
<div class="page-wrapper">
    <div class="content">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                
                <!-- Page Header -->
                <div class="mb-4">
                    <h6 class="fw-bold mb-0 d-flex align-items-center">
                        <a href="{{ route('appointments.index') }}" class="text-dark">
                            <i class="ti ti-chevron-left me-1"></i>Appointments
                        </a>
                        <span class="mx-2">/</span>
                        <span class="text-primary">Edit Appointment</span>
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

                <!-- Edit Form -->
                <form action="{{ route('appointments.update', $appointment->id) }}" method="POST" id="appointmentForm">
                    @csrf
                    @method('PUT')
                    
                    <div class="card">
                        <div class="card-body">
                            <div class="form">
                                
                                <!-- Appointment ID -->
                                <div class="mb-3">
                                    <label class="form-label mb-1 fw-medium">Appointment ID</label>
                                    <input type="text" class="form-control" value="{{ $appointment->appointment_id }}" disabled>
                                </div>

                                <!-- Row 1: Patient & Appointment Type -->
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label mb-1 fw-medium">Patient<span class="text-danger ms-1">*</span></label>
                                            <select name="patient_id" class="select @error('patient_id') is-invalid @enderror" required>
                                                <option value="">Select Patient</option>
                                                @foreach($patients as $patient)
                                                    <option value="{{ $patient->id }}" {{ old('patient_id', $appointment->patient_id) == $patient->id ? 'selected' : '' }}>
                                                        {{ $patient->first_name }} {{ $patient->last_name }} ({{ $patient->patient_id }})
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('patient_id')
                                                <span class="invalid-feedback d-block">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label mb-1 fw-medium">Appointment Type<span class="text-danger ms-1">*</span></label>
                                            <select name="appointment_type" class="select @error('appointment_type') is-invalid @enderror" required>
                                                <option value="">Select</option>
                                                <option value="in_person" {{ old('appointment_type', $appointment->appointment_type) == 'in_person' ? 'selected' : '' }}>In Person</option>
                                                <option value="online" {{ old('appointment_type', $appointment->appointment_type) == 'online' ? 'selected' : '' }}>Online</option>
                                            </select>
                                            @error('appointment_type')
                                                <span class="invalid-feedback d-block">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Row 2: Date & Time -->
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label mb-1 fw-medium">Date of Appointment<span class="text-danger ms-1">*</span></label>
                                            <div class="input-icon-end position-relative">  
                                                <input type="date" name="appointment_date" class="form-control @error('appointment_date') is-invalid @enderror" value="{{ old('appointment_date', $appointment->appointment_date?->format('Y-m-d')) }}" required>
                                                <span class="input-icon-addon"><i class="ti ti-calendar"></i></span>
                                            </div>
                                            @error('appointment_date')
                                                <span class="invalid-feedback d-block">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label mb-1 fw-medium">Time<span class="text-danger ms-1">*</span></label>
                                            <div class="input-icon-end position-relative">
                                                <input type="time" name="appointment_time" class="form-control @error('appointment_time') is-invalid @enderror" value="{{ old('appointment_time', $appointment->appointment_time?->format('H:i')) }}" required>
                                                <span class="input-icon-addon"><i class="ti ti-clock text-gray-7"></i></span>
                                            </div>
                                            @error('appointment_time')
                                                <span class="invalid-feedback d-block">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Reason -->
                                <div class="mb-3">
                                    <label class="form-label mb-1 fw-medium">Appointment Reason<span class="text-danger ms-1">*</span></label>
                                    <textarea name="reason" class="form-control @error('reason') is-invalid @enderror" rows="3" required>{{ old('reason', $appointment->reason) }}</textarea>
                                    @error('reason')
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- ✅ VITAL SIGNS SECTION -->
                                <div class="card mt-4 border-primary">
                                    <div class="card-header bg-primary text-white py-2">
                                        <h6 class="fw-bold mb-0">
                                            <i class="ti ti-heartbeat me-2"></i>Vital Signs (This Visit)
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <!-- VAT, PIT, KUFF -->
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label class="form-label small fw-medium">VAT</label>
                                                    <input type="text" name="vat" class="form-control form-control-sm" 
                                                           value="{{ old('vat', $appointment->vat) }}" placeholder="e.g., Normal">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label class="form-label small fw-medium">PIT</label>
                                                    <input type="text" name="pit" class="form-control form-control-sm" 
                                                           value="{{ old('pit', $appointment->pit) }}" placeholder="e.g., Normal">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label class="form-label small fw-medium">Kuff</label>
                                                    <input type="text" name="kuff" class="form-control form-control-sm" 
                                                           value="{{ old('kuff', $appointment->kuff) }}" placeholder="e.g., Dry/Wet">
                                                </div>
                                            </div>

                                            <!-- BP (Auto-Format) -->
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label class="form-label small fw-medium">Blood Pressure</label>
                                                    <input type="text" name="bp" id="bpInput" 
                                                           class="form-control form-control-sm @error('bp') is-invalid @enderror" 
                                                           value="{{ old('bp', $appointment->bp) }}" placeholder="120/80" maxlength="7">
                                                    <small class="text-muted">Auto-formats as 120/80</small>
                                                    @error('bp')
                                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>

                                            <!-- Temperature (Smart °F/°C) -->
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label class="form-label small fw-medium">Temperature</label>
                                                    <div class="input-group input-group-sm">
                                                        <input type="text" name="temp" id="tempInput" 
                                                               class="form-control @error('temp') is-invalid @enderror" 
                                                               value="{{ old('temp', $appointment->temp) }}" placeholder="98.6" maxlength="6">
                                                        <span class="input-group-text">°F</span>
                                                    </div>
                                                    <small class="text-muted">Auto-converts Celsius → Fahrenheit</small>
                                                    @error('temp')
                                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>

                                            <!-- Pulse -->
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label class="form-label small fw-medium">Pulse</label>
                                                    <input type="text" name="pulse" class="form-control form-control-sm" 
                                                           value="{{ old('pulse', $appointment->pulse) }}" placeholder="e.g., 72 bpm">
                                                </div>
                                            </div>

                                            <!-- Weight, Tongue, Nails -->
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label class="form-label small fw-medium">Weight</label>
                                                    <input type="text" name="weight" class="form-control form-control-sm" 
                                                           value="{{ old('weight', $appointment->weight) }}" placeholder="e.g., 65 kg">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label class="form-label small fw-medium">Tongue</label>
                                                    <input type="text" name="tongue" class="form-control form-control-sm" 
                                                           value="{{ old('tongue', $appointment->tongue) }}" placeholder="e.g., Coated">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label class="form-label small fw-medium">Nails</label>
                                                    <input type="text" name="nails" class="form-control form-control-sm" 
                                                           value="{{ old('nails', $appointment->nails) }}" placeholder="e.g., Normal">
                                                </div>
                                            </div>

                                            <!-- Cerebral Fluid -->
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label class="form-label small fw-medium">Cerebral Fluid</label>
                                                    <select name="cerebral_fluid" class="select select-sm">
                                                        <option value="">Select</option>
                                                        <option value="normal" {{ old('cerebral_fluid', $appointment->cerebral_fluid) == 'normal' ? 'selected' : '' }}>Normal</option>
                                                        <option value="shrink" {{ old('cerebral_fluid', $appointment->cerebral_fluid) == 'shrink' ? 'selected' : '' }}>Shrink</option>
                                                        <option value="expand" {{ old('cerebral_fluid', $appointment->cerebral_fluid) == 'expand' ? 'selected' : '' }}>Expand</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <!-- Vital Notes -->
                                            <div class="col-md-8">
                                                <div class="mb-3">
                                                    <label class="form-label small fw-medium">Vital Notes</label>
                                                    <textarea name="vital_notes" class="form-control form-control-sm" rows="2" 
                                                              placeholder="Any observations about vitals...">{{ old('vital_notes', $appointment->vital_notes) }}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Status -->
                                <div class="mb-0">
                                    <label class="form-label mb-1 fw-medium">Status<span class="text-danger ms-1">*</span></label>
                                    <select name="status" class="select @error('status') is-invalid @enderror" required>
                                        <option value="">Select</option>
                                        @foreach(['schedule' => 'Schedule', 'confirmed' => 'Confirmed', 'checked_in' => 'Checked In', 'checked_out' => 'Checked Out', 'cancelled' => 'Cancelled'] as $val => $label)
                                            <option value="{{ $val }}" {{ old('status', $appointment->status) == $val ? 'selected' : '' }}>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                    @error('status')
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                    @enderror
                                </div>

                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="d-flex align-items-center justify-content-end mt-3">
                        <a href="{{ route('appointments.index') }}" class="btn btn-light me-2">Cancel</a>
                        <button type="submit" class="btn btn-primary">Update Appointment</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>

<!-- ✅ Smart Input Scripts -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    
    // ✅ BP Auto-Format (120/80)
    const bpInput = document.getElementById('bpInput');
    if (bpInput) {
        bpInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, ''); // Remove non-digits

            // Auto-add slash after 3 digits
            if (value.length >= 3 && !value.includes('/')) {
                value = value.substring(0, 3) + '/' + value.substring(3, 5);
            }

            // Limit: 3 digits systolic, 2 digits diastolic
            if (value.includes('/')) {
                const parts = value.split('/');
                if (parts[0].length > 3) parts[0] = parts[0].substring(0, 3);
                if (parts[1] && parts[1].length > 2) parts[1] = parts[1].substring(0, 2);
                value = parts[0] + '/' + (parts[1] || '');
            }

            e.target.value = value;
        });

        // Validate BP format on blur
        bpInput.addEventListener('blur', function(e) {
            const value = e.target.value;
            if (value && !/^\d{2,3}\/\d{2}$/.test(value)) {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'warning',
                    title: 'Invalid BP',
                    text: 'Use format: 120/80',
                    showConfirmButton: false,
                    timer: 3000
                });
            }
        });
    }

    // ✅ Temperature: Smart °F/°C Handling
    const tempInput = document.getElementById('tempInput');
    if (tempInput) {
        let isConverting = false;

        tempInput.addEventListener('input', function(e) {
            if (isConverting) return;
            
            // Allow only digits and one decimal
            let value = e.target.value.replace(/[^0-9.]/g, '');
            const parts = value.split('.');
            
            if (parts.length > 2) {
                value = parts[0] + '.' + parts.slice(1).join('');
            }
            if (parts.length === 2 && parts[1].length > 1) {
                value = parts[0] + '.' + parts[1].substring(0, 1);
            }
            
            e.target.value = value;
        });

        // Smart validation & conversion on blur
        tempInput.addEventListener('blur', function(e) {
            const value = parseFloat(e.target.value);
            if (!e.target.value || isNaN(value)) return;

            // 🌡️ If < 50, assume Celsius → convert to Fahrenheit
            if (value < 50) {
                isConverting = true;
                const fahrenheit = (value * 9/5) + 32;
                const rounded = fahrenheit.toFixed(1);
                
                e.target.value = rounded;
                isConverting = false;
                
                // Subtle toast notification
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'info',
                    title: `Converted: ${value}°C → ${rounded}°F`,
                    showConfirmButton: false,
                    timer: 2500,
                    timerProgressBar: true
                });
            }
            // Valid Fahrenheit range
            else if (value >= 95 && value <= 108) {
                // All good
            }
            // Out of valid range
            else {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'warning',
                    title: 'Check Temperature',
                    html: `<small>Valid: 35-42°C or 95-108°F</small>`,
                    showConfirmButton: false,
                    timer: 3500
                });
            }
        });
    }
});
</script>
@endsection