@extends('layout.master')
@section('content')
    <!-- ========================
                                               Start Page Content
                                              ========================= -->

    <div class="page-wrapper">

        <!-- Start Content -->
        <div class="content">

            <!-- Start Page Header -->
            <div class="d-flex align-items-sm-center flex-sm-row flex-column gap-2 pb-3 mb-3 border-1 border-bottom">
                <div class="flex-grow-1">
                    <h4 class="fw-bold mb-0">Patients List <span
                            class="badge badge-soft-primary fw-medium border py-1 px-2 border-primary fs-13 ms-1">Total
                            Patients : {{ count($patients) }}</span></h4>
                </div>
                <div class="text-end d-flex">
                    <!-- dropdown-->
                    {{-- <div class="dropdown me-1">
                        <a href="javascript:void(0);"
                            class="btn btn-md fs-14 fw-normal border bg-white rounded text-dark d-inline-flex align-items-center"
                            data-bs-toggle="dropdown">
                            Export<i class="ti ti-chevron-down ms-2"></i>
                        </a>
                        <ul class="dropdown-menu p-2">
                            <li>
                                <a class="dropdown-item" href="#">Download as PDF</a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="#">Download as Excel</a>
                            </li>
                        </ul>
                    </div> --}}
                    <div
                        class="bg-white border shadow-sm rounded px-1 pb-0 text-center d-flex align-items-center justify-content-center">

                        <a href="{{ route('patients.index') }}"
                            class="bg-light rounded p-1 d-flex align-items-center justify-content-center"> <i
                                class="ti ti-list fs-14 text-body"></i></a>
                        <a href="{{ route('patients.index') }}"
                            class="bg-white rounded p-1 d-flex align-items-center justify-content-center"> <i
                                class="ti ti-layout-grid fs-14 text-body"></i> </a>
                    </div>
                    @can('create-patients')
                        <a href="{{ route('patients.create') }}" class="btn btn-primary ms-2 fs-13 btn-md"><i
                                class="ti ti-plus me-1"></i>New Patient</a>
                    @endcan

                </div>
            </div>
            <!-- End Page Header -->

            <!--  Start Filter -->
            <div class=" d-flex align-items-center justify-content-between flex-wrap">
                <div>
                    <div class="search-set mb-3">
                        <div class="d-flex align-items-center flex-wrap gap-2">
                            <div class="table-search d-flex align-items-center mb-0">
                                <div class="search-input">
                                    <a href="javascript:void(0);" class="btn-searchset"></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


            </div>
            <!--  End Filter -->
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
            <!--  Start Table -->
            <div class="table-responsive">
                <table class="table datatable table-nowrap">
                    <thead class="">
                        <tr>
                            <th>Patient</th>
                            <th>Patient ID</th>
                            <th>Phone</th>
                            <th>Doctor</th>
                            <th>Address</th>
                            <th>Last Visit</th>
                            <th>Status</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($patients as $patient)
                            <tr>
                                <!-- Patient Name & Image -->
                                <td>
                                    <div class="d-flex align-items-center">
                                        <a href="{{ route('patients.show', $patient->id) }}" class="avatar avatar-md me-2">
                                            @if ($patient->profile_image)
                                                <img src="{{ Storage::url($patient->profile_image) }}"
                                                    alt="{{ $patient->first_name }}" class="rounded-circle">
                                            @else
                                                <span
                                                    class="avatar-text bg-light text-muted rounded-circle d-flex align-items-center justify-content-center w-100 h-100">
                                                    {{ substr($patient->first_name, 0, 1) }}
                                                </span>
                                            @endif
                                        </a>
                                        <a href="{{ route('patients.show', $patient->id) }}" class="text-dark fw-semibold">
                                            {{ $patient->first_name }} {{ $patient->last_name }}
                                            <span class="text-body fs-13 fw-normal d-block">
                                                {{ $patient->age }}, {{ ucfirst($patient->gender) }}
                                            </span>
                                        </a>
                                    </div>
                                </td>

                                <!-- ID -->
                                <td>{{ $patient->patient_id }}</td>
                                <!-- Phone -->
                                <td>{{ $patient->phone }}</td>

                                <!-- Doctor -->
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar me-2 flex-shrink-0 bg-light text-muted rounded-circle d-flex align-items-center justify-content-center"
                                            style="width: 32px; height: 32px;">
                                            <i class="ti ti-user fs-14"></i>
                                        </div>
                                        <div>
                                            <h6 class="fs-14 mb-1">
                                                <a href="javascript:void(0);" class="fw-semibold text-dark">
                                                    {{ $patient->primary_doctor ?? 'N/A' }}
                                                </a>
                                            </h6>
                                            <p class="mb-0 fs-13 text-muted">Doctor</p>
                                        </div>
                                    </div>
                                </td>

                                <!-- Address -->
                                <td>{{ $patient->city }}, {{ $patient->state }}</td>

                                <!-- Last Visit (using created_at as fallback) -->
                                <td>{{ $patient->updated_at ? $patient->updated_at->format('d M Y') : $patient->created_at->format('d M Y') }}
                                </td>

                                <!-- Status Badge -->
                                <td>
                                    @if ($patient->status == 'available')
                                        <span
                                            class="badge badge-soft-success rounded text-success border border-success fs-13 fw-medium">Available</span>
                                    @else
                                        <span
                                            class="badge badge-soft-danger rounded text-danger border border-danger fs-13 fw-medium">Unavailable</span>
                                    @endif
                                </td>

                                <!-- Actions -->
                                <!-- Actions -->
                                <td class="action-item">
                                    <div class="d-flex align-items-center gap-1">
                                        @can('view-patient-appointment')
                                            <!-- Appointment Button -->
                                            <a href="{{ route('appointments.create', ['patient_id' => $patient->id]) }}"
                                                class="btn btn-primary">
                                                <i class="ti ti-calendar-event me-1"></i>
                                            </a>
                                        @endcan

                                        <a href="javascript:void(0);"
                                            class="shadow-sm fs-14 d-inline-flex border rounded-2 p-1"
                                            data-bs-toggle="dropdown" title="More">
                                            <i class="ti ti-dots-vertical"></i>
                                        </a>

                                        <ul class="dropdown-menu p-2">
                                            <li>
                                                @can('edit-patients')
                                                    <a href="{{ route('patients.edit', $patient->id) }}"
                                                        class="dropdown-item d-flex align-items-center">
                                                        <i class="ti ti-edit me-2 fs-14"></i> Edit
                                                    </a>
                                                @endcan
                                            </li>
                                            <li>
                                                @can('view-patient-details')
                                                    <a href="{{ route('patients.show', $patient->id) }}"
                                                        class="dropdown-item d-flex align-items-center">
                                                        <i class="ti ti-eye me-2 fs-14"></i> View
                                                    </a>
                                                @endcan
                                            </li>


                                            <li>
                                                @can('download-patient-report')
                                                    <a href="{{ route('welcome-letter.download', $patient->id) }}"
                                                        class="dropdown-item d-flex align-items-center">
                                                        <i class="ti ti-file-download me-2 fs-14"></i> Download Welcome Letter
                                                    </a>
                                                @endcan
                                            </li>
                                            <li>
                                                @can('send-patient-welcome-email')
                                                    <!-- Added unique ID & display:contents to prevent dropdown layout breaks -->
                                                    <form id="welcomeEmailForm_{{ $patient->id }}"
                                                        action="{{ route('send-welcome-email', $patient->id) }}" method="POST"
                                                        style="display: contents;">
                                                        @csrf
                                                        <!-- Changed type to button & removed onsubmit -->
                                                        <button type="button"
                                                            class="dropdown-item d-flex align-items-center w-100 border-0 bg-transparent"
                                                            onclick="confirmSendEmail('{{ $patient->email }}', 'welcomeEmailForm_{{ $patient->id }}')"
                                                            {{ empty($patient->email) ? 'disabled' : '' }}>
                                                            <i class="ti ti-send me-2 fs-14"></i> Email Welcome Letter
                                                        </button>
                                                    </form>
                                                @endcan
                                            </li>
                                            <li>
                                                <a href="{{ route('diagnosis-report.preview', $patient->id) }}"
                                                    class="dropdown-item d-flex align-items-center" target="_blank">
                                                    <i class="ti ti-report-medical me-2 fs-14"></i> Diagnosis Report
                                                </a>
                                            </li>
                                            <li>
                                                @can('assign-medicines-to-patients')
                                                    <a href="javascript:void(0);"
                                                        class="dropdown-item d-flex align-items-center" data-bs-toggle="modal"
                                                        data-bs-target="#assignModal{{ $patient->id }}">
                                                        <i class="ti ti-pills me-2 fs-14"></i> Assign Medicines
                                                    </a>
                                                @endcan
                                            </li>
                                            <li>
                                                @can('delete-patients')
                                                    <a href="javascript:void(0);"
                                                        class="dropdown-item d-flex align-items-center text-danger"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#delete_modal{{ $patient->id }}">
                                                        <i class="ti ti-trash me-2 fs-14"></i> Delete
                                                    </a>
                                                @endcan
                                            </li>
                                        </ul>
                                    </div>
                                </td>

                            </tr>

                            <!-- Start Delete Modal -->
                            <div class="modal fade" id="delete_modal{{ $patient->id }}" tabindex="-1">
                                <div class="modal-dialog modal-dialog-centered modal-sm">
                                    <div class="modal-content">
                                        <div class="modal-body text-center position-relative">
                                            <img src="{{ asset('assets/img/bg/delete-modal-bg-01.png') }}" alt=""
                                                class="img-fluid position-absolute top-0 start-0 z-0">
                                            <img src="{{ asset('assets/img/bg/delete-modal-bg-02.png') }}" alt=""
                                                class="img-fluid position-absolute bottom-0 end-0 z-0">

                                            <div class="mb-3 position-relative z-1">
                                                <span class="avatar avatar-lg bg-danger text-white">
                                                    <i class="ti ti-trash fs-24"></i>
                                                </span>
                                            </div>

                                            <h5 class="fw-bold mb-1 position-relative z-1">Delete Confirmation</h5>
                                            <p class="mb-3 position-relative z-1">
                                                Are you sure you want to delete <strong id="delete-patient-name">this
                                                    patient</strong>?
                                            </p>

                                            <div class="d-flex justify-content-center">
                                                <a href="javascript:void(0);"
                                                    class="btn btn-light position-relative z-1 me-3"
                                                    data-bs-dismiss="modal">Cancel</a>

                                                <!-- ✅ PURE LARAVEL FORM - No JavaScript needed for logic -->
                                                <form action="{{ route('patients.destroy', $patient->id) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="btn btn-danger position-relative z-1">Yes,
                                                        Delete</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- End Delete Modal -->
                        @endforeach
                    </tbody>
                </table>
            </div>
            <!--  End Table -->

        </div>
        <!-- End Content -->

        @include('components.copyright')

    </div>

    <!-- ========================
                                               End Page Content
                                              ========================= -->

    <!-- Start Delete Modal  -->
    <div class="modal fade" id="delete_modal">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content">
                <div class="modal-body text-center position-relative">
                    <img src="assets/img/bg/delete-modal-bg-01.png" alt=""
                        class="img-fluid position-absolute top-0 start-0 z-0">
                    <img src="assets/img/bg/delete-modal-bg-02.png" alt=""
                        class="img-fluid position-absolute bottom-0 end-0 z-0">
                    <div class="mb-3 position-relative z-1">
                        <span class="avatar avatar-lg bg-danger text-white"><i class="ti ti-trash fs-24"></i></span>
                    </div>
                    <h5 class="fw-bold mb-1 position-relative z-1">Delete Confirmation</h5>
                    <p class="mb-3 position-relative z-1">Are you sure want to delete?</p>
                    <div class="d-flex justify-content-center">
                        <a href="javascript:void(0);" class="btn btn-light position-relative z-1 me-3"
                            data-bs-dismiss="modal">Cancel</a>
                        <a href="" class="btn btn-danger position-relative z-1" data-bs-dismiss="modal">Yes,
                            Delete</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Delete Modal  -->
    <!-- Assign Medicine Modals (One per patient) -->
    @foreach ($patients as $patient)
        <div class="modal fade" id="assignModal{{ $patient->id }}" tabindex="-1">
            <div class="modal-dialog modal-xl modal-dialog-scrollable">
                <div class="modal-content">
                    <form action="{{ route('patients.medicines.assign-custom', $patient->id) }}" method="POST">
                        @csrf

                        <div class="modal-header sticky-top bg-white z-3">
                            <h5 class="modal-title">
                                <i class="ti ti-pills me-2"></i>Assign Medicines
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>

                        <div class="modal-body">
                            <p class="text-muted mb-3">
                                Select medicines for <strong>{{ $patient->first_name }} {{ $patient->last_name }}</strong>
                            </p>

                            <!-- Medicine Group Selector -->
                            <div class="mb-3">
                                <label class="form-label fw-medium">Medicine Group <span
                                        class="text-danger">*</span></label>
                                <select name="medicine_group_id" class="form-select group-selector"
                                    data-patient-id="{{ $patient->id }}" required>
                                    <option value="">Select group...</option>
                                    @foreach ($medicineGroups as $group)
                                        <option value="{{ $group->id }}">
                                            {{ $group->name }}
                                            @if ($group->code)
                                                ({{ $group->code }})
                                            @endif
                                            - {{ $group->medicines_count }} medicines
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- ✅ Pre-rendered Medicine Groups (Hidden by default) -->
                            @foreach ($medicineGroups as $group)
                                <div class="medicine-group-content d-none" data-group-id="{{ $group->id }}"
                                    data-patient-id="{{ $patient->id }}">

                                    <!-- Check All -->
                                    <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
                                        <div class="form-check">
                                            <input class="form-check-input check-all" type="checkbox"
                                                id="checkAll_{{ $patient->id }}_{{ $group->id }}">
                                            <label class="form-check-label fw-medium"
                                                for="checkAll_{{ $patient->id }}_{{ $group->id }}">
                                                Select All
                                            </label>
                                        </div>
                                    </div>

                                    <!-- Medicines List -->
                                    @foreach ($group->medicines as $index => $medicine)
                                        @php
                                            $patientMed = $patientMedicinesMap[$patient->id][$medicine->id] ?? null;
                                            $isAssigned = $patientMed !== null;
                                        @endphp

                                        <div class="medicine-item card border-0 shadow-sm mb-2">
                                            <div class="card-body py-2 px-3">
                                                <div class="d-flex align-items-start gap-2">
                                                    <div class="pt-1">
                                                        <input class="form-check-input medicine-checkbox" type="checkbox"
                                                            name="medicines[{{ $index }}][assign]" value="1"
                                                            {{ $isAssigned ? 'checked' : '' }}>
                                                        <input type="hidden"
                                                            name="medicines[{{ $index }}][medicine_id]"
                                                            value="{{ $medicine->id }}">
                                                        @if ($isAssigned)
                                                            <input type="hidden"
                                                                name="medicines[{{ $index }}][patient_medicine_id]"
                                                                value="{{ $patientMed->id }}">
                                                        @endif
                                                    </div>

                                                    <div class="flex-grow-1">
                                                        <input type="text"
                                                            name="medicines[{{ $index }}][custom_name]"
                                                            class="form-control form-control-sm mb-1"
                                                            value="{{ $patientMed->custom_name ?? $medicine->name }}"
                                                            required>
                                                        @if ($isAssigned)
                                                            <span class="badge badge-soft-success fs-10">Already
                                                                Assigned</span>
                                                        @endif
                                                    </div>

                                                    <div class="medicine-fields" style="min-width: 200px;">
                                                        <div class="row g-2">
                                                            <div class="col-6">
                                                                <input type="text"
                                                                    name="medicines[{{ $index }}][dosage]"
                                                                    class="form-control form-control-sm"
                                                                    placeholder="Dosage"
                                                                    value="{{ $patientMed->dosage ?? $medicine->dosage }}"
                                                                    {{ !$isAssigned ? 'disabled' : '' }}>
                                                            </div>
                                                            <div class="col-6">
                                                                <input type="text"
                                                                    name="medicines[{{ $index }}][quantity]"
                                                                    class="form-control form-control-sm" placeholder="Qty"
                                                                    value="{{ $patientMed->quantity ?? $medicine->quantity }}"
                                                                    {{ !$isAssigned ? 'disabled' : '' }}>
                                                            </div>
                                                        </div>

                                                        @if ($isAssigned)
                                                            <div class="form-check mt-2">
                                                                <input class="form-check-input" type="checkbox"
                                                                    name="medicines[{{ $index }}][assign_as_new]"
                                                                    value="1"
                                                                    id="assignNew_{{ $patient->id }}_{{ $group->id }}_{{ $index }}">
                                                                <label class="form-check-label small text-primary"
                                                                    for="assignNew_{{ $patient->id }}_{{ $group->id }}_{{ $index }}">
                                                                    <i class="ti ti-refresh me-1"></i>Assign as new
                                                                </label>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endforeach

                            <!-- Extra Medicines Section -->
                            <div class="mt-4 pt-3 border-top">
                                <h6 class="fw-bold mb-3">
                                    <i class="ti ti-plus me-1 text-primary"></i>Extra Medicines
                                </h6>
                                <div id="extraMedicines_{{ $patient->id }}"></div>
                                <button type="button" class="btn btn-sm btn-outline-primary mt-2 add-extra-med"
                                    data-patient-id="{{ $patient->id }}">
                                    <i class="ti ti-plus me-1"></i> Add Extra Medicine
                                </button>
                            </div>

                            <!-- Date & Notes -->
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
                                <label class="form-label">Notes</label>
                                <textarea name="notes" class="form-control" rows="2"></textarea>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">
                                <i class="ti ti-check me-1"></i> Assign Medicines
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach
    <style>
        /* Modal Scrolling Fixes */
        .modal-dialog-scrollable .modal-content {
            max-height: calc(100vh - 3.5rem);
            display: flex;
            flex-direction: column;
        }

        .modal-dialog-scrollable .modal-body {
            overflow-y: auto;
            flex: 1 1 auto;
        }

        /* Custom scrollbar for medicines container */
        #medicinesContainer_{{ $patient->id }}::-webkit-scrollbar {
            width: 8px;
        }

        #medicinesContainer_{{ $patient->id }}::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 4px;
        }

        #medicinesContainer_{{ $patient->id }}::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 4px;
        }

        #medicinesContainer_{{ $patient->id }}::-webkit-scrollbar-thumb:hover {
            background: #555;
        }

        /* Sticky elements */
        .sticky-top {
            position: sticky;
            top: 0;
            z-index: 10;
        }

        .sticky-bottom {
            position: sticky;
            bottom: 0;
            z-index: 10;
        }

        /* Medicine item styling */
        .medicine-item {
            transition: all 0.2s ease;
        }

        .medicine-item:hover {
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1) !important;
        }

        /* Disable opacity for disabled inputs */
        .medicine-fields input:disabled {
            background-color: #f8f9fa;
            opacity: 0.6;
        }

        /* Better spacing */
        .medicine-item .card-body {
            padding: 0.75rem 1rem;
        }
    </style>
