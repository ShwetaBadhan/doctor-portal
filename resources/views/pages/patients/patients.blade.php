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
                                                <a href="{{ route('diagnosis-report.download', $patient->id) }}"
                                                    class="dropdown-item d-flex align-items-center">
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
<div class="modal fade" id="assignModal{{ $patient->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <form id="assignMedicineForm_{{ $patient->id }}"
                  action="{{ route('patients.medicines.assign-custom', $patient->id) }}" 
                  method="POST">
                @csrf
                <div class="modal-header sticky-top bg-white z-3">
                    <h5 class="modal-title">
                        <i class="ti ti-pills me-2"></i>Assign Medicines
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" style="max-height: calc(100vh - 200px); overflow-y: auto;">
                    
                    <p class="text-muted mb-3">
                        Select medicines from group for <strong>{{ $patient->first_name }} {{ $patient->last_name }}</strong>
                    </p>

                    <!-- Medicine Group Selector -->
                    <div class="mb-3">
                        <label class="form-label fw-medium">Medicine Group <span class="text-danger">*</span></label>
                        <select name="medicine_group_id" class="form-select medicine-group-select" 
                                data-patient-id="{{ $patient->id }}" required>
                            <option value="">Select group...</option>
                            @foreach ($medicineGroups as $group)
                                <option value="{{ $group->id }}" 
                                        data-medicines-count="{{ $group->medicines_count }}">
                                    {{ $group->name }}
                                    @if ($group->code)
                                        <span class="text-muted">({{ $group->code }})</span>
                                    @endif
                                    - {{ $group->medicines_count }} medicines
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Loading Spinner -->
                    <div id="medicinesLoading_{{ $patient->id }}" class="text-center py-4 d-none">
                        <div class="spinner-border text-primary" role="status"></div>
                        <p class="mt-2 text-muted">Fetching medicines...</p>
                    </div>

                    <!-- Dynamic Medicines List (Hidden by default) -->
                    <div id="medicinesList_{{ $patient->id }}" class="d-none">
                        
                        <!-- Check All + Actions -->
                        <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
                            <div class="form-check">
                                <input class="form-check-input check-all-medicines" type="checkbox" 
                                       id="checkAll_{{ $patient->id }}"
                                       data-container="medicinesContainer_{{ $patient->id }}">
                                <label class="form-check-label fw-medium" for="checkAll_{{ $patient->id }}">
                                    Select All Medicines
                                </label>
                            </div>
                            <span class="badge bg-light text-dark" id="selectedCount_{{ $patient->id }}">
                                0 selected
                            </span>
                        </div>

                        <!-- Group Medicines Container -->
                        <div id="medicinesContainer_{{ $patient->id }}" 
                             style="max-height: 300px; overflow-y: auto; padding-right: 5px;">
                            <!-- Dynamic content will be injected here -->
                        </div>

                        <!-- ===== EXTRA MEDICINES SECTION ===== -->
                        <div class="mt-4 pt-3 border-top">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="fw-bold mb-0">
                                    <i class="ti ti-plus me-1 text-primary"></i>Extra Medicines
                                </h6>
                                <button type="button" class="btn btn-sm btn-outline-primary" 
                                        onclick="addExtraMedicine('{{ $patient->id }}')">
                                    <i class="ti ti-plus me-1"></i> Add Extra Medicine
                                </button>
                            </div>
                            
                            <!-- Extra Medicines Container -->
                            <div id="extraMedicinesContainer_{{ $patient->id }}">
                                <!-- Extra medicines will be added here -->
                            </div>
                            <small class="text-muted">
                                <i class="ti ti-info-circle me-1"></i>
                                Add medicines that are not part of this group
                            </small>
                        </div>
                        <!-- ===== END EXTRA MEDICINES SECTION ===== -->

                    </div>

                    <!-- No Medicines Message -->
                    <div id="noMedicines_{{ $patient->id }}" class="alert alert-warning d-none">
                        <i class="ti ti-alert-circle me-2"></i>
                        This group has no medicines.
                    </div>

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
                        <textarea name="notes" class="form-control" rows="2" 
                                  placeholder="Optional instructions for all medicines..."></textarea>
                    </div>

                </div>
                <div class="modal-footer sticky-bottom bg-white border-top">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="submitAssign_{{ $patient->id }}" disabled>
                        <i class="ti ti-check me-1"></i> Assign Selected (<span id="submitCount_{{ $patient->id }}">0</span>)
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
    <!-- Success/Error Messages -->
    <div id="assignMessage"></div>
   
@endsection
@push('scripts')
<script>
// Global counter for extra medicines
let extraMedicineCounter = {};

