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
                        </h6>
                    </div>

                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form action="{{ route('appointments.store') }}" method="POST">
                        @csrf
                        <div class="card">
                            <div class="card-body">
                                <div class="form">
                                    <!-- Appointment ID -->
                                    <div class="mb-3">
                                        <label class="form-label mb-1 fw-medium">Appointment ID</label>
                                        <input type="text" class="form-control" value="{{ $nextId }}" disabled>
                                    </div>

                                    <div class="row">
                                        <!-- Patient -->
                                        <!-- Patient Dropdown -->
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label mb-1 fw-medium">Patient<span
                                                        class="text-danger ms-1">*</span></label>
                                                <select name="patient_id"
                                                    class="select @error('patient_id') is-invalid @enderror" required>
                                                    <option value="">Select Patient</option>
                                                    @foreach ($patients as $patient)
                                                        <option value="{{ $patient->id }}"
                                                            {{ (old('patient_id') ?? $selectedPatient) == $patient->id ? 'selected' : '' }}>
                                                            {{ $patient->first_name }} {{ $patient->last_name }}
                                                            ({{ $patient->patient_id }})
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('patient_id')
                                                    <span class="invalid-feedback">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <!-- Appointment Type -->
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label mb-1 fw-medium">Appointment Type<span
                                                        class="text-danger ms-1">*</span></label>
                                                <select name="appointment_type"
                                                    class="select @error('appointment_type') is-invalid @enderror" required>
                                                    <option value="">Select</option>
                                                    <option value="in_person"
                                                        {{ old('appointment_type') == 'in_person' ? 'selected' : '' }}>In
                                                        Person</option>
                                                    <option value="online"
                                                        {{ old('appointment_type') == 'online' ? 'selected' : '' }}>Online
                                                    </option>
                                                </select>
                                                @error('appointment_type')
                                                    <span class="invalid-feedback">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <!-- Date -->
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label mb-1 fw-medium">Date of Appointment<span
                                                        class="text-danger ms-1">*</span></label>
                                                <div class="input-icon-end position-relative">
                                                    <input type="date" name="appointment_date"
                                                        class="form-control @error('appointment_date') is-invalid @enderror"
                                                        value="{{ old('appointment_date') }}" required>
                                                    <span class="input-icon-addon"><i class="ti ti-calendar"></i></span>
                                                </div>
                                                @error('appointment_date')
                                                    <span class="invalid-feedback">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <!-- Time -->
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label mb-1 fw-medium">Time<span
                                                        class="text-danger ms-1">*</span></label>
                                                <div class="input-icon-end position-relative">
                                                    <input type="time" name="appointment_time"
                                                        class="form-control @error('appointment_time') is-invalid @enderror"
                                                        value="{{ old('appointment_time') }}" required>
                                                    <span class="input-icon-addon"><i
                                                            class="ti ti-clock text-gray-7"></i></span>
                                                </div>
                                                @error('appointment_time')
                                                    <span class="invalid-feedback">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Reason -->
                                    <div class="mb-3">
                                        <label class="form-label mb-1 fw-medium">Appointment Reason<span
                                                class="text-danger ms-1">*</span></label>
                                        <textarea name="reason" class="form-control @error('reason') is-invalid @enderror" rows="3" required>{{ old('reason') }}</textarea>
                                        @error('reason')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <!-- ✅ VITAL SIGNS SECTION -->
                                    <div class="card mt-4">
                                        <div class="card-header bg-light">
                                            <h6 class="fw-bold mb-0">
                                                <i class="ti ti-heartbeat me-2 text-primary"></i>Vital Signs
                                            </h6>
                                            <small class="text-muted">Record patient vitals for this visit</small>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <!-- VAT, PIT, KUFF -->
                                                <div class="col-md-4">
                                                    <div class="mb-3">
                                                        <label class="form-label small fw-medium">VAT</label>
                                                        <input type="text" name="vat"
                                                            class="form-control form-control-sm"
                                                            value="{{ old('vat') }}" placeholder="e.g., Normal">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="mb-3">
                                                        <label class="form-label small fw-medium">PIT</label>
                                                        <input type="text" name="pit"
                                                            class="form-control form-control-sm"
                                                            value="{{ old('pit') }}" placeholder="e.g., Normal">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="mb-3">
                                                        <label class="form-label small fw-medium">Kuff</label>
                                                        <input type="text" name="kuff"
                                                            class="form-control form-control-sm"
                                                            value="{{ old('kuff') }}" placeholder="e.g., Dry/Wet">
                                                    </div>
                                                </div>

                                                <!-- BP, Temp, Pulse -->

                                                <div class="col-md-4">
                                                    <div class="mb-3">
                                                        <label class="form-label small fw-medium">Blood Pressure</label>
                                                        <input type="text" name="bp" id="bpInput"
                                                            class="form-control form-control-sm @error('bp') is-invalid @enderror"
                                                            value="{{ old('bp') }}" placeholder="120/80"
                                                            maxlength="7">
                                                        <small class="text-muted">Auto-formats as 120/80</small>
                                                        @error('bp')
                                                            <span class="invalid-feedback">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <!-- Temperature -->
                                                <div class="col-md-4">
                                                    <div class="mb-3">
                                                        <label class="form-label small fw-medium">Temperature <small
                                                                class="text-muted">(°F)</small></label>
                                                        <div class="input-group input-group-sm">
                                                            <input type="text" name="temp" id="tempInput"
                                                                class="form-control @error('temp') is-invalid @enderror"
                                                                value="{{ old('temp') }}" placeholder="98.6"
                                                                maxlength="6">
                                                            <span class="input-group-text">°F</span>
                                                        </div>
                                                        <small class="text-muted">Auto-converts to Fahrenheit</small>
                                                        @error('temp')
                                                            <span class="invalid-feedback">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="mb-3">
                                                        <label class="form-label small fw-medium">Pulse</label>
                                                        <input type="text" name="pulse"
                                                            class="form-control form-control-sm"
                                                            value="{{ old('pulse') }}" placeholder="e.g., 72 bpm">
                                                    </div>
                                                </div>

                                                <!-- Weight, Tongue, Nails -->
                                                <div class="col-md-4">
                                                    <div class="mb-3">
                                                        <label class="form-label small fw-medium">Weight</label>
                                                        <input type="text" name="weight"
                                                            class="form-control form-control-sm"
                                                            value="{{ old('weight') }}" placeholder="e.g., 65 kg">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="mb-3">
                                                        <label class="form-label small fw-medium">Tongue</label>
                                                        <input type="text" name="tongue"
                                                            class="form-control form-control-sm"
                                                            value="{{ old('tongue') }}" placeholder="e.g., Coated">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="mb-3">
                                                        <label class="form-label small fw-medium">Nails</label>
                                                        <input type="text" name="nails"
                                                            class="form-control form-control-sm"
                                                            value="{{ old('nails') }}" placeholder="e.g., Normal">
                                                    </div>
                                                </div>

                                                <!-- Cerebral Fluid -->
                                                <div class="col-md-4">
                                                    <div class="mb-3">
                                                        <label class="form-label small fw-medium">Cerebral Fluid</label>
                                                        <select name="cerebral_fluid" class="select select-sm">
                                                            <option value="">Select</option>
                                                            <option value="normal"
                                                                {{ old('cerebral_fluid') == 'normal' ? 'selected' : '' }}>
                                                                Normal</option>
                                                            <option value="shrink"
                                                                {{ old('cerebral_fluid') == 'shrink' ? 'selected' : '' }}>
                                                                Shrink</option>
                                                            <option value="expand"
                                                                {{ old('cerebral_fluid') == 'expand' ? 'selected' : '' }}>
                                                                Expand</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <!-- Vital Notes -->
                                                <div class="col-md-8">
                                                    <div class="mb-3">
                                                        <label class="form-label small fw-medium">Vital Notes</label>
                                                        <textarea name="vital_notes" class="form-control form-control-sm" rows="2"
                                                            placeholder="Any observations about vitals...">{{ old('vital_notes') }}</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Status -->
                                    <div class="mb-0">
                                        <label class="form-label mb-1 fw-medium">Status<span
                                                class="text-danger ms-1">*</span></label>
                                        <select name="status" class="select @error('status') is-invalid @enderror"
                                            required>
                                            <option value="">Select</option>
                                            @foreach (['schedule' => 'Schedule', 'confirmed' => 'Confirmed', 'checked_in' => 'Checked In', 'checked_out' => 'Checked Out', 'cancelled' => 'Cancelled'] as $val => $label)
                                                <option value="{{ $val }}"
                                                    {{ old('status') == $val ? 'selected' : '' }}>{{ $label }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('status')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex align-items-center justify-content-end mt-3">
                            <a href="{{ route('appointments.index') }}" class="btn btn-light me-2">Cancel</a>
                            <button type="submit" class="btn btn-primary">Create Appointment</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

  <script>
document.addEventListener('DOMContentLoaded', function() {
    
    // ✅ BP Auto-Format (120/80)
    const bpInput = document.getElementById('bpInput');
    if (bpInput) {
        bpInput.addEventListener('input', function(e) {
            let val = e.target.value.replace(/\D/g, '');
            if (val.length >= 3 && !val.includes('/')) {
                val = val.slice(0, 3) + '/' + val.slice(3, 5);
            }
            if (val.includes('/')) {
                const [sys, dia] = val.split('/');
                val = sys.slice(0, 3) + '/' + (dia ? dia.slice(0, 2) : '');
            }
            e.target.value = val;
        });
        bpInput.addEventListener('blur', function(e) {
            const val = e.target.value;
            if (val && !/^\d{2,3}\/\d{2}$/.test(val)) {
                e.target.style.borderColor = '#dc3545';
                setTimeout(() => e.target.style.borderColor = '', 2000);
            }
        });
    }

    // ✅ Temperature: Smart Celsius/Fahrenheit Handling
    const tempInput = document.getElementById('tempInput');
    if (tempInput) {
        tempInput.addEventListener('input', function(e) {
            let val = e.target.value.toLowerCase().replace(/[^0-9.c]/g, '');
            const parts = val.split('.');
            if (parts.length > 2) {
                val = parts[0] + '.' + parts.slice(1).join('').replace('c', '');
            }
            const hasC = val.endsWith('c');
            const numPart = hasC ? val.slice(0, -1) : val;
            if (numPart.includes('.') && numPart.split('.')[1]?.length > 1) {
                const [int, dec] = numPart.split('.');
                val = int + '.' + dec.substring(0, 1) + (hasC ? 'c' : '');
            }
            e.target.value = val;
        });

        tempInput.addEventListener('blur', function(e) {
            const raw = e.target.value.trim().toLowerCase();
            if (!raw) return;
            
            const isExplicitCelsius = raw.endsWith('c');
            let num = parseFloat(raw);
            if (isNaN(num)) return;
            
            if (isExplicitCelsius || num < 60) {
                const fahrenheit = (num * 9/5) + 32;
                e.target.value = fahrenheit.toFixed(1);
                e.target.style.borderColor = '#28a745';
                e.target.style.boxShadow = '0 0 0 0.2rem rgba(40, 167, 69, 0.25)';
                setTimeout(() => {
                    e.target.style.borderColor = '';
                    e.target.style.boxShadow = '';
                }, 2000);
                console.log(`✓ Converted: ${num}°C → ${fahrenheit.toFixed(1)}°F`);
            }
            else if (num < 90 || num > 115) {
                e.target.style.borderColor = '#ffc107';
                setTimeout(() => e.target.style.borderColor = '', 2000);
            }
        });
    }
});
</script>
@endsection
