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

        <form action="{{ route('shipments.store') }}" method="POST">
            @csrf
            <div class="row">
                <!-- Left: Shipment Details -->
                <div class="col-lg-8">
                    <!-- Link to Invoice/Patient -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h6 class="fw-bold mb-0"><i class="ti ti-link me-2"></i>Link to Order</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Invoice (Optional)</label>
                                        <select name="invoice_id" class="select">
                                            <option value="">-- Select Invoice --</option>
                                            @foreach($invoices as $inv)
                                                <option value="{{ $inv->id }}">{{ $inv->invoice_number }} - {{ $inv->patient_name }} (₹{{ $inv->total_amount }})</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Patient (Optional)</label>
                                        <select name="patient_id" class="select" id="patientSelect" onchange="fillPatientAddress()">
                                            <option value="">-- Select Patient --</option>
                                            @foreach($patients as $p)
                                                <option value="{{ $p->id }}" 
                                                    data-address="{{ $p->address_1 }}{{ $p->address_2 ? ', '.$p->address_2 : '' }}, {{ $p->city }}, {{ $p->state }} {{ $p->pincode }}"
                                                    data-phone="{{ $p->phone }}">
                                                    {{ $p->first_name }} {{ $p->last_name }}
                                                </option>
                                            @endforeach
                                        </select>
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
                                        <label class="form-label fw-medium">Name <span class="text-danger">*</span></label>
                                        <input type="text" name="recipient_name" class="form-control" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-medium">Phone <span class="text-danger">*</span></label>
                                        <input type="tel" name="recipient_phone" class="form-control" required>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="mb-3">
                                        <label class="form-label fw-medium">Address <span class="text-danger">*</span></label>
                                        <textarea name="recipient_address" class="form-control" rows="2" required></textarea>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label fw-medium">City <span class="text-danger">*</span></label>
                                        <input type="text" name="recipient_city" class="form-control" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label fw-medium">State <span class="text-danger">*</span></label>
                                        <input type="text" name="recipient_state" class="form-control" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label fw-medium">Pincode <span class="text-danger">*</span></label>
                                        <input type="text" name="recipient_pincode" class="form-control" required>
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
                            <table class="table table-sm mb-0" id="itemsTable">
                                <thead class="table-light">
                                    <tr>
                                        <th>Item Name</th>
                                        <th width="100">Quantity</th>
                                        <th width="50"></th>
                                    </tr>
                                </thead>
                                <tbody id="itemsBody">
                                    <!-- Dynamic rows -->
                                </tbody>
                            </table>
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
                                <label class="form-label">Courier Service</label>
                                <select name="courier_name" class="select">
                                    <option value="">Select Courier</option>
                                    <option value="Blue Dart">Blue Dart</option>
                                    <option value="DTDC">DTDC</option>
                                    <option value="Delhivery">Delhivery</option>
                                    <option value="India Post">India Post</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Tracking Number</label>
                                <input type="text" name="tracking_number" class="form-control" placeholder="Auto-generated if empty">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Initial Status</label>
                                <select name="status" class="select">
                                    <option value="pending" selected>Pending</option>
                                    <option value="packed">Packed</option>
                                </select>
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
        <td>
            <input type="text" name="items[][name]" class="form-control form-control-sm" placeholder="Item name" required>
        </td>
        <td>
            <input type="number" name="items[][quantity]" class="form-control form-control-sm" min="1" value="1" required>
        </td>
        <td>
            <button type="button" class="btn btn-sm btn-light text-danger" onclick="removeShipmentItem(this)">
                <i class="ti ti-x"></i>
            </button>
        </td>
    </tr>
</template>

<script>
let itemIndex = 0;

function addShipmentItem() {
    itemIndex++;
    const template = document.getElementById('shipmentItemTemplate');
    const clone = template.content.cloneNode(true);
    clone.querySelectorAll('[name]').forEach(input => {
        input.name = input.name.replace('[]', `[${itemIndex}]`);
    });
    document.getElementById('itemsBody').appendChild(clone);
}

function removeShipmentItem(btn) {
    btn.closest('.shipment-item-row').remove();
}

function fillPatientAddress() {
    const select = document.getElementById('patientSelect');
    const option = select.options[select.selectedIndex];
    if (option.value) {
        document.querySelector('textarea[name="recipient_address"]').value = option.dataset.address || '';
        document.querySelector('input[name="recipient_phone"]').value = option.dataset.phone || '';
    }
}

// Add one item row by default
document.addEventListener('DOMContentLoaded', function() {
    addShipmentItem();
});
</script>
@endsection