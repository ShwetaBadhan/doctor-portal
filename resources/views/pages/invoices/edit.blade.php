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

            <form action="{{ route('invoices.update', $invoice) }}" method="POST" id="invoiceForm">
                @csrf
                @method('PUT')

                <div class="row">
                    <!-- Left: Invoice Details -->
                    <div class="col-lg-8">
                        <input type="hidden" name="patient_id" id="patientIdHidden"
                            value="{{ old('patient_id', $invoice->patient_id) }}">

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
                                            <input type="text" class="form-control form-control-sm"
                                                value="{{ $invoice->invoice_number }}" disabled>
                                        </div>
                                        <div>
                                            <label class="form-label small fw-medium">Date <span
                                                    class="text-danger">*</span></label>
                                            <input type="date" name="invoice_date" class="form-control form-control-sm"
                                                value="{{ old('invoice_date', $invoice->invoice_date->format('Y-m-d')) }}"
                                                required>
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
                                            <select id="patientSelect" class="form-select">
                                                <option value="">-- Search or type patient name/phone --</option>
                                                @foreach ($patients as $p)
                                                    <option value="{{ $p->id }}"
                                                        data-name="{{ trim($p->first_name . ' ' . $p->last_name) }}"
                                                        data-phone="{{ $p->phone }}"
                                                        data-address="{{ trim($p->address_1 . ($p->address_2 ? ', ' . $p->address_2 : '') . ', ' . $p->city . ', ' . $p->state . ' ' . $p->pincode) }}"
                                                        {{ old('patient_id', $invoice->patient_id) == $p->id ? 'selected' : '' }}>
                                                        {{ $p->first_name }} {{ $p->last_name }} ({{ $p->phone }})
                                                    </option>
                                                @endforeach
                                            </select>
                                            <small class="text-muted">Search by name or phone number</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label fw-medium">Patient Name <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" name="patient_name" id="patientName" class="form-control"
                                                value="{{ old('patient_name', $invoice->patient_name) }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label fw-medium">Mobile</label>
                                            <input type="text" name="patient_mobile" id="patientMobile"
                                                class="form-control"
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
                                                <th width="5%" class="text-center">#</th>
                                                <th width="20%">Item Name <span class="text-danger">*</span></th>
                                                <th width="8%">HSN</th>
                                                <th width="10%">Qty</th>
                                                <th width="15%">Amount <small class="text-muted">(₹ without
                                                        tax)</small></th>
                                                <th width="15%">Tax</th>
                                                <th width="10%">Unit Price <small class="text-muted">(Auto)</small>
                                                </th>
                                                <th width="12%">Line Total <small class="text-muted">(Auto
                                                        +GST)</small></th>
                                                <th width="5%"></th>
                                            </tr>
                                        </thead>
                                        <tbody id="itemsBody">
                                            @foreach ($invoice->items as $index => $item)
                                                <tr class="item-row"
                                                    data-original-tax-percent="{{ $item['tax_percent'] ?? 0 }}"
                                                    data-original-tax-type="{{ $item['tax_type'] ?? 'NONE' }}">
                                                    <td class="row-num text-center fw-medium align-middle">
                                                        {{ $index + 1 }}</td>
                                                    <td>
                                                        <input type="text" name="items[{{ $index }}][name]"
                                                            class="form-control form-control-sm item-name"
                                                            value="{{ old('items.' . $index . '.name', $item['name']) }}"
                                                            required style="min-width: 150px;">
                                                    </td>
                                                    <td>
                                                        <input type="text" name="items[{{ $index }}][hsn]"
                                                            class="form-control form-control-sm"
                                                            value="{{ old('items.' . $index . '.hsn', $item['hsn'] ?? '') }}"
                                                            style="min-width: 80px;">
                                                    </td>
                                                    <td>
                                                        <input type="number" name="items[{{ $index }}][quantity]"
                                                            class="form-control form-control-sm item-qty" step="0.01"
                                                            min="0.01"
                                                            value="{{ old('items.' . $index . '.quantity', $item['quantity']) }}"
                                                            required oninput="calculateRowTotal(this)"
                                                            style="min-width: 90px;">
                                                    </td>
                                                    <td>
                                                        {{-- ✅ Calculate Amount WITHOUT tax from Line Total --}}
                                                        @php
                                                            $taxPercent = $item['tax_percent'] ?? 0;
                                                            $taxType = $item['tax_type'] ?? 'NONE';

                                                            // DB me already taxable amount save hai
                                                            $amountWithoutTax = $item['amount'];

                                                            // Tax calculate
                                                            $taxAmount = 0;
                                                            if ($taxPercent > 0 && $taxType !== 'NONE') {
                                                                $taxAmount = ($amountWithoutTax * $taxPercent) / 100;
                                                            }

                                                            // Final total with GST
                                                            $lineTotal = $amountWithoutTax + $taxAmount;

                                                            $unitPrice =
                                                                $item['quantity'] > 0
                                                                    ? $amountWithoutTax / $item['quantity']
                                                                    : 0;
                                                        @endphp
                                                        <input type="number" name="items[{{ $index }}][amount]"
                                                            class="form-control form-control-sm item-amount fw-bold text-primary"
                                                            step="0.01" min="0"
                                                            value="{{ old('items.' . $index . '.amount', round($amountWithoutTax, 2)) }}"
                                                            required oninput="calculateRowTotal(this)"
                                                            style="min-width: 110px;">
                                                        <input type="hidden"
                                                            name="items[{{ $index }}][amount_hidden]"
                                                            class="item-amount-hidden"
                                                            value="{{ number_format($amountWithoutTax, 2) }}">
                                                    </td>
                                                    <td>
                                                        <select name="items[{{ $index }}][tax_type]"
                                                            class="form-select form-select-sm item-tax-type"
                                                            onchange="toggleTaxInput(this); calculateRowTotal(this)"
                                                            style="min-width: 120px;">
                                                            <option value="NONE"
                                                                {{ old('items.' . $index . '.tax_type', $taxType) == 'NONE' ? 'selected' : '' }}>
                                                                No Tax</option>
                                                            <option value="IGST"
                                                                {{ old('items.' . $index . '.tax_type', $taxType) == 'IGST' ? 'selected' : '' }}>
                                                                IGST</option>
                                                            <option value="CGST+SGST"
                                                                {{ old('items.' . $index . '.tax_type', $taxType) == 'CGST+SGST' ? 'selected' : '' }}>
                                                                CGST + SGST</option>
                                                        </select>
                                                        <input type="number"
                                                            name="items[{{ $index }}][tax_percent]"
                                                            class="form-control form-control-sm item-tax-percent mt-1"
                                                            step="0.01" min="0" max="100"
                                                            placeholder="Tax %"
                                                            value="{{ old('items.' . $index . '.tax_percent', $taxPercent) }}"
                                                            style="{{ old('items.' . $index . '.tax_type', $taxType) == 'NONE' ? 'display:none;' : '' }} min-width: 90px;"
                                                            oninput="calculateRowTotal(this)">
                                                    </td>
                                                    <td>
                                                        <input type="text"
                                                            class="form-control form-control-sm item-unit-price bg-light"
                                                            value="₹{{ number_format($unitPrice, 2) }}" readonly
                                                            style="min-width: 100px;">
                                                        <input type="hidden"
                                                            name="items[{{ $index }}][unit_price]"
                                                            class="item-unit-price-hidden"
                                                            value="{{ number_format($unitPrice, 2) }}">
                                                    </td>
                                                    <td>
                                                        <input type="text"
                                                            class="form-control form-control-sm item-line-total bg-success bg-opacity-10 text-success fw-bold"
                                                            value="₹{{ number_format($lineTotal, 2) }}" readonly
                                                            style="min-width: 110px;">
                                                        <input type="hidden"
                                                            name="items[{{ $index }}][line_total]"
                                                            class="item-line-total-hidden"
                                                            value="{{ number_format($lineTotal, 2) }}">
                                                        <input type="hidden"
                                                            name="items[{{ $index }}][tax_amount]"
                                                            class="item-tax-amount-hidden"
                                                            value="{{ number_format($taxAmount, 2) }}">
                                                    </td>
                                                    <td class="align-middle">
                                                        <button type="button" class="btn btn-sm btn-light text-danger"
                                                            onclick="removeItemRow(this)" title="Remove">
                                                            <i class="ti ti-x"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div id="emptyItems" style="{{ count($invoice->items) ? 'display:none;' : '' }}"
                                    class="text-center py-4 text-muted">
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
                                <!-- Subtotal -->
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted">Subtotal:</span>
                                    <span class="fw-medium"
                                        id="taxableAmount">₹{{ number_format($invoice->taxable_amount, 2) }}</span>
                                </div>

                                <!-- CGST -->
                                <div class="d-flex justify-content-between mb-2" id="cgstRow"
                                    style="{{ $invoice->cgst_amount > 0 ? '' : 'display:none;' }}">
                                    <span class="text-muted">CGST:</span>
                                    <span class="fw-medium text-success"
                                        id="cgstAmount">₹{{ number_format($invoice->cgst_amount, 2) }}</span>
                                </div>

                                <!-- SGST -->
                                <div class="d-flex justify-content-between mb-2" id="sgstRow"
                                    style="{{ $invoice->sgst_amount > 0 ? '' : 'display:none;' }}">
                                    <span class="text-muted">SGST:</span>
                                    <span class="fw-medium text-success"
                                        id="sgstAmount">₹{{ number_format($invoice->sgst_amount, 2) }}</span>
                                </div>

                                <!-- IGST -->
                                <div class="d-flex justify-content-between mb-2" id="igstRow"
                                    style="{{ $invoice->igst_amount > 0 ? '' : 'display:none;' }}">
                                    <span class="text-muted">IGST:</span>
                                    <span class="fw-medium text-danger"
                                        id="igstAmount">₹{{ number_format($invoice->igst_amount, 2) }}</span>
                                </div>

                                <!-- Total GST -->
                                <div class="d-flex justify-content-between mb-2" id="totalGstRow"
                                    style="{{ $invoice->igst_amount + $invoice->cgst_amount + $invoice->sgst_amount > 0 ? '' : 'display:none;' }}">
                                    <span class="text-muted">Total GST:</span>
                                    <span class="fw-medium text-primary"
                                        id="totalGstAmount">₹{{ number_format($invoice->igst_amount + $invoice->cgst_amount + $invoice->sgst_amount, 2) }}</span>
                                </div>

                                <hr>

                                <!-- Grand Total -->
                                <div class="d-flex justify-content-between mb-3 p-2 bg-light rounded">
                                    <span class="fw-bold fs-5">Grand Total:</span>
                                    <span class="fw-bold fs-3 text-success"
                                        id="totalAmount">₹{{ number_format($invoice->total_amount, 2) }}</span>
                                </div>

                                <!-- Payment Status -->
                                <div class="mb-3">
                                    <label class="form-label fw-medium">Payment Status</label>
                                    <select name="is_paid" class="form-select">
                                        <option value="0" {{ !$invoice->is_paid ? 'selected' : '' }}>⏳ Unpaid
                                        </option>
                                        <option value="1" {{ $invoice->is_paid ? 'selected' : '' }}>✅ Paid</option>
                                    </select>
                                </div>

                                <!-- Hidden Inputs -->
                                <input type="hidden" name="taxable_amount" id="inputTaxable"
                                    value="{{ $invoice->taxable_amount }}">
                                <input type="hidden" name="igst_amount" id="inputIgst"
                                    value="{{ $invoice->igst_amount }}">
                                <input type="hidden" name="cgst_amount" id="inputCgst"
                                    value="{{ $invoice->cgst_amount }}">
                                <input type="hidden" name="sgst_amount" id="inputSgst"
                                    value="{{ $invoice->sgst_amount }}">
                                <input type="hidden" name="total_amount" id="inputTotal"
                                    value="{{ $invoice->total_amount }}">

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
    <!-- Item Row Template - FIXED -->
    <template id="itemRowTemplate">
        <tr class="item-row">
            <td class="row-num text-center fw-medium align-middle">1</td>

            <td>
                <input type="text" name="items[][name]" class="form-control form-control-sm item-name"
                    placeholder="Item name" required style="min-width: 150px;">
            </td>

            <td>
                <input type="text" name="items[][hsn]" class="form-control form-control-sm" placeholder="HSN"
                    style="min-width: 80px;">
            </td>

            <td>
                <input type="number" name="items[][quantity]" class="form-control form-control-sm item-qty"
                    step="0.01" min="0.01" value="1" required oninput="calculateRowTotal(this)"
                    style="min-width: 90px;">
            </td>

            <td>
                {{-- ✅ Amount WITHOUT tax - Visible Input --}}
                <input type="number" name="items[][amount]"
                    class="form-control form-control-sm item-amount fw-bold text-primary" step="0.01" min="0"
                    placeholder="₹ Amount (without tax)" required oninput="calculateRowTotal(this)"
                    style="min-width: 110px;">
                {{-- ✅ Hidden field for JS - FIXED NAME --}}
                <input type="hidden" name="items[][amount_hidden]" class="item-amount-hidden">
            </td>

            <td>
                <select name="items[][tax_type]" class="form-select form-select-sm item-tax-type"
                    onchange="toggleTaxInput(this); calculateRowTotal(this)" style="min-width: 120px;">
                    <option value="NONE">No Tax</option>
                    <option value="IGST">IGST</option>
                    <option value="CGST+SGST">CGST + SGST</option>
                </select>
                <input type="number" name="items[][tax_percent]"
                    class="form-control form-control-sm item-tax-percent mt-1" step="0.01" min="0"
                    max="100" placeholder="Tax %" style="display:none; min-width: 90px;"
                    oninput="calculateRowTotal(this)">
            </td>

            <td>
                <input type="text" class="form-control form-control-sm item-unit-price bg-light" placeholder="₹ 0.00"
                    readonly style="min-width: 100px;">
                <input type="hidden" name="items[][unit_price]" class="item-unit-price-hidden">
            </td>

            <td>
                <input type="text"
                    class="form-control form-control-sm item-line-total bg-success bg-opacity-10 text-success fw-bold"
                    placeholder="₹ 0.00" readonly style="min-width: 110px;">
                <input type="hidden" name="items[][line_total]" class="item-line-total-hidden">
                <input type="hidden" name="items[][tax_amount]" class="item-tax-amount-hidden">
            </td>

            <td class="align-middle">
                <button type="button" class="btn btn-sm btn-light text-danger" onclick="removeItemRow(this)"
                    title="Remove">
                    <i class="ti ti-x"></i>
                </button>
            </td>
        </tr>
    </template>

