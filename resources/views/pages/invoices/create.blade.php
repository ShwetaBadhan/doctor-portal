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
                    <span class="text-primary">Create Invoice</span>
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

            <form action="{{ route('invoices.store') }}" method="POST" id="invoiceForm">
                @csrf
                <div class="row">
                    <!-- Left: Invoice Details -->
                    <div class="col-lg-8">
                        <input type="hidden" name="patient_id" id="patientIdHidden" value="{{ old('patient_id') }}">
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
                                                value="{{ $nextNumber }}" disabled>
                                        </div>
                                        <div>
                                            <label class="form-label small fw-medium">Date <span
                                                    class="text-danger">*</span></label>
                                            <input type="date" name="invoice_date" class="form-control form-control-sm"
                                                value="{{ old('invoice_date', date('Y-m-d')) }}" required>
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
                                            <select id="patientSelect" class="form-select select2-patient">
                                                <option value="">-- Search by ID, name or phone --</option>
                                                @foreach ($patients as $p)
                                                    <option value="{{ $p->id }}"
                                                        data-name="{{ trim($p->first_name . ' ' . $p->last_name) }}"
                                                        data-phone="{{ $p->phone }}"
                                                        data-patient-id="{{ $p->patient_id }}" {{-- ✅ Ye ab value lega --}}
                                                        data-address="{{ trim($p->address_1 . ($p->address_2 ? ', ' . $p->address_2 : '') . ', ' . $p->city . ', ' . $p->state . ' ' . $p->pincode) }}">
                                                        {{ $p->patient_id }} - {{ $p->first_name }} {{ $p->last_name }}
                                                        ({{ $p->phone }})
                                                    </option>
                                                @endforeach
                                            </select>
                                            <small class="text-muted">Search by Patient ID, name or phone number</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label fw-medium">Patient Name <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" name="patient_name" id="patientName" class="form-control"
                                                value="{{ old('patient_name') }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label fw-medium">Mobile</label>
                                            <input type="text" name="patient_mobile" id="patientMobile"
                                                class="form-control" value="{{ old('patient_mobile') }}">
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="mb-3">
                                            <label class="form-label fw-medium">Address</label>
                                            <textarea name="patient_address" id="patientAddress" class="form-control" rows="2">{{ old('patient_address') }}</textarea>
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
                                                <th width="15%">Tax</th>
                                                <th width="15%">Amount <small class="text-muted fw-normal">(Line Total
                                                        ₹)</small></th>
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
                                            <textarea name="terms" class="form-control" rows="3"
                                                placeholder="All disputes subject to Jalandhar jurisdiction. Goods once sold will not be taken back.">{{ old('terms', 'All Disputes are Subject to Jalandhar jurisdiction Only. Goods Once Sold will not taken back or exchanged.') }}</textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label fw-medium">Notes</label>
                                            <textarea name="notes" class="form-control" rows="3" placeholder="Additional notes...">{{ old('notes') }}</textarea>
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
                                    <span class="fw-medium" id="taxableAmount">₹0.00</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2" id="igstRow" style="display:none;">
                                    <span class="text-muted">IGST:</span>
                                    <span class="fw-medium text-danger" id="igstAmount">₹0.00</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2" id="cgstRow" style="display:none;">
                                    <span class="text-muted">CGST:</span>
                                    <span class="fw-medium text-success" id="cgstAmount">₹0.00</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2" id="sgstRow" style="display:none;">
                                    <span class="text-muted">SGST:</span>
                                    <span class="fw-medium text-success" id="sgstAmount">₹0.00</span>
                                </div>
                                <hr>
                                <div class="d-flex justify-content-between mb-3">
                                    <span class="fw-bold fs-5">Total:</span>
                                    <span class="fw-bold fs-4 text-primary" id="totalAmount">₹0.00</span>
                                </div>

                                <input type="hidden" name="taxable_amount" id="inputTaxable">
                                <input type="hidden" name="igst_amount" id="inputIgst">
                                <input type="hidden" name="cgst_amount" id="inputCgst">
                                <input type="hidden" name="sgst_amount" id="inputSgst">
                                <input type="hidden" name="total_amount" id="inputTotal">

                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="ti ti-device-floppy me-1"></i> Create Invoice
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

    <!-- Item Row Template -->

    <!-- Item Row Template -->
    <template id="itemRowTemplate">
        <tr class="item-row">
            <td class="row-num">1</td>
            <td>
                <input type="text" name="items[][name]" class="form-control form-control-sm item-name"
                    placeholder="Item name" required>
            </td>
            <td>
                <input type="text" name="items[][hsn]" class="form-control form-control-sm" placeholder="HSN">
            </td>
            <td>
                <input type="number" name="items[][quantity]" class="form-control form-control-sm item-qty"
                    step="1" min="1" value="1" required oninput="calculateTotal()">
            </td>
            <td>
                <select name="items[][tax_type]" class="select form-select-sm item-tax-type"
                    onchange="toggleTaxInput(this); calculateTotal()">
                    <option value="NONE">No Tax</option>
                    <option value="IGST">IGST</option>
                    <option value="CGST+SGST">CGST + SGST</option>
                </select>
                <input type="number" name="items[][tax_percent]"
                    class="form-control form-control-sm item-tax-percent mt-1" step="0.01" min="0"
                    max="100" placeholder="Tax %" style="display:none;" oninput="calculateTotal()">
            </td>
            <td>
                <input type="number" name="items[][amount]" class="form-control form-control-sm item-amount"
                    step="0.01" min="0" placeholder="₹ Total" required oninput="calculateTotal()">
            </td>
            <td>
                <button type="button" class="btn btn-sm btn-light text-danger" onclick="removeItemRow(this)"
                    title="Remove">
                    <i class="ti ti-x"></i>
                </button>
            </td>
        </tr>
    </template>
    <script>
        let rowIndex = 0;

        // ✅ Patient auto-fill function (Tom Select compatible)
        function fillPatientDetails(patientId) {
            const patientSelect = document.getElementById('patientSelect');
            let selectedOption = null;

            // Find option by value (patient's database id)
            if (patientId) {
                selectedOption = patientSelect.querySelector(`option[value="${patientId}"]`);
            } else {
                // Fallback: get from native select if Tom Select not initialized
                selectedOption = patientSelect.options[patientSelect.selectedIndex];
            }

            if (selectedOption && selectedOption.value) {
                document.getElementById('patientName').value = selectedOption.dataset.name || '';
                document.getElementById('patientMobile').value = selectedOption.dataset.phone || '';
                document.getElementById('patientAddress').value = selectedOption.dataset.address || '';
                document.getElementById('patientIdHidden').value = selectedOption.value;
            } else {
                // Clear fields if no selection
                document.getElementById('patientName').value = '';
                document.getElementById('patientMobile').value = '';
                document.getElementById('patientAddress').value = '';
                document.getElementById('patientIdHidden').value = '';
            }
        }

        // Add new item row
        function addNewItemRow() {
            rowIndex++;
            const template = document.getElementById('itemRowTemplate');
            const clone = template.content.cloneNode(true);

            clone.querySelectorAll('[name]').forEach(input => {
                input.name = input.name.replace('[]', `[${rowIndex}]`);
            });

            clone.querySelector('.item-qty').addEventListener('input', calculateTotal);
            clone.querySelector('.item-amount').addEventListener('input', calculateTotal);
            clone.querySelector('.item-tax-type').addEventListener('change', function() {
                toggleTaxInput(this);
                calculateTotal();
            });
            clone.querySelector('.item-tax-percent').addEventListener('input', calculateTotal);

            clone.querySelector('.row-num').textContent = rowIndex;
            document.getElementById('itemsBody').appendChild(clone);
            document.getElementById('emptyItems').style.display = 'none';
            updateRowNumbers();
            calculateTotal();
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

        // Update serial numbers
        function updateRowNumbers() {
            document.querySelectorAll('.item-row').forEach((row, idx) => {
                row.querySelector('.row-num').textContent = idx + 1;
            });
        }

        // Toggle tax percent input visibility
        function toggleTaxInput(select) {
            const input = select.closest('td').querySelector('.item-tax-percent');
            if (select.value === 'NONE') {
                input.style.display = 'none';
                input.value = '';
            } else {
                input.style.display = 'block';
                if (!input.value) input.value = '18';
            }
        }

        // ✅ Live Calculation Function
        function calculateTotal() {
            let taxable = 0,
                igst = 0,
                cgstSgst = 0;

            document.querySelectorAll('.item-row').forEach(row => {
                const amount = parseFloat(row.querySelector('.item-amount').value) || 0;
                const taxType = row.querySelector('.item-tax-type').value;
                const taxPercent = parseFloat(row.querySelector('.item-tax-percent').value) || 0;

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

            document.getElementById('taxableAmount').textContent = '₹' + taxable.toFixed(2);
            document.getElementById('igstAmount').textContent = '₹' + igst.toFixed(2);
            document.getElementById('cgstAmount').textContent = '₹' + cgst.toFixed(2);
            document.getElementById('sgstAmount').textContent = '₹' + sgst.toFixed(2);
            document.getElementById('totalAmount').textContent = '₹' + total.toFixed(2);

            document.getElementById('inputTaxable').value = taxable.toFixed(2);
            document.getElementById('inputIgst').value = igst.toFixed(2);
            document.getElementById('inputCgst').value = cgst.toFixed(2);
            document.getElementById('inputSgst').value = sgst.toFixed(2);
            document.getElementById('inputTotal').value = total.toFixed(2);

            document.getElementById('igstRow').style.display = igst > 0 ? 'flex' : 'none';
            document.getElementById('cgstRow').style.display = cgst > 0 ? 'flex' : 'none';
            document.getElementById('sgstRow').style.display = sgst > 0 ? 'flex' : 'none';
        }

        // ✅ Initialize Everything on Page Load
        document.addEventListener('DOMContentLoaded', function() {
            addNewItemRow();
            calculateTotal();

            // ✅ Initialize Tom Select
            if (typeof TomSelect !== 'undefined') {
                const patientSelect = document.getElementById('patientSelect');

                // Prepare options data
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

                // Initialize Tom Select
                const tomSelect = new TomSelect('#patientSelect', {
                    placeholder: 'Search by Patient ID, name or phone...',
                    maxItems: 1,
                    valueField: 'value',
                    labelField: 'text',
                    searchField: ['text', 'name', 'phone', 'patientId'],
                    options: patientOptions,
                    items: [],
                    render: {
                        option: function(data, escape) {
                            return `<div class="py-2">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="fw-bold text-primary">${escape(data.patientId)}</span>
                            <small class="text-muted">${escape(data.phone)}</small>
                        </div>
                        <div class="fw-medium">${escape(data.name)}</div>
                    </div>`;
                        },
                        item: function(data, escape) {
                            return `<div>${escape(data.patientId)} - ${escape(data.name)}</div>`;
                        }
                    },
                    // ✅ MAIN FIX: Auto-fill on Tom Select change
                    onChange: function(value) {
                        if (value) {
                            // Find the original option to get data attributes
                            const originalOption = patientSelect.querySelector(
                                `option[value="${value}"]`);
                            if (originalOption) {
                                document.getElementById('patientName').value = originalOption.dataset
                                    .name || '';
                                document.getElementById('patientMobile').value = originalOption.dataset
                                    .phone || '';
                                document.getElementById('patientAddress').value = originalOption.dataset
                                    .address || '';
                                document.getElementById('patientIdHidden').value = value;
                            }
                        } else {
                            // Clear fields when selection is cleared
                            document.getElementById('patientName').value = '';
                            document.getElementById('patientMobile').value = '';
                            document.getElementById('patientAddress').value = '';
                            document.getElementById('patientIdHidden').value = '';
                        }
                    }
                });

                // Store tomSelect instance globally if needed later
                window.patientTomSelect = tomSelect;
            }
        });
    </script>
@endsection
