@extends('layout.master')

@section('content')
<div class="page-wrapper">
    <div class="content">
        <!-- Page Header -->
        <div class="mb-4">
            <h6 class="fw-bold mb-0 d-flex align-items-center">
                <a href="{{ route('invoices.index') }}" class="text-dark">
                    <i class="ti ti-chevron-left me-1"></i>Invoices
                </a>
                <span class="mx-2">/</span>
                <span class="text-primary">Edit Invoice #{{ $invoice->invoice_number }}</span>
            </h6>
        </div>

        <!-- SweetAlert Messages -->
        @if(session('success'))
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

        <form action="{{ route('invoices.update', $invoice) }}" method="POST" id="invoiceForm">
            @csrf
            @method('PUT')
            
            <div class="row">
                <!-- Left: Invoice Details -->
                <div class="col-lg-8">
                    <!-- Company & Invoice Info -->
                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <h5 class="fw-bold mb-1">{{ $company['name'] }}</h5>
                                    <p class="text-muted small mb-1">{{ $company['address'] }}</p>
                                    <p class="text-muted small mb-1">GSTIN: {{ $company['gstin'] }}</p>
                                    <p class="text-muted small mb-1">PAN: {{ $company['pan'] }}</p>
                                    <p class="text-muted small">Contact: {{ $company['contact'] }}</p>
                                </div>
                                <div class="col-md-6 text-md-end">
                                    <h4 class="text-primary fw-bold">INVOICE</h4>
                                    <div class="mb-2">
                                        <label class="form-label small fw-medium">Invoice No.</label>
                                        <input type="text" class="form-control form-control-sm" value="{{ $invoice->invoice_number }}" disabled>
                                    </div>
                                    <div>
                                        <label class="form-label small fw-medium">Date <span class="text-danger">*</span></label>
                                        <input type="date" name="invoice_date" class="form-control form-control-sm" 
                                               value="{{ old('invoice_date', $invoice->invoice_date->format('Y-m-d')) }}" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Patient Details -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h6 class="fw-bold mb-0"><i class="ti ti-user me-2"></i>Bill To</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-medium">Select Patient</label>
                                        <select id="patientSelect" class="select" onchange="fillPatientDetails()">
                                            <option value="">-- Select or type manually --</option>
                                            @foreach($patients as $p)
                                                <option value="{{ $p->id }}" 
                                                    data-name="{{ $p->first_name }} {{ $p->last_name }}"
                                                    data-phone="{{ $p->phone }}"
                                                    data-address="{{ $p->address_1 }}{{ $p->address_2 ? ', '.$p->address_2 : '' }}, {{ $p->city }}, {{ $p->state }} {{ $p->pincode }}"
                                                    {{ old('patient_id', $invoice->patient_id) == $p->id ? 'selected' : '' }}>
                                                    {{ $p->first_name }} {{ $p->last_name }} ({{ $p->phone }})
                                                </option>
                                            @endforeach
                                        </select>
                                        <small class="text-muted">Or enter details manually below</small>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-medium">Patient Name <span class="text-danger">*</span></label>
                                        <input type="text" name="patient_name" id="patientName" class="form-control" 
                                               value="{{ old('patient_name', $invoice->patient_name) }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-medium">Mobile</label>
                                        <input type="text" name="patient_mobile" id="patientMobile" class="form-control" 
                                               value="{{ old('patient_mobile', $invoice->patient_mobile) }}">
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="mb-3">
                                        <label class="form-label fw-medium">Address</label>
                                        <textarea name="patient_address" id="patientAddress" class="form-control" rows="2">{{ old('patient_address', $invoice->patient_address) }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Items Table -->
                    <div class="card mb-4">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h6 class="fw-bold mb-0"><i class="ti ti-list me-2"></i>Items</h6>
                            <button type="button" class="btn btn-sm btn-primary" onclick="addNewItemRow()">
                                <i class="ti ti-plus me-1"></i> Add Item
                            </button>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-sm mb-0" id="itemsTable">
                                    <thead class="table-light">
                                        <tr>
                                            <th width="5%">#</th>
                                            <th>Item Name <span class="text-danger">*</span></th>
                                            <th width="10%">HSN</th>
                                            <th width="10%">Qty</th>
                                            <th width="12%">Rate</th>
                                            <th width="15%">Tax</th>
                                            <th width="12%">Amount</th>
                                            <th width="5%"></th>
                                        </tr>
                                    </thead>
                                    <tbody id="itemsBody">
                                        @foreach($invoice->items as $index => $item)
                                        <tr class="item-row">
                                            <td class="row-num">{{ $index + 1 }}</td>
                                            <td>
                                                <input type="text" name="items[{{ $index }}][name]" class="form-control form-control-sm item-name" 
                                                       value="{{ old('items.'.$index.'.name', $item['name']) }}" required>
                                            </td>
                                            <td>
                                                <input type="text" name="items[{{ $index }}][hsn]" class="form-control form-control-sm" 
                                                       value="{{ old('items.'.$index.'.hsn', $item['hsn'] ?? '') }}">
                                            </td>
                                            <td>
                                                <input type="number" name="items[{{ $index }}][quantity]" class="form-control form-control-sm item-qty" 
                                                       step="0.01" min="0.01" value="{{ old('items.'.$index.'.quantity', $item['quantity']) }}" 
                                                       required oninput="calculateRow(this)">
                                            </td>
                                            <td>
                                                <input type="number" name="items[{{ $index }}][rate]" class="form-control form-control-sm item-rate" 
                                                       step="0.01" min="0" value="{{ old('items.'.$index.'.rate', $item['rate']) }}" 
                                                       required oninput="calculateRow(this)">
                                            </td>
                                            <td>
                                                <select name="items[{{ $index }}][tax_type]" class="select form-select-sm item-tax-type" 
                                                        onchange="toggleTaxPercent(this); calculateRow(this)">
                                                    <option value="NONE" {{ old('items.'.$index.'.tax_type', $item['tax_type']) == 'NONE' ? 'selected' : '' }}>No Tax</option>
                                                    <option value="IGST" {{ old('items.'.$index.'.tax_type', $item['tax_type']) == 'IGST' ? 'selected' : '' }}>IGST</option>
                                                    <option value="CGST+SGST" {{ old('items.'.$index.'.tax_type', $item['tax_type']) == 'CGST+SGST' ? 'selected' : '' }}>CGST + SGST</option>
                                                </select>
                                                <input type="number" name="items[{{ $index }}][tax_percent]" class="form-control form-control-sm item-tax-percent mt-1" 
                                                       step="0.01" min="0" max="100" placeholder="Tax %" 
                                                       value="{{ old('items.'.$index.'.tax_percent', $item['tax_percent'] ?? '') }}"
                                                       style="{{ old('items.'.$index.'.tax_type', $item['tax_type']) == 'NONE' ? 'display:none;' : '' }}" 
                                                       oninput="calculateRow(this)">
                                            </td>
                                            <td>
                                                <input type="text" class="form-control form-control-sm item-amount" readonly 
                                                       value="{{ number_format(($item['amount'] ?? 0) + ($item['tax_amount'] ?? 0), 2) }}">
                                                <input type="hidden" name="items[{{ $index }}][amount]" class="item-amount-hidden" 
                                                       value="{{ number_format(($item['amount'] ?? 0) + ($item['tax_amount'] ?? 0), 2) }}">
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-sm btn-light text-danger" onclick="removeItemRow(this)" title="Remove">
                                                    <i class="ti ti-x"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div id="emptyItems" style="{{ count($invoice->items) ? 'display:none;' : '' }}" class="text-center py-4 text-muted">
                                <i class="ti ti-package fs-3 d-block mb-2"></i>
                                <p>No items added yet</p>
                                <button type="button" class="btn btn-sm btn-primary" onclick="addNewItemRow()">
                                    <i class="ti ti-plus me-1"></i> Add First Item
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Terms & Notes -->
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-medium">Terms & Conditions</label>
                                        <textarea name="terms" class="form-control" rows="3">{{ old('terms', $invoice->terms ?? 'All Disputes are Subject to Jalandhar jurisdiction Only. Goods Once Sold will not taken back or exchanged.') }}</textarea>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-medium">Notes</label>
                                        <textarea name="notes" class="form-control" rows="3">{{ old('notes', $invoice->notes ?? '') }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right: Summary -->
                <div class="col-lg-4">
                    <div class="card sticky-top" style="top: 20px;">
                        <div class="card-header">
                            <h6 class="fw-bold mb-0">Summary</h6>
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Taxable Amount:</span>
                                <span class="fw-medium" id="taxableAmount">₹{{ number_format($invoice->taxable_amount, 2) }}</span>
                            </div>
                            @if($invoice->igst_amount > 0)
                            <div class="d-flex justify-content-between mb-2" id="igstRow">
                                <span class="text-muted">IGST:</span>
                                <span class="fw-medium text-danger" id="igstAmount">₹{{ number_format($invoice->igst_amount, 2) }}</span>
                            </div>
                            @endif
                            @if($invoice->cgst_amount > 0)
                            <div class="d-flex justify-content-between mb-2" id="cgstRow">
                                <span class="text-muted">CGST:</span>
                                <span class="fw-medium text-success" id="cgstAmount">₹{{ number_format($invoice->cgst_amount, 2) }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2" id="sgstRow">
                                <span class="text-muted">SGST:</span>
                                <span class="fw-medium text-success" id="sgstAmount">₹{{ number_format($invoice->sgst_amount, 2) }}</span>
                            </div>
                            @endif
                            <hr>
                            <div class="d-flex justify-content-between mb-3">
                                <span class="fw-bold fs-5">Total:</span>
                                <span class="fw-bold fs-4 text-primary" id="totalAmount">₹{{ number_format($invoice->total_amount, 2) }}</span>
                            </div>
                            
                            <input type="hidden" name="taxable_amount" id="inputTaxable" value="{{ $invoice->taxable_amount }}">
                            <input type="hidden" name="igst_amount" id="inputIgst" value="{{ $invoice->igst_amount }}">
                            <input type="hidden" name="cgst_amount" id="inputCgst" value="{{ $invoice->cgst_amount }}">
                            <input type="hidden" name="sgst_amount" id="inputSgst" value="{{ $invoice->sgst_amount }}">
                            <input type="hidden" name="total_amount" id="inputTotal" value="{{ $invoice->total_amount }}">
                            
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="ti ti-device-floppy me-1"></i> Update Invoice
                                </button>
                                <a href="{{ route('invoices.index') }}" class="btn btn-light">Cancel</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Item Row Template (Hidden) -->
<template id="itemRowTemplate">
    <tr class="item-row">
        <td class="row-num">1</td>
        <td>
            <input type="text" name="items[][name]" class="form-control form-control-sm item-name" placeholder="Item name" required>
        </td>
        <td>
            <input type="text" name="items[][hsn]" class="form-control form-control-sm" placeholder="HSN">
        </td>
        <td>
            <input type="number" name="items[][quantity]" class="form-control form-control-sm item-qty" step="0.01" min="0.01" placeholder="0" required oninput="calculateRow(this)">
        </td>
        <td>
            <input type="number" name="items[][rate]" class="form-control form-control-sm item-rate" step="0.01" min="0" placeholder="0.00" required oninput="calculateRow(this)">
        </td>
        <td>
            <select name="items[][tax_type]" class="select form-select-sm item-tax-type" onchange="toggleTaxPercent(this); calculateRow(this)">
                <option value="NONE">No Tax</option>
                <option value="IGST">IGST</option>
                <option value="CGST+SGST">CGST + SGST</option>
            </select>
            <input type="number" name="items[][tax_percent]" class="form-control form-control-sm item-tax-percent mt-1" step="0.01" min="0" max="100" placeholder="Tax %" style="display:none;" oninput="calculateRow(this)">
        </td>
        <td>
            <input type="text" class="form-control form-control-sm item-amount" readonly value="0.00">
            <input type="hidden" name="items[][amount]" class="item-amount-hidden">
        </td>
        <td>
            <button type="button" class="btn btn-sm btn-light text-danger" onclick="removeItemRow(this)" title="Remove">
                <i class="ti ti-x"></i>
            </button>
        </td>
    </tr>
</template>

<script>
let rowIndex = {{ count($invoice->items) }};

// Fill patient details when selected from dropdown
function fillPatientDetails() {
    const select = document.getElementById('patientSelect');
    const option = select.options[select.selectedIndex];
    
    if (option.value) {
        document.getElementById('patientName').value = option.dataset.name || '';
        document.getElementById('patientMobile').value = option.dataset.phone || '';
        document.getElementById('patientAddress').value = option.dataset.address || '';
    }
}

// Add new item row
function addNewItemRow() {
    rowIndex++;
    const template = document.getElementById('itemRowTemplate');
    const clone = template.content.cloneNode(true);
    
    // Update name attributes with correct index
    clone.querySelectorAll('[name]').forEach(input => {
        input.name = input.name.replace('[]', `[${rowIndex}]`);
    });
    
    // Set row number
    clone.querySelector('.row-num').textContent = rowIndex;
    
    document.getElementById('itemsBody').appendChild(clone);
    document.getElementById('emptyItems').style.display = 'none';
    updateRowNumbers();
}

// Remove item row
function removeItemRow(btn) {
    btn.closest('.item-row').remove();
    updateRowNumbers();
    if (document.querySelectorAll('.item-row').length === 0) {
        document.getElementById('emptyItems').style.display = 'block';
        rowIndex = 0;
    }
    calculateTotal();
}

// Update row numbers after delete
function updateRowNumbers() {
    document.querySelectorAll('.item-row').forEach((row, idx) => {
        row.querySelector('.row-num').textContent = idx + 1;
    });
}

// Toggle tax percent input
function toggleTaxPercent(select) {
    const input = select.closest('td').querySelector('.item-tax-percent');
    if (select.value === 'NONE') {
        input.style.display = 'none';
        input.value = '';
    } else {
        input.style.display = 'block';
        if (!input.value) input.value = '18'; // Default 18%
    }
    calculateRow(select);
}

// Calculate row amount and tax
function calculateRow(el) {
    const row = el.closest('.item-row');
    const qty = parseFloat(row.querySelector('.item-qty').value) || 0;
    const rate = parseFloat(row.querySelector('.item-rate').value) || 0;
    const taxType = row.querySelector('.item-tax-type').value;
    const taxPercent = parseFloat(row.querySelector('.item-tax-percent').value) || 0;
    
    const amount = qty * rate;
    let tax = 0;
    
    if (taxType !== 'NONE' && taxPercent > 0) {
        tax = (amount * taxPercent) / 100;
    }
    
    row.querySelector('.item-amount').value = (amount + tax).toFixed(2);
    row.querySelector('.item-amount-hidden').value = (amount + tax).toFixed(2);
    
    calculateTotal();
}

// Calculate grand total
function calculateTotal() {
    let taxable = 0, igst = 0, cgstSgst = 0;
    
    document.querySelectorAll('.item-row').forEach(row => {
        const qty = parseFloat(row.querySelector('.item-qty').value) || 0;
        const rate = parseFloat(row.querySelector('.item-rate').value) || 0;
        const taxType = row.querySelector('.item-tax-type').value;
        const taxPercent = parseFloat(row.querySelector('.item-tax-percent').value) || 0;
        
        const amount = qty * rate;
        taxable += amount;
        
        if (taxType === 'IGST' && taxPercent > 0) {
            igst += (amount * taxPercent) / 100;
        } else if (taxType === 'CGST+SGST' && taxPercent > 0) {
            cgstSgst += (amount * taxPercent) / 100;
        }
    });
    
    const cgst = cgstSgst / 2;
    const sgst = cgstSgst / 2;
    const total = taxable + igst + cgst + sgst;
    
    // Update display
    document.getElementById('taxableAmount').textContent = '₹' + taxable.toFixed(2);
    document.getElementById('igstAmount').textContent = '₹' + igst.toFixed(2);
    document.getElementById('cgstAmount').textContent = '₹' + cgst.toFixed(2);
    document.getElementById('sgstAmount').textContent = '₹' + sgst.toFixed(2);
    document.getElementById('totalAmount').textContent = '₹' + total.toFixed(2);
    
    // Update hidden inputs for form submission
    document.getElementById('inputTaxable').value = taxable.toFixed(2);
    document.getElementById('inputIgst').value = igst.toFixed(2);
    document.getElementById('inputCgst').value = cgst.toFixed(2);
    document.getElementById('inputSgst').value = sgst.toFixed(2);
    document.getElementById('inputTotal').value = total.toFixed(2);
    
    // Show/hide tax rows
    document.getElementById('igstRow').style.display = igst > 0 ? 'flex' : 'none';
    document.getElementById('cgstRow').style.display = cgst > 0 ? 'flex' : 'none';
    document.getElementById('sgstRow').style.display = sgst > 0 ? 'flex' : 'none';
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    // Calculate initial totals from pre-filled data
    calculateTotal();
});
</script>
@endsection