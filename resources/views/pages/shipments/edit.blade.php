@extends('layout.master')

@section('content')
    <div class="page-wrapper">
        <div class="content">

            <!-- Page Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h6 class="fw-bold mb-0 d-flex align-items-center">
                    <a href="{{ route('shipments.index') }}" class="text-dark">
                        <i class="ti ti-chevron-left me-1"></i>Shipments
                    </a>
                    <span class="mx-2">/</span>
                    <span class="text-primary">Edit #{{ $shipment->tracking_number }}</span>
                </h6>
                <div>
                    <a href="{{ route('shipments.index') }}" class="btn btn-light">Cancel</a>
                </div>
            </div>

            <form action="{{ route('shipments.update', $shipment) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    <!-- Left: Recipient & Items -->
                    <div class="col-lg-8">
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
                                            <input type="text" name="recipient_name" class="form-control"
                                                value="{{ old('recipient_name', $shipment->recipient_name) }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label fw-medium">Phone <span
                                                    class="text-danger">*</span></label>
                                            <input type="tel" name="recipient_phone" class="form-control"
                                                value="{{ old('recipient_phone', $shipment->recipient_phone) }}" required>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="mb-3">
                                            <label class="form-label fw-medium">Address <span
                                                    class="text-danger">*</span></label>
                                            <textarea name="recipient_address" class="form-control" rows="2" required>{{ old('recipient_address', $shipment->recipient_address) }}</textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label fw-medium">Country <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" name="recipient_country" class="form-control"
                                                value="{{ old('recipient_country', $shipment->recipient_country ?? 'India') }}"
                                                required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label fw-medium">City <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" name="recipient_city" class="form-control"
                                                value="{{ old('recipient_city', $shipment->recipient_city) }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label fw-medium">State <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" name="recipient_state" class="form-control"
                                                value="{{ old('recipient_state', $shipment->recipient_state) }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label fw-medium">Pincode <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" name="recipient_pincode" class="form-control"
                                                value="{{ old('recipient_pincode', $shipment->recipient_pincode) }}"
                                                required>
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
                                                <th>Item Name</th>
                                                <th width="100">Quantity</th>
                                                <th width="50"></th>
                                            </tr>
                                        </thead>
                                        <tbody id="itemsBody">
                                            @foreach ($shipment->items as $index => $item)
                                                <tr class="shipment-item-row">
                                                    <td>
                                                        <input type="text" name="items[{{ $index }}][name]"
                                                            class="form-control form-control-sm"
                                                            value="{{ old('items.' . $index . '.name', $item['name']) }}"
                                                            required>
                                                    </td>
                                                    <td>
                                                        <input type="number" name="items[{{ $index }}][quantity]"
                                                            class="form-control form-control-sm" min="1"
                                                            value="{{ old('items.' . $index . '.quantity', $item['quantity']) }}"
                                                            required>
                                                    </td>
                                                    <td>
                                                        <button type="button" class="btn btn-sm btn-light text-danger"
                                                            onclick="removeShipmentItem(this)">
                                                            <i class="ti ti-x"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
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
                                <!-- Status -->
                                <div class="mb-3">
                                    <label class="form-label fw-medium">Status</label>
                                    <select name="status" class="select">
                                        <option value="pending"
                                            {{ old('status', $shipment->status) == 'pending' ? 'selected' : '' }}>Pending
                                        </option>
                                        <option value="packed"
                                            {{ old('status', $shipment->status) == 'packed' ? 'selected' : '' }}>Packed
                                        </option>
                                        <option value="dispatched"
                                            {{ old('status', $shipment->status) == 'dispatched' ? 'selected' : '' }}>
                                            Dispatched</option>
                                        <option value="delivered"
                                            {{ old('status', $shipment->status) == 'delivered' ? 'selected' : '' }}>
                                            Delivered</option>
                                        <option value="cancelled"
                                            {{ old('status', $shipment->status) == 'cancelled' ? 'selected' : '' }}>
                                            Cancelled</option>
                                    </select>
                                </div>

                                <!-- Tracking Number -->
                                <div class="mb-3">
                                    <label class="form-label fw-medium">Tracking Number</label>
                                    <input type="text" name="tracking_number" class="form-control"
                                        value="{{ old('tracking_number', $shipment->tracking_number) }}" readonly>
                                    <small class="text-muted">Tracking ID cannot be changed.</small>
                                </div>

                                <!-- Courier -->
                                <div class="mb-3">
                                    <label class="form-label fw-medium">Courier Service</label>
                                    <select name="courier_name" class="select">
                                        <option value="">Select Courier</option>
                                        <option value="Blue Dart"
                                            {{ old('courier_name', $shipment->courier_name) == 'Blue Dart' ? 'selected' : '' }}>
                                            Blue Dart</option>
                                        <option value="DTDC"
                                            {{ old('courier_name', $shipment->courier_name) == 'DTDC' ? 'selected' : '' }}>
                                            DTDC</option>
                                        <option value="Delhivery"
                                            {{ old('courier_name', $shipment->courier_name) == 'Delhivery' ? 'selected' : '' }}>
                                            Delhivery</option>
                                        <option value="India Post"
                                            {{ old('courier_name', $shipment->courier_name) == 'India Post' ? 'selected' : '' }}>
                                            India Post</option>
                                        <option value="Other"
                                            {{ old('courier_name', $shipment->courier_name) == 'Other' ? 'selected' : '' }}>
                                            Other</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-medium">Remarks / Notes</label>
                                    <textarea name="remarks" class="form-control" rows="3" placeholder="Any special instructions for shipping...">{{ old('remarks', $shipment->remarks) }}</textarea>
                                    <small class="text-muted">Optional: Add delivery instructions, fragile items,
                                        etc.</small>
                                </div>
                                <hr>
                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="ti ti-check me-1"></i> Update Shipment
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Item Row Template (Hidden) -->
    <template id="shipmentItemTemplate">
        <tr class="shipment-item-row">
            <td>
                <input type="text" name="items[][name]" class="form-control form-control-sm" placeholder="Item name"
                    required>
            </td>
            <td>
                <input type="number" name="items[][quantity]" class="form-control form-control-sm" min="1"
                    value="1" required>
            </td>
            <td>
                <button type="button" class="btn btn-sm btn-light text-danger" onclick="removeShipmentItem(this)">
                    <i class="ti ti-x"></i>
                </button>
            </td>
        </tr>
    </template>

    <script>
        // Initialize row index based on existing items
        let itemIndex = {{ count($shipment->items) }};

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
    </script>
@endsection
