@extends('layout.master')

@section('content')
    <div class="page-wrapper">
        <div class="content">
            <!-- Page Header -->
            <div class="mb-4">
                <h6 class="fw-bold mb-0 d-flex align-items-center">
                    <a href="{{ route('shipments.index') }}" class="text-dark">
                        <i class="ti ti-chevron-left me-1"></i>Shipments
                    </a>
                    <span class="mx-2">/</span>
                    <span class="text-primary">Create Shipment</span>
                </h6>
            </div>

            <!-- SweetAlert Messages -->
            @if (session('success'))
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: @json(session('success')),
                            timer: 3000,
                            showConfirmButton: false,
                            toast: true,
                            position: 'top-end'
                        });
                    });
                </script>
            @endif

            <form action="{{ route('shipments.store') }}" method="POST" id="shipmentForm">
                @csrf

                <!-- Hidden input to store the linked patient ID -->
                <input type="hidden" name="patient_id" id="patientIdHidden" value="{{ old('patient_id') }}">

                <div class="row">
                    <!-- Left: Shipment Details -->
                    <div class="col-lg-8">
                        <!-- Link to Invoice/Patient -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h6 class="fw-bold mb-0"><i class="ti ti-link me-2"></i>Link to Order / Patient</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label fw-medium">Invoice (Optional)</label>
                                            <select name="invoice_id" class="form-select">
                                                <option value="">-- Select Invoice --</option>
                                                @foreach ($invoices as $inv)
                                                    <option value="{{ $inv->id }}"
                                                        {{ old('invoice_id') == $inv->id ? 'selected' : '' }}>
                                                        {{ $inv->invoice_number }} - {{ $inv->patient_name }}
                                                        (₹{{ $inv->total_amount }})
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label fw-medium">Link Patient (Optional)</label>
                                            <!-- Select has NO name attribute, TomSelect manages it, value goes to hidden input -->
                                            <select id="patientSelect" class="form-select">
                                                <option value="">-- Search by Patient ID, name or phone --</option>
                                                @foreach ($patients as $p)
                                                    <option value="{{ $p->id }}"
                                                        data-name="{{ trim($p->first_name . ' ' . $p->last_name) }}"
                                                        data-phone="{{ $p->phone }}"
                                                        data-patient-id="{{ $p->patient_id }}"
                                                        data-address="{{ trim($p->address_1 . ($p->address_2 ? ', ' . $p->address_2 : '') . ', ' . $p->city . ', ' . $p->state . ' ' . $p->pincode) }}">
                                                        {{ $p->patient_id }} - {{ $p->first_name }} {{ $p->last_name }}
                                                        ({{ $p->phone }})
                                                    </option>
                                                @endforeach
                                            </select>
                                            <small class="text-muted">Select a patient to auto-fill recipient details
                                                below.</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Recipient Details -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h6 class="fw-bold mb-0"><i class="ti ti-user me-2"></i>Recipient Details</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label fw-medium">Name <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" name="recipient_name" id="recipientName"
                                                class="form-control" value="{{ old('recipient_name') }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label fw-medium">Phone <span
                                                    class="text-danger">*</span></label>
                                            <input type="tel" name="recipient_phone" id="recipientPhone"
                                                class="form-control" value="{{ old('recipient_phone') }}" required>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="mb-3">
                                            <label class="form-label fw-medium">Address <span
                                                    class="text-danger">*</span></label>
                                            <textarea name="recipient_address" id="recipientAddress" class="form-control" rows="2" required>{{ old('recipient_address') }}</textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label fw-medium">Country <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" name="recipient_country" class="form-control"
                                                value="{{ old('recipient_country', 'India') }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label fw-medium">City <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" name="recipient_city" class="form-control"
                                                value="{{ old('recipient_city') }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label fw-medium">State <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" name="recipient_state" class="form-control"
                                                value="{{ old('recipient_state') }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label fw-medium">Pincode <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" name="recipient_pincode" class="form-control"
                                                value="{{ old('recipient_pincode') }}" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Items to Ship -->
                        <div class="card mb-4">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h6 class="fw-bold mb-0"><i class="ti ti-package me-2"></i>Items to Ship</h6>
                                <button type="button" class="btn btn-sm btn-primary" onclick="addShipmentItem()">
                                    <i class="ti ti-plus me-1"></i> Add Item
                                </button>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-sm mb-0" id="itemsTable">
                                        <thead class="table-light">
                                            <tr>
                                                <th width="5%" class="text-center">#</th>
                                                <th>Item Name <span class="text-danger">*</span></th>
                                                <th width="15%">Quantity</th>
                                                <th width="5%"></th>
                                            </tr>
                                        </thead>
                                        <tbody id="itemsBody">
                                            <!-- Dynamic rows -->
                                        </tbody>
                                    </table>
                                </div>
                                <div id="emptyItems" class="text-center py-4 text-muted">
                                    <i class="ti ti-package fs-3 d-block mb-2"></i>
                                    <p>No items added yet</p>
                                    <button type="button" class="btn btn-sm btn-primary" onclick="addShipmentItem()">
                                        <i class="ti ti-plus me-1"></i> Add First Item
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right: Shipping Info -->
                    <div class="col-lg-4">
                        <div class="card sticky-top" style="top: 20px;">
                            <div class="card-header">
                                <h6 class="fw-bold mb-0">Shipping Details</h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label fw-medium">Courier Service</label>
                                    <select name="courier_name" class="form-select">
                                        <option value="">Select Courier</option>
                                        <option value="Blue Dart"
                                            {{ old('courier_name') == 'Blue Dart' ? 'selected' : '' }}>Blue Dart</option>
                                        <option value="DTDC" {{ old('courier_name') == 'DTDC' ? 'selected' : '' }}>DTDC
                                        </option>
                                        <option value="Delhivery"
                                            {{ old('courier_name') == 'Delhivery' ? 'selected' : '' }}>Delhivery</option>
                                        <option value="India Post"
                                            {{ old('courier_name') == 'India Post' ? 'selected' : '' }}>India Post</option>
                                        <option value="Other" {{ old('courier_name') == 'Other' ? 'selected' : '' }}>
                                            Other</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-medium">Tracking Number</label>
                                    <input type="text" name="tracking_number" class="form-control"
                                        value="{{ old('tracking_number') }}" placeholder="Auto-generated if empty">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-medium">Initial Status</label>
                                    <select name="status" class="form-select">
                                        <option value="pending"
                                            {{ old('status', 'pending') == 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="packed" {{ old('status') == 'packed' ? 'selected' : '' }}>Packed
                                        </option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-medium">Remarks / Notes</label>
                                    <textarea name="remarks" class="form-control" rows="3" placeholder="Any special instructions for shipping...">{{ old('remarks') }}</textarea>
                                </div>
                                <hr>
                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="ti ti-truck me-1"></i> Create Shipment
                                    </button>
                                    <a href="{{ route('shipments.index') }}" class="btn btn-light">Cancel</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Item Row Template -->
    <template id="shipmentItemTemplate">
        <tr class="shipment-item-row">
            <td class="row-num text-center fw-medium align-middle">1</td>
            <td>
                <input type="text" name="items[0][name]" class="form-control form-control-sm" placeholder="Item name"
                    required>
            </td>
            <td>
                <input type="number" name="items[0][quantity]" class="form-control form-control-sm" min="1"
                    value="1" required>
            </td>
            <td class="align-middle">
                <button type="button" class="btn btn-sm btn-light text-danger" onclick="removeShipmentItem(this)"
                    title="Remove">
                    <i class="ti ti-x"></i>
                </button>
            </td>
        </tr>
    </template>
@endsection

@push('scripts')
    <script>
        let itemIndex = 0;

        function addShipmentItem() {
            itemIndex++;
            const template = document.getElementById('shipmentItemTemplate');
            const clone = template.content.cloneNode(true);

            // Update input names to ensure sequential indexing for Laravel
            clone.querySelectorAll('[name]').forEach(input => {
                input.name = input.name.replace('[0]', `[${itemIndex - 1}]`);
            });

            document.getElementById('itemsBody').appendChild(clone);
            updateRowNumbers();

            // Hide empty state
            const emptyItems = document.getElementById('emptyItems');
            if (emptyItems) emptyItems.style.display = 'none';
        }

        function removeShipmentItem(btn) {
            btn.closest('.shipment-item-row').remove();
            updateRowNumbers();

            // Show empty state if no rows left
            const itemsBody = document.getElementById('itemsBody');
            const emptyItems = document.getElementById('emptyItems');
            if (itemsBody && itemsBody.querySelectorAll('.shipment-item-row').length === 0) {
                if (emptyItems) emptyItems.style.display = 'block';
            }
        }

        function updateRowNumbers() {
            document.querySelectorAll('.shipment-item-row').forEach((row, idx) => {
                // Update serial number
                const rowNum = row.querySelector('.row-num');
                if (rowNum) rowNum.textContent = idx + 1;

                // Update input names to be sequential starting from 0
                row.querySelectorAll('[name]').forEach(input => {
                    if (input.name) {
                        input.name = input.name.replace(/items\[\d*\]/, `items[${idx}]`);
                    }
                });
            });
        }

        // Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    // Add one item row by default
    addShipmentItem();

    // ✅ Initialize TomSelect for Patient (in Create Shipment page)
    if (typeof TomSelect !== 'undefined') {
        const patientSelect = document.getElementById('patientSelect');
        if (patientSelect) {
            const patientOptions = Array.from(patientSelect.options)
                .filter(opt => opt.value)
                .map(opt => ({
                    value: opt.value,
                    text: opt.text,
                    name: opt.dataset.name || '',
                    phone: opt.dataset.phone || '',
                    patientId: opt.dataset.patientId || '',
                    address: opt.dataset.address || ''
                }));

            new TomSelect('#patientSelect', {
                placeholder: 'Search by Patient ID, name or phone...',
                maxItems: 1,
                valueField: 'value',
                labelField: 'text',
                searchField: ['text', 'name', 'phone', 'patientId'],
                options: patientOptions,
                items: [],
                render: {
                    option: (data, escape) => `<div class="py-2">
                        <div class="d-flex justify-content-between">
                            <span class="fw-bold text-primary">${escape(data.patientId)}</span>
                            <small class="text-muted">${escape(data.phone)}</small>
                        </div>
                        <div class="fw-medium">${escape(data.name)}</div>
                    </div>`,
                    item: (data, escape) =>
                        `<div>${escape(data.patientId)} - ${escape(data.name)}</div>`
                },
                onChange: function(value) {
                    const opt = patientSelect.querySelector(`option[value="${value}"]`);
                    if (opt) {
                        // ✅ Auto-fill recipient details
                        document.getElementById('recipientName').value = opt.dataset.name || '';
                        document.getElementById('recipientPhone').value = opt.dataset.phone || '';
                        document.getElementById('recipientAddress').value = opt.dataset.address || '';
                        document.getElementById('patientIdHidden').value = value;
                        
                        // ✅ Parse address to extract city, state, pincode
                        const addressParts = parseAddress(opt.dataset.address || '');
                        if (addressParts.city) {
                            document.getElementById('recipientCity').value = addressParts.city;
                        }
                        if (addressParts.state) {
                            document.getElementById('recipientState').value = addressParts.state;
                        }
                        if (addressParts.pincode) {
                            document.getElementById('recipientPincode').value = addressParts.pincode;
                        }
                        if (addressParts.country) {
                            document.getElementById('recipientCountry').value = addressParts.country;
                        }
                    } else {
                        // Clear all recipient fields if selection is removed
                        ['recipientName', 'recipientPhone', 'recipientAddress', 
                         'recipientCity', 'recipientState', 'recipientPincode', 
                         'recipientCountry', 'patientIdHidden']
                        .forEach(id => {
                            const el = document.getElementById(id);
                            if (el) el.value = '';
                        });
                    }
                }
            });
        }
    }
});

// ✅ Helper function to parse address string
function parseAddress(addressString) {
    const parts = {
        city: '',
        state: '',
        pincode: '',
        country: ''
    };
    
    if (!addressString) return parts;
    
    // Split by comma
    const segments = addressString.split(',').map(s => s.trim());
    
    // Try to extract pincode (usually 6 digits)
    const pincodeMatch = addressString.match(/\b(\d{6})\b/);
    if (pincodeMatch) {
        parts.pincode = pincodeMatch[1];
    }
    
    // Last segment is usually state or country
    if (segments.length >= 2) {
        const lastSegment = segments[segments.length - 1].trim();
        // Check if it looks like a state (not a number)
        if (!/^\d+$/.test(lastSegment)) {
            parts.state = lastSegment;
        }
    }
    
    // Second to last might be city
    if (segments.length >= 3) {
        const citySegment = segments[segments.length - 2].trim();
        // Make sure it's not a pincode
        if (!/^\d+$/.test(citySegment)) {
            parts.city = citySegment;
        }
    }
    
    // Default country to India if not specified
    parts.country = 'India';
    
    return parts;
}
    </script>
@endpush
