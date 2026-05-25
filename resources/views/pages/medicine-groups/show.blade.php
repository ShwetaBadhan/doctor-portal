@extends('layout.master')

@section('content')
    <div class="page-wrapper">
        <div class="content">
            <!-- Page Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h6 class="fw-bold mb-0 d-flex align-items-center">
                    <a href="{{ route('medicine-groups.index') }}" class="text-dark">
                        <i class="ti ti-chevron-left me-1"></i>Medicine Groups
                    </a>
                    <span class="mx-2">/</span>
                    <span class="text-primary">{{ $medicineGroup->name }}</span>
                </h6>
                <div>
                    <a href="{{ route('medicine-groups.edit', $medicineGroup) }}" class="btn btn-light">
                        <i class="ti ti-edit me-1"></i> Edit Group
                    </a>
                </div>
            </div>

            <!-- Group Details Card -->
            <div class="card mb-4">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="fw-bold mb-3">
                                <i class="ti ti-info-circle me-2"></i>Group Details
                            </h6>
                            <dl class="row mb-0">
                                <dt class="col-sm-4 text-muted">Name</dt>
                                <dd class="col-sm-8 fw-medium">{{ $medicineGroup->name }}</dd>

                                <dt class="col-sm-4 text-muted">Code</dt>
                                <dd class="col-sm-8">
                                    @if ($medicineGroup->code)
                                        <span class="badge bg-light text-dark">{{ $medicineGroup->code }}</span>
                                    @else
                                        <span class="text-muted">N/A</span>
                                    @endif
                                </dd>

                                <dt class="col-sm-4 text-muted">Description</dt>
                                <dd class="col-sm-8">{{ $medicineGroup->description ?? 'N/A' }}</dd>

                                <dt class="col-sm-4 text-muted">Status</dt>
                                <dd class="col-sm-8">
                                    <span class="badge bg-{{ $medicineGroup->is_active ? 'success' : 'secondary' }}">
                                        {{ $medicineGroup->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ✅ MODE SWITCHER: Single vs Bulk Add -->
            <div class="card mb-4 border-primary">
                <div class="card-header bg-primary text-white py-2 d-flex justify-content-between align-items-center">
                    <h6 class="fw-bold mb-0 text-white"><i class="ti ti-plus me-2"></i>Add Medicines</h6>
                    <div class="btn-group btn-group-sm" role="group">
                        <button type="button" class="btn btn-light active" id="singleModeBtn"
                            onclick="switchMode('single')">
                            <i class="ti ti-click me-1"></i>Single Save
                        </button>
                        <button type="button" class="btn btn-light" id="bulkModeBtn" onclick="switchMode('bulk')">
                            <i class="ti ti-list me-1"></i>Bulk Add
                        </button>
                    </div>
                </div>
                <div class="card-body py-3">

                    <!-- ✅ SINGLE MODE FORM (Immediate Save) -->
                    <form action="{{ route('medicines.store') }}" method="POST" id="singleModeForm"
                        class="row g-2 align-items-end">
                        @csrf
                        <input type="hidden" name="medicine_group_id" value="{{ $medicineGroup->id }}">
                        <input type="hidden" name="bulk_mode" value="0">

                        <!-- S.NO -->
                        <div class="col-md-1 col-4">
                            <label class="form-label small fw-medium mb-1">S.NO</label>
                            <input type="number" name="sort_order" class="form-control form-control-sm" placeholder="#"
                                min="1" value="{{ old('sort_order') }}">
                        </div>

                        <!-- Medicine Name (Required) -->
                        <div class="col-md-4 col-8">
                            <label class="form-label small fw-medium mb-1">Medicine Name <span
                                    class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control form-control-sm"
                                placeholder="e.g., G.T-5" required value="{{ old('name') }}" autofocus>
                        </div>

                        <!-- Dosage -->
                        <div class="col-md-2 col-6">
                            <label class="form-label small fw-medium mb-1">Dosage</label>
                            <input type="text" name="dosage" class="form-control form-control-sm" placeholder="3*4"
                                value="{{ old('dosage') }}">
                        </div>

                        <!-- Quantity -->
                        <div class="col-md-2 col-6">
                            <label class="form-label small fw-medium mb-1">Qty</label>
                            <input type="text" name="quantity" class="form-control form-control-sm" placeholder="50ML"
                                value="{{ old('quantity') }}">
                        </div>

                        <!-- Route (Optional) -->
                        <div class="col-md-2 col-6">
                            <label class="form-label small fw-medium mb-1">Route <small
                                    class="text-muted">(Optional)</small></label>
                            <select name="route" class="form-select form-select-sm">
                                <option value="">Select</option>
                                <option value="ORAL" {{ old('route') == 'ORAL' ? 'selected' : '' }}>ORAL</option>
                                <option value="EXTERNAL" {{ old('route') == 'EXTERNAL' ? 'selected' : '' }}>EXTERNAL
                                </option>
                                <option value="INJECTION" {{ old('route') == 'INJECTION' ? 'selected' : '' }}>INJECTION
                                </option>
                                <option value="GULBULES" {{ old('route') == 'GULBULES' ? 'selected' : '' }}>GULBULES
                                </option>
                                <option value="SIP SIP" {{ old('route') == 'SIP SIP' ? 'selected' : '' }}>SIP SIP</option>
                                <option value="APPLY" {{ old('route') == 'APPLY' ? 'selected' : '' }}>APPLY</option>
                            </select>
                        </div>

                        <!-- Submit Button -->
                        <div class="col-md-1 col-6">
                            <button type="submit" class="btn btn-primary btn-sm w-100" title="Save Medicine">
                                <i class="ti ti-device-floppy"></i>
                            </button>
                        </div>
                    </form>

                    <!-- ✅ BULK MODE FORM (Add Multiple, Then Save All) -->
                    <form action="{{ route('medicines.bulk-store') }}" method="POST" id="bulkModeForm"
                        style="display: none;">
                        @csrf
                        <input type="hidden" name="medicine_group_id" value="{{ $medicineGroup->id }}">

                        <div id="bulkMedicinesContainer">
                            <!-- Dynamic rows will be added here -->
                        </div>

                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <button type="button" class="btn btn-sm btn-light" onclick="addBulkRow()">
                                <i class="ti ti-plus me-1"></i> Add More
                            </button>
                            <div>
                                <button type="button" class="btn btn-light me-2" onclick="clearBulkRows()">
                                    <i class="ti ti-trash me-1"></i> Clear All
                                </button>
                                <button type="submit" class="btn btn-success">
                                    <i class="ti ti-check me-1"></i> Save All (<span id="bulkCount">0</span>)
                                </button>
                            </div>
                        </div>
                    </form>

                    <small class="text-muted d-block mt-2">
                        💡 <strong>Single Mode:</strong> Fill & press Enter to save immediately |
                        <strong>Bulk Mode:</strong> Add multiple medicines, then save all at once
                    </small>
                </div>
            </div>

            <!-- Medicines Table -->
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="fw-bold mb-0">
                        <i class="ti ti-pills me-2"></i>Medicines ({{ $medicineGroup->medicines->count() }})
                    </h6>
                </div>
                <div class="card-body">
                    @if ($medicineGroup->medicines->count())
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th width="60">S.No</th>
                                        <th>Medicine Name</th>
                                        <th width="100">Dosage</th>
                                        <th width="100">Quantity</th>
                                        <th width="100">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($medicineGroup->medicines->sortBy('sort_order') as $medicine)
                                        <tr id="row-{{ $medicine->id }}">
                                            <!-- S.No -->
                                            <td>
                                                <span class="view-text">{{ $medicine->sort_order ?? '-' }}</span>
                                                <input type="number"
                                                    class="form-control form-control-sm edit-input d-none"
                                                    name="sort_order" value="{{ $medicine->sort_order }}" min="1"
                                                    style="width: 80px;">
                                            </td>

                                            <!-- Name -->
                                            <td>
                                                <span class="view-text fw-medium">{{ $medicine->name }}</span>
                                                <input type="text"
                                                    class="form-control form-control-sm edit-input d-none" name="name"
                                                    value="{{ $medicine->name }}">
                                            </td>

                                            <!-- Dosage -->
                                            <td>
                                                <span class="view-text">
                                                    @if ($medicine->dosage)
                                                        <span
                                                            class="badge bg-light text-dark">{{ $medicine->dosage }}</span>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </span>
                                                <input type="text"
                                                    class="form-control form-control-sm edit-input d-none" name="dosage"
                                                    value="{{ $medicine->dosage }}">
                                            </td>

                                            <!-- Quantity -->
                                            <td>
                                                <span class="view-text">{{ $medicine->quantity ?? '-' }}</span>
                                                <input type="text"
                                                    class="form-control form-control-sm edit-input d-none" name="quantity"
                                                    value="{{ $medicine->quantity }}">
                                            </td>

                                            <!-- Actions -->
                                            <td>
                                                <!-- View Mode Buttons -->
                                                <div class="view-actions btn-group btn-group-sm">
                                                    <button class="btn btn-light btn-edit"
                                                        onclick="enableEdit({{ $medicine->id }})" title="Edit">
                                                        <i class="ti ti-edit"></i>
                                                    </button>
                                                    <button class="btn btn-light text-danger"
                                                        onclick="deleteMedicine({{ $medicine->id }}, '{{ $medicine->name }}')"
                                                        title="Delete">
                                                        <i class="ti ti-trash"></i>
                                                    </button>
                                                </div>

                                                <!-- Edit Mode Buttons -->
                                                <div class="edit-actions btn-group btn-group-sm d-none">
                                                    <button class="btn btn-success btn-save"
                                                        onclick="saveEdit({{ $medicine->id }})" title="Save">
                                                        <i class="ti ti-check"></i>
                                                    </button>
                                                    <button class="btn btn-light"
                                                        onclick="cancelEdit({{ $medicine->id }})" title="Cancel">
                                                        <i class="ti ti-x"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5 text-muted">
                            <i class="ti ti-pill fs-1 mb-3 d-block"></i>
                            <p>No medicines added yet.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    @if (session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'success',
                    title: 'Done!',
                    text: @json(session('success')),
                    timer: 2500,
                    showConfirmButton: false,
                    toast: true,
                    position: 'top-end'
                });
                const form = document.getElementById('singleModeForm');
                if (form) {
                    form.reset();
                    form.querySelector('[name="name"]').focus();
                }
            });
        </script>
    @endif

    @if ($errors->any())
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const errorList = @json($errors->all()).map(err => `<li>${err}</li>`).join('');
                Swal.fire({
                    icon: 'error',
                    title: 'Oops!',
                    html: `<ul class="text-start mb-0">${errorList}</ul>`,
                    confirmButtonText: 'Got it',
                    confirmButtonColor: '#dc3545'
                });
            });
        </script>
    @endif

    <script>
        let bulkRowIndex = 0;

        // Switch between Single and Bulk Mode
        function switchMode(mode) {
            document.getElementById('singleModeForm').style.display = mode === 'single' ? 'block' : 'none';
            document.getElementById('bulkModeForm').style.display = mode === 'bulk' ? 'block' : 'none';
            document.getElementById('singleModeBtn').classList.toggle('active', mode === 'single');
            document.getElementById('bulkModeBtn').classList.toggle('active', mode === 'bulk');

            if (mode === 'single') {
                document.querySelector('#singleModeForm [name="name"]').focus();
            } else if (bulkRowIndex === 0) {
                addBulkRow();
            }
        }

        // Add Bulk Row
        function addBulkRow() {
            bulkRowIndex++;
            const container = document.getElementById('bulkMedicinesContainer');
            const row = document.createElement('div');
            row.className = 'row g-2 align-items-end mb-2 bulk-row';
            row.innerHTML = `
        <div class="col-md-1"><input type="number" name="medicines[${bulkRowIndex}][sort_order]" class="form-control form-control-sm" placeholder="#" min="1"></div>
        <div class="col-md-4"><input type="text" name="medicines[${bulkRowIndex}][name]" class="form-control form-control-sm" placeholder="Medicine Name *" required></div>
        <div class="col-md-2"><input type="text" name="medicines[${bulkRowIndex}][dosage]" class="form-control form-control-sm" placeholder="Dosage"></div>
        <div class="col-md-2"><input type="text" name="medicines[${bulkRowIndex}][quantity]" class="form-control form-control-sm" placeholder="Qty"></div>
        <div class="col-md-2"><select name="medicines[${bulkRowIndex}][route]" class="form-select form-select-sm"><option value="">Route</option><option value="ORAL">ORAL</option><option value="EXTERNAL">EXTERNAL</option><option value="INJECTION">INJECTION</option><option value="GULBULES">GULBULES</option></select></div>
        <div class="col-md-1"><button type="button" class="btn btn-sm btn-light text-danger w-100" onclick="this.closest('.bulk-row').remove(); updateBulkCount();"><i class="ti ti-x"></i></button></div>
    `;
            container.appendChild(row);
            updateBulkCount();
            row.querySelector('[name*="[name]"]').focus();
        }

        // Update Bulk Count
        function updateBulkCount() {
            document.getElementById('bulkCount').textContent = document.querySelectorAll('.bulk-row').length;
        }

        // Clear Bulk Rows
        function clearBulkRows() {
            if (confirm('Clear all entries?')) {
                document.getElementById('bulkMedicinesContainer').innerHTML = '';
                bulkRowIndex = 0;
                updateBulkCount();
            }
        }

        // ✅ Enable Edit Mode
        function enableEdit(id) {
            const row = document.getElementById(`row-${id}`);
            row.querySelectorAll('.view-text').forEach(el => el.classList.add('d-none'));
            row.querySelectorAll('.edit-input').forEach(el => el.classList.remove('d-none'));
            row.querySelector('.view-actions').classList.add('d-none');
            row.querySelector('.edit-actions').classList.remove('d-none');
            row.querySelector('.edit-input[name="name"]').focus();
        }

        // ✅ Cancel Edit
        function cancelEdit(id) {
            const row = document.getElementById(`row-${id}`);
            row.querySelectorAll('.view-text').forEach(el => el.classList.remove('d-none'));
            row.querySelectorAll('.edit-input').forEach(el => el.classList.add('d-none'));
            row.querySelector('.view-actions').classList.remove('d-none');
            row.querySelector('.edit-actions').classList.add('d-none');
        }

        // ✅ Save Edit (Simple Form Submit - No AJAX)
        function saveEdit(id) {
            const row = document.getElementById(`row-${id}`);
            const formData = {
                _method: 'PUT',
                _token: '{{ csrf_token() }}',
                sort_order: row.querySelector('[name="sort_order"]').value,
                name: row.querySelector('[name="name"]').value,
                dosage: row.querySelector('[name="dosage"]').value,
                quantity: row.querySelector('[name="quantity"]').value
            };

            if (!formData.name.trim()) {
                alert('Medicine name is required');
                return;
            }

            // Simple form submit
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/medicines/${id}`;
            Object.keys(formData).forEach(key => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = key;
                input.value = formData[key];
                form.appendChild(input);
            });
            document.body.appendChild(form);
            form.submit();
        }

        // Delete Medicine
        // Delete Medicine with SweetAlert
        function deleteMedicine(id, name) {
            Swal.fire({
                title: 'Delete Medicine?',
                html: `Are you sure you want to delete <strong>${name}</strong>?<br><br><span class="text-muted">This action cannot be undone.</span>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: '<i class="ti ti-trash me-1"></i> Yes, Delete',
                cancelButtonText: '<i class="ti ti-x me-1"></i> Cancel',
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    // Show loading
                    Swal.fire({
                        title: 'Deleting...',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    // Submit delete form
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `/medicines/${id}`;
                    form.innerHTML = `@csrf @method('DELETE')`;
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }

        // Auto-focus on load
        document.addEventListener('DOMContentLoaded', function() {
            const nameInput = document.querySelector('#singleModeForm [name="name"]');
            if (nameInput) nameInput.focus();
        });
    </script>
@endsection
