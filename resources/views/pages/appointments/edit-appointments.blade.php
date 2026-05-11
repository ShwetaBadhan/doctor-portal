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
                <form action="{{ route('appointments.update', $appointment->id) }}" method="POST">
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
                                            <div class="d-flex align-items-center justify-content-between mb-1">
                                             <label class="form-label mb-0 fw-medium">Patient<span class="text-danger ms-1">*</span></label>
                                               
                                            </div>
                                            <select name="patient_id" class="select @error('patient_id') is-invalid @enderror" required>
                                                <option value="">Select Patient</option>
                                                @foreach($patients as $patient)
                                                    <option value="{{ $patient->id }}" {{ old('patient_id', $appointment->patient_id) == $patient->id ? 'selected' : '' }}>
                                                        {{ $patient->first_name }} {{ $patient->last_name }}
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
                                                <input type="date" name="appointment_date" class="form-control  @error('appointment_date') is-invalid @enderror" value="{{ old('appointment_date', $appointment->appointment_date?->format('Y-m-d')) }}" required>
                                                <span class="input-icon-addon">
                                                    <i class="ti ti-calendar"></i>
                                                </span>
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
                                                <input type="time" name="appointment_time" class="form-control  @error('appointment_time') is-invalid @enderror" value="{{ old('appointment_time', $appointment->appointment_time?->format('H:i')) }}" required>
                                                <span class="input-icon-addon">
                                                    <i class="ti ti-clock text-gray-7"></i>
                                                </span>
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

 
@endsection