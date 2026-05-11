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
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label mb-1 fw-medium">Patient<span
                                                        class="text-danger ms-1">*</span></label>
                                                <select name="patient_id"
                                                    class="select @error('patient_id') is-invalid @enderror" required>
                                                    <option value="">Select Patient</option>
                                                    @foreach ($patients as $patient)
                                                        <option value="{{ $patient->id }}"
                                                            {{ old('patient_id') == $patient->id ? 'selected' : '' }}>
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

    <!-- Keep your Add Patient Modal here if needed -->
@endsection