@endsection
@push('scripts')
    <script>
        let rowIndex = {{ count($invoice->items) }};

        // Calculate row total
        function calculateRowTotal(changedElement) {
            const row = changedElement.closest('.item-row');
            if (!row) return;

            const qty = parseFloat(row.querySelector('.item-qty')?.value) || 0;
            const amount = parseFloat(row.querySelector('.item-amount')?.value) || 0;
            const taxType = row.querySelector('.item-tax-type')?.value || 'NONE';
            const taxPercent = parseFloat(row.querySelector('.item-tax-percent')?.value) || 0;

            let taxAmount = 0;
            if (taxPercent > 0 && taxType !== 'NONE') {
                taxAmount = (amount * taxPercent) / 100;
            }

            const lineTotal = amount + taxAmount;
            const unitPrice = qty > 0 ? amount / qty : 0;

            // Update visible fields
            const unitPriceEl = row.querySelector('.item-unit-price');
            const lineTotalEl = row.querySelector('.item-line-total');
            if (unitPriceEl) unitPriceEl.value = '₹' + unitPrice.toFixed(2);
            if (lineTotalEl) lineTotalEl.value = '₹' + lineTotal.toFixed(2);

            // Update hidden fields for form submission
            const amountHidden = row.querySelector('.item-amount-hidden');
            const lineTotalHidden = row.querySelector('.item-line-total-hidden');
            const taxAmountHidden = row.querySelector('.item-tax-amount-hidden');
            const unitPriceHidden = row.querySelector('.item-unit-price-hidden');

            if (amountHidden) amountHidden.value = amount.toFixed(2);
            if (lineTotalHidden) lineTotalHidden.value = lineTotal.toFixed(2);
            if (taxAmountHidden) taxAmountHidden.value = taxAmount.toFixed(2);
            if (unitPriceHidden) unitPriceHidden.value = unitPrice.toFixed(2);

            calculateGrandTotal();
        }

        // Toggle tax input
        function toggleTaxInput(select) {
            const td = select.closest('td');
            if (!td) return;
            const input = td.querySelector('.item-tax-percent');
            if (!input) return;

            if (select.value === 'NONE') {
                input.style.display = 'none';
                input.value = '';
            } else {
                input.style.display = 'block';
                if (!input.value) input.value = '18';
            }
            calculateRowTotal(select);
        }

        // Calculate grand total
        function calculateGrandTotal() {
            let taxableAmount = 0,
                igstTotal = 0,
                cgstTotal = 0,
                sgstTotal = 0,
                grandTotal = 0;

            document.querySelectorAll('.item-row').forEach(row => {
                const amount = parseFloat(row.querySelector('.item-amount')?.value) || 0;
                const taxType = row.querySelector('.item-tax-type')?.value || 'NONE';
                const taxPercent = parseFloat(row.querySelector('.item-tax-percent')?.value) || 0;

                let rowTax = 0;
                if (taxPercent > 0 && taxType !== 'NONE') {
                    rowTax = (amount * taxPercent) / 100;
                }

                const lineTotal = amount + rowTax;

                taxableAmount += amount;
                grandTotal += lineTotal;

                if (taxType === 'IGST' && taxPercent > 0) {
                    igstTotal += rowTax;
                } else if (taxType === 'CGST+SGST' && taxPercent > 0) {
                    cgstTotal += rowTax / 2;
                    sgstTotal += rowTax / 2;
                }
            });

            const totalGst = igstTotal + cgstTotal + sgstTotal;

            // Update display
            document.getElementById('taxableAmount').textContent = '₹' + taxableAmount.toFixed(2);
            document.getElementById('igstAmount').textContent = '₹' + igstTotal.toFixed(2);
            document.getElementById('cgstAmount').textContent = '₹' + cgstTotal.toFixed(2);
            document.getElementById('sgstAmount').textContent = '₹' + sgstTotal.toFixed(2);
            document.getElementById('totalGstAmount').textContent = '₹' + totalGst.toFixed(2);
            document.getElementById('totalAmount').textContent = '₹' + grandTotal.toFixed(2);

            // Update hidden inputs for form submission
            document.getElementById('inputTaxable').value = taxableAmount.toFixed(2);
            document.getElementById('inputIgst').value = igstTotal.toFixed(2);
            document.getElementById('inputCgst').value = cgstTotal.toFixed(2);
            document.getElementById('inputSgst').value = sgstTotal.toFixed(2);
            document.getElementById('inputTotal').value = grandTotal.toFixed(2);

            // Show/hide tax rows
            document.getElementById('igstRow').style.display = igstTotal > 0.005 ? 'flex' : 'none';
            document.getElementById('cgstRow').style.display = cgstTotal > 0.005 ? 'flex' : 'none';
            document.getElementById('sgstRow').style.display = sgstTotal > 0.005 ? 'flex' : 'none';
            document.getElementById('totalGstRow').style.display = totalGst > 0.005 ? 'flex' : 'none';
        }

        // Add new item row
        function addNewItemRow() {
            rowIndex++;
            const template = document.getElementById('itemRowTemplate');
            if (!template) return;

            const clone = template.content.cloneNode(true);

            clone.querySelectorAll('[name]').forEach(input => {
                if (input.name) input.name = input.name.replace('[]', `[${rowIndex}]`);
            });

            // Add event listeners
            clone.querySelector('.item-qty')?.addEventListener('input', function() {
                calculateRowTotal(this);
            });
            clone.querySelector('.item-amount')?.addEventListener('input', function() {
                calculateRowTotal(this);
            });
            clone.querySelector('.item-tax-type')?.addEventListener('change', function() {
                toggleTaxInput(this);
                calculateRowTotal(this);
            });
            clone.querySelector('.item-tax-percent')?.addEventListener('input', function() {
                calculateRowTotal(this);
            });

            const rowNum = clone.querySelector('.row-num');
            if (rowNum) rowNum.textContent = rowIndex;

            const itemsBody = document.getElementById('itemsBody');
            const emptyItems = document.getElementById('emptyItems');

            if (itemsBody) {
                itemsBody.appendChild(clone);
                if (emptyItems) emptyItems.style.display = 'none';
            }

            updateRowNumbers();
            calculateGrandTotal();
        }

        // Remove row
        function removeItemRow(btn) {
            btn.closest('.item-row')?.remove();
            updateRowNumbers();

            const itemsBody = document.getElementById('itemsBody');
            const emptyItems = document.getElementById('emptyItems');

            if (itemsBody && itemsBody.querySelectorAll('.item-row').length === 0) {
                if (emptyItems) emptyItems.style.display = 'block';
                rowIndex = {{ count($invoice->items) }};
            }
            calculateGrandTotal();
        }

        // Update row numbers
        function updateRowNumbers() {
            document.querySelectorAll('.item-row').forEach((row, idx) => {
                const rowNum = row.querySelector('.row-num');
                if (rowNum) rowNum.textContent = idx + 1;
            });
        }

        // Patient auto-fill
        function fillPatientDetails() {
            const patientSelect = document.getElementById('patientSelect');
            if (!patientSelect) return;

            const option = patientSelect.querySelector(`option[value="${patientSelect.value}"]`);

            if (option && option.value) {
                document.getElementById('patientName').value = option.dataset.name || '';
                document.getElementById('patientMobile').value = option.dataset.phone || '';
                document.getElementById('patientAddress').value = option.dataset.address || '';
                document.getElementById('patientIdHidden').value = option.value;
            }
        }

        // ============================================
        // ✅ INITIALIZATION - Run after DOM is ready
        // ============================================
        document.addEventListener('DOMContentLoaded', function() {
            console.log('🔄 Initializing invoice edit page...');

            // ✅ Step 1: Sync hidden fields with visible inputs for existing rows
            document.querySelectorAll('.item-row').forEach(row => {
                const amountInput = row.querySelector('.item-amount');
                const amountHidden = row.querySelector('.item-amount-hidden');
                const lineTotalInput = row.querySelector('.item-line-total');
                const lineTotalHidden = row.querySelector('.item-line-total-hidden');
                const taxAmountHidden = row.querySelector('.item-tax-amount-hidden');
                const unitPriceInput = row.querySelector('.item-unit-price');
                const unitPriceHidden = row.querySelector('.item-unit-price-hidden');

                if (amountInput && amountHidden) {
                    const amount = parseFloat(amountInput.value) || 0;
                    amountHidden.value = amount.toFixed(2);
                }
                if (lineTotalInput && lineTotalHidden) {
                    const lineTotal = parseFloat(lineTotalInput.value.replace('₹', '')) || 0;
                    lineTotalHidden.value = lineTotal.toFixed(2);
                }
                if (taxAmountHidden) {
                    const taxAmount = parseFloat(taxAmountHidden.value) || 0;
                    taxAmountHidden.value = taxAmount.toFixed(2);
                }
                if (unitPriceInput && unitPriceHidden) {
                    const unitPrice = parseFloat(unitPriceInput.value.replace('₹', '')) || 0;
                    unitPriceHidden.value = unitPrice.toFixed(2);
                }
            });

            // ✅ Step 2: Calculate grand total after syncing
            setTimeout(function() {
                calculateGrandTotal();
                console.log('✅ Calculations complete');
            }, 100);

            // Initialize Tom Select
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
                        items: patientSelect.value ? [patientSelect.value] : [],
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
                                document.getElementById('patientName').value = opt.dataset.name || '';
                                document.getElementById('patientMobile').value = opt.dataset.phone ||
                                '';
                                document.getElementById('patientAddress').value = opt.dataset.address ||
                                    '';
                                document.getElementById('patientIdHidden').value = value;
                            } else {
                                ['patientName', 'patientMobile', 'patientAddress', 'patientIdHidden']
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
    </script>
@endpush