@endsection
@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // ✅ Show/hide medicine groups based on selection
            document.querySelectorAll('.group-selector').forEach(select => {
                select.addEventListener('change', function() {
                    const patientId = this.dataset.patientId;
                    const modal = this.closest('.modal');

                    // Hide all groups
                    modal.querySelectorAll('.medicine-group-content').forEach(el => {
                        el.classList.add('d-none');
                    });

                    // Show selected group
                    if (this.value) {
                        const groupContent = modal.querySelector(
                            `[data-group-id="${this.value}"][data-patient-id="${patientId}"]`);
                        if (groupContent) {
                            groupContent.classList.remove('d-none');
                        }
                    }
                });
            });

            // ✅ Check all functionality
            document.addEventListener('change', function(e) {
                if (e.target.classList.contains('check-all')) {
                    const groupContent = e.target.closest('.medicine-group-content');
                    const isChecked = e.target.checked;

                    groupContent.querySelectorAll('.medicine-checkbox').forEach(cb => {
                        cb.checked = isChecked;
                        const fields = cb.closest('.medicine-item').querySelector(
                            '.medicine-fields');
                        fields.querySelectorAll('input[type="text"]').forEach(input => {
                            if (!input.name.includes('[custom_name]')) {
                                input.disabled = !isChecked;
                            }
                        });
                    });
                }

                // Enable/disable fields when checkbox changes
                if (e.target.classList.contains('medicine-checkbox')) {
                    const fields = e.target.closest('.medicine-item').querySelector('.medicine-fields');
                    fields.querySelectorAll('input[type="text"]').forEach(input => {
                        if (!input.name.includes('[custom_name]')) {
                            input.disabled = !e.target.checked;
                        }
                    });
                }
            });

            // ✅ Add extra medicine (minimal JS)
            let extraCounter = {};
            document.querySelectorAll('.add-extra-med').forEach(btn => {
                btn.addEventListener('click', function() {
                    const patientId = this.dataset.patientId;
                    if (!extraCounter[patientId]) extraCounter[patientId] = 0;
                    extraCounter[patientId]++;

                    const container = document.getElementById(`extraMedicines_${patientId}`);
                    const index = extraCounter[patientId];

                    container.insertAdjacentHTML('beforeend', `
                <div class="extra-med-item card border-0 bg-light mb-2">
                    <div class="card-body py-2 px-3">
                        <div class="row g-2">
                            <div class="col-md-5">
                                <select name="extra_medicines[${index}][medicine_id]" class="form-select form-select-sm">
                                    <option value="">Select medicine</option>
                                    @foreach ($allMedicines as $med)
                                        <option value="{{ $med->id }}" 
                                                data-name="{{ $med->name }}"
                                                data-dosage="{{ $med->dosage }}"
                                                data-quantity="{{ $med->quantity }}">
                                            {{ $med->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <input type="text" name="extra_medicines[${index}][custom_name]" 
                                       class="form-control form-control-sm" placeholder="Medicine name" required>
                            </div>
                            <div class="col-md-1">
                                <input type="text" name="extra_medicines[${index}][dosage]" 
                                       class="form-control form-control-sm" placeholder="Dosage">
                            </div>
                            <div class="col-md-1">
                                <input type="text" name="extra_medicines[${index}][quantity]" 
                                       class="form-control form-control-sm" placeholder="Qty">
                            </div>
                            <div class="col-md-1">
                                <button type="button" class="btn btn-sm btn-light text-danger remove-extra">
                                    <i class="ti ti-x"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            `);
                });
            });

            // ✅ Remove extra medicine
            document.addEventListener('click', function(e) {
                if (e.target.closest('.remove-extra')) {
                    e.target.closest('.extra-med-item').remove();
                }
            });

            // ✅ Auto-fill extra medicine
            document.addEventListener('change', function(e) {
                if (e.target.name && e.target.name.includes('[medicine_id]')) {
                    const option = e.target.options[e.target.selectedIndex];
                    const row = e.target.closest('.extra-med-item');

                    const nameField = row.querySelector('input[name*="[custom_name]"]');
                    const dosageField = row.querySelector('input[name*="[dosage]"]');
                    const qtyField = row.querySelector('input[name*="[quantity]"]');

                    if (option.dataset.name) nameField.value = option.dataset.name;
                    if (option.dataset.dosage) dosageField.value = option.dataset.dosage;
                    if (option.dataset.quantity) qtyField.value = option.dataset.quantity;
                }
            });
        });
    </script>
@endpush