// Add extra medicine row
function addExtraMedicine(patientId) {
    if (!extraMedicineCounter[patientId]) {
        extraMedicineCounter[patientId] = 0;
    }
    extraMedicineCounter[patientId]++;
    
    const container = document.getElementById(`extraMedicinesContainer_${patientId}`);
    const extraIndex = extraMedicineCounter[patientId];
    
    const extraMedHtml = `
        <div class="extra-medicine-item card border-0 bg-light mb-2" id="extraMed_${patientId}_${extraIndex}">
            <div class="card-body py-2 px-3">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h6 class="fw-bold mb-0 text-primary">
                        <i class="ti ti-pill me-1"></i>Extra Medicine ${extraIndex}
                    </h6>
                    <button type="button" class="btn btn-sm btn-light text-danger" 
                            onclick="removeExtraMedicine('${patientId}', ${extraIndex})" 
                            title="Remove">
                        <i class="ti ti-x"></i>
                    </button>
                </div>
                <div class="row g-2">
                    <div class="col-md-6">
                        <label class="form-label small">Select Medicine <span class="text-danger">*</span></label>
                        <select name="extra_medicines[${extraIndex}][medicine_id]" 
                                class="form-select form-select-sm extra-medicine-select" 
                                required onchange="autoFillExtraMedicine(this, '${patientId}', ${extraIndex})">
                            <option value="">-- Select medicine --</option>
                            @foreach(\App\Models\Medicine::where('is_active', true)->orderBy('name')->get() as $med)
                                <option value="{{ $med->id }}" 
                                        data-dosage="{{ $med->dosage }}"
                                        data-quantity="{{ $med->quantity }}"
                                        data-route="{{ $med->route }}"
                                        data-instructions="{{ $med->instructions }}">
                                    {{ $med->name }} @if($med->code)({{ $med->code }})@endif
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small">Dosage</label>
                        <input type="text" name="extra_medicines[${extraIndex}][dosage]" 
                               class="form-control form-control-sm extra-dosage" 
                               placeholder="e.g., 1-0-1" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small">Quantity</label>
                        <input type="text" name="extra_medicines[${extraIndex}][quantity]" 
                               class="form-control form-control-sm extra-quantity" 
                               placeholder="e.g., 30 tabs" required>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    container.insertAdjacentHTML('beforeend', extraMedHtml);
    updateSubmitButton(patientId);
}

// Remove extra medicine
function removeExtraMedicine(patientId, index) {
    const element = document.getElementById(`extraMed_${patientId}_${index}`);
    if (element) {
        element.remove();
        updateSubmitButton(patientId);
    }
}

// Auto-fill medicine details when selected
function autoFillExtraMedicine(select, patientId, index) {
    const selectedOption = select.options[select.selectedIndex];
    
    // ✅ FIXED: Update button whether medicine is selected or not
    updateSubmitButton(patientId);
    
    if (!selectedOption.value) return;
    
    const row = select.closest('.extra-medicine-item');
    
    // Auto-fill from data attributes
    const dosageField = row.querySelector('.extra-dosage');
    const qtyField = row.querySelector('.extra-quantity');
    
    if (dosageField && selectedOption.dataset.dosage) {
        dosageField.value = selectedOption.dataset.dosage;
    }
    if (qtyField && selectedOption.dataset.quantity) {
        qtyField.value = selectedOption.dataset.quantity;
    }
}

// Existing group medicine AJAX code
document.querySelectorAll('.medicine-group-select').forEach(select => {
    select.addEventListener('change', function() {
        const patientId = this.dataset.patientId;
        const groupId = this.value;
        const loading = document.getElementById(`medicinesLoading_${patientId}`);
        const list = document.getElementById(`medicinesList_${patientId}`);
        const container = document.getElementById(`medicinesContainer_${patientId}`);
        const noMedicines = document.getElementById(`noMedicines_${patientId}`);
        const submitBtn = document.getElementById(`submitAssign_${patientId}`);

        list.classList.add('d-none');
        noMedicines.classList.add('d-none');
        container.innerHTML = '';
        submitBtn.disabled = true;
        document.getElementById(`submitCount_${patientId}`).textContent = '0';
        document.getElementById(`selectedCount_${patientId}`).textContent = '0 selected';

        if (!groupId) return;
        loading.classList.remove('d-none');

        fetch(`/medicine-groups/${groupId}/medicines?patient_id=${patientId}`)
            .then(response => response.json())
            .then(data => {
                loading.classList.add('d-none');
                if (data.medicines.length === 0) {
                    noMedicines.classList.remove('d-none');
                    return;
                }

                data.medicines.forEach((medicine, index) => {
                    const itemHtml = `
                        <div class="medicine-item card border-0 shadow-sm mb-2" data-medicine-id="${medicine.id}">
                            <div class="card-body py-2 px-3">
                                <div class="d-flex align-items-start gap-2">
                                    <div class="pt-1">
                                        <input class="form-check-input medicine-checkbox" 
                                               type="checkbox" 
                                               name="medicines[${index}][assign]" 
                                               value="1"
                                               ${medicine.already_assigned ? 'checked' : ''}>
                                        <input type="hidden" name="medicines[${index}][medicine_id]" value="${medicine.id}">
                                        ${medicine.already_assigned ? 
                                            `<input type="hidden" name="medicines[${index}][patient_medicine_id]" value="${medicine.patient_medicine_id}">` 
                                            : ''}
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1 fw-semibold">${medicine.name}</h6>
                                        ${medicine.code ? `<small class="text-muted">Code: ${medicine.code}</small>` : ''}
                                        ${medicine.already_assigned ? 
                                            `<span class="badge badge-soft-success fs-10 ms-1">Already Assigned</span>` 
                                            : ''}
                                    </div>
                                    <div class="medicine-fields" style="min-width: 200px;">
                                        <div class="row g-2">
                                            <div class="col-6">
                                                <input type="text" name="medicines[${index}][dosage]" 
                                                       class="form-control form-control-sm" 
                                                       placeholder="Dosage" value="${medicine.dosage || ''}"
                                                       ${medicine.already_assigned ? '' : 'disabled'}>
                                            </div>
                                            <div class="col-6">
                                                <input type="text" name="medicines[${index}][quantity]" 
                                                       class="form-control form-control-sm" 
                                                       placeholder="Qty" value="${medicine.quantity || ''}"
                                                       ${medicine.already_assigned ? '' : 'disabled'}>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>`;
                    container.insertAdjacentHTML('beforeend', itemHtml);
                });

                container.querySelectorAll('.medicine-checkbox').forEach(checkbox => {
                    checkbox.addEventListener('change', function() {
                        const fields = this.closest('.medicine-item').querySelector('.medicine-fields');
                        fields.querySelectorAll('input').forEach(input => {
                            if (input.name.includes('[dosage]') || input.name.includes('[quantity]')) {
                                input.disabled = !this.checked;
                            }
                        });
                        updateSubmitButton(patientId);
                    });
                });

                list.classList.remove('d-none');
                updateSubmitButton(patientId);
            })
            .catch(error => {
                console.error('Error:', error);
                loading.classList.add('d-none');
                Swal.fire('Error', 'Failed to load medicines', 'error');
            });
    });
});

// Check All functionality
document.addEventListener('change', function(e) {
    if (e.target.classList.contains('check-all-medicines')) {
        const containerId = e.target.dataset.container;
        const container = document.getElementById(containerId);
        const isChecked = e.target.checked;
        
        container.querySelectorAll('.medicine-checkbox').forEach(cb => {
            cb.checked = isChecked;
            const fields = cb.closest('.medicine-item').querySelector('.medicine-fields');
            fields.querySelectorAll('input').forEach(input => {
                if (input.name.includes('[dosage]') || input.name.includes('[quantity]')) {
                    input.disabled = !isChecked;
                }
            });
        });
        const patientId = e.target.closest('form').querySelector('.medicine-group-select').dataset.patientId;
        updateSubmitButton(patientId);
    }
});

// ✅ FIXED: Update submit button - count both group and extra medicines
function updateSubmitButton(patientId) {
    // Count group medicines
    const container = document.getElementById(`medicinesContainer_${patientId}`);
    const groupChecked = container ? container.querySelectorAll('.medicine-checkbox:checked').length : 0;
    
    // Count extra medicines that have a medicine selected
    const extraContainer = document.getElementById(`extraMedicinesContainer_${patientId}`);
    let extraFilled = 0;
    
    if (extraContainer) {
        const extraSelects = extraContainer.querySelectorAll('.extra-medicine-select');
        extraSelects.forEach(select => {
            if (select.value && select.value !== '') {
                extraFilled++;
            }
        });
    }
    
    const totalSelected = groupChecked + extraFilled;
    
    const selectedCountEl = document.getElementById(`selectedCount_${patientId}`);
    const submitCountEl = document.getElementById(`submitCount_${patientId}`);
    const submitBtn = document.getElementById(`submitAssign_${patientId}`);
    
    if (selectedCountEl) selectedCountEl.textContent = `${totalSelected} selected`;
    if (submitCountEl) submitCountEl.textContent = totalSelected;
    if (submitBtn) submitBtn.disabled = totalSelected === 0;
}

// Enable fields when checkbox is checked
document.addEventListener('change', function(e) {
    if (e.target.classList.contains('medicine-checkbox')) {
        const fields = e.target.closest('.medicine-item').querySelector('.medicine-fields');
        fields.querySelectorAll('input').forEach(input => {
            if (input.name.includes('[dosage]') || input.name.includes('[quantity]')) {
                input.disabled = !e.target.checked;
            }
        });
        const patientId = e.target.closest('form').querySelector('.medicine-group-select').dataset.patientId;
        updateSubmitButton(patientId);
    }
});

// ✅ NEW: Listen for changes in extra medicine dropdowns
document.addEventListener('change', function(e) {
    if (e.target.classList.contains('extra-medicine-select')) {
        // Extract patientId from the element ID or parent
        const extraMedItem = e.target.closest('.extra-medicine-item');
        if (extraMedItem) {
            const extraMedId = extraMedItem.id; // e.g., "extraMed_1_1"
            const parts = extraMedId.split('_');
            if (parts.length >= 2) {
                const patientId = parts[1];
                updateSubmitButton(patientId);
            }
        }
    }
});
</script>
@endpush