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
                <span class="text-primary">{{ $shipment->tracking_number }}</span>
            </h6>
            <div class="d-flex gap-2">
                <a href="{{ route('shipments.index') }}" class="btn btn-light">
                    <i class="ti ti-arrow-left me-1"></i> Back to List
                </a>
                <a href="{{ route('shipments.edit', $shipment) }}" class="btn btn-primary">
                    <i class="ti ti-edit me-1"></i> Edit Details
                </a>
            </div>
        </div>

        <!-- Shipment Status Banner -->
        <div class="alert alert-{{ $shipment->status == 'delivered' ? 'success' : ($shipment->status == 'dispatched' ? 'primary' : 'warning') }} d-flex align-items-center mb-4">
            <i class="ti {{ $shipment->status == 'delivered' ? 'ti-check' : ($shipment->status == 'dispatched' ? 'ti-truck' : 'ti-clock') }} fs-1 me-3"></i>
            <div class="flex-grow-1">
                <h5 class="fw-bold mb-1">{{ $shipment->getStatusLabel() }}</h5>
                <p class="mb-0 small">
                    @if($shipment->status == 'dispatched') Dispatched on {{ $shipment->dispatched_at?->format('d M Y') }}
                    @elseif($shipment->status == 'packed') Packed on {{ $shipment->packed_at?->format('d M Y') }}
                    @elseif($shipment->status == 'delivered') Delivered on {{ $shipment->delivered_at?->format('d M Y') }}
                    @else Awaiting shipment @endif
                </p>
            </div>
            <div>
                <!-- Quick Status Update -->
                <form action="{{ route('shipments.update-status', $shipment) }}" method="POST" class="d-inline-flex">
                    @csrf @method('PATCH')
                    <select name="status" class="select form-select-sm" onchange="this.form.submit()" style="min-width: 120px;">
                        <option value="pending" {{ $shipment->status=='pending'?'selected':'' }}>Pending</option>
                        <option value="packed" {{ $shipment->status=='packed'?'selected':'' }}>Packed</option>
                        <option value="dispatched" {{ $shipment->status=='dispatched'?'selected':'' }}>Dispatched</option>
                        <option value="delivered" {{ $shipment->status=='delivered'?'selected':'' }}>Delivered</option>
                        <option value="cancelled" {{ $shipment->status=='cancelled'?'selected':'' }}>Cancelled</option>
                    </select>
                </form>
            </div>
        </div>

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
                                <p class="text-muted small mb-1">Name</p>
                                <h6 class="fw-bold">{{ $shipment->recipient_name }}</h6>
                            </div>
                            <div class="col-md-6">
                                <p class="text-muted small mb-1">Phone</p>
                                <h6 class="fw-bold">{{ $shipment->recipient_phone }}</h6>
                            </div>
                            <div class="col-12">
                                <p class="text-muted small mb-1">Address</p>
                                <p class="fw-medium">{{ $shipment->recipient_address }}</p>
                            </div>
                            <div class="col-md-4">
                                <p class="text-muted small mb-1">City</p>
                                <p class="fw-medium">{{ $shipment->recipient_city }}</p>
                            </div>
                            <div class="col-md-4">
                                <p class="text-muted small mb-1">State</p>
                                <p class="fw-medium">{{ $shipment->recipient_state }}</p>
                            </div>
                            <div class="col-md-4">
                                <p class="text-muted small mb-1">Pincode</p>
                                <p class="fw-medium">{{ $shipment->recipient_pincode }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Items -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="fw-bold mb-0"><i class="ti ti-package me-2"></i>Items Shipped</h6>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-sm mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Item Name</th>
                                        <th class="text-center">Quantity</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($shipment->items as $index => $item)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td class="fw-medium">{{ $item['name'] }}</td>
                                        <td class="text-center">{{ $item['quantity'] }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Notes -->
                @if($shipment->status_notes)
                <div class="card">
                    <div class="card-header">
                        <h6 class="fw-bold mb-0"><i class="ti ti-note me-2"></i>Notes</h6>
                    </div>
                    <div class="card-body">
                        <p class="mb-0 text-muted">{{ nl2br(e($shipment->status_notes)) }}</p>
                    </div>
                </div>
                @endif
            </div>

            <!-- Right: Shipping Info -->
            <div class="col-lg-4">
                <!-- Tracking Details -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="fw-bold mb-0">Shipping Information</h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <p class="text-muted small mb-1">Tracking Number</p>
                            <h6 class="fw-bold text-primary">{{ $shipment->tracking_number }}</h6>
                        </div>
                        <div class="mb-3">
                            <p class="text-muted small mb-1">Courier Service</p>
                            <h6 class="fw-bold">{{ $shipment->courier_name ?? 'N/A' }}</h6>
                        </div>
                        <hr>
                        <div class="mb-3">
                            <p class="text-muted small mb-1">Created</p>
                            <p class="fw-medium mb-0">{{ $shipment->created_at->format('d M Y, h:i A') }}</p>
                        </div>
                        <div class="mb-3">
                            <p class="text-muted small mb-1">Linked Invoice</p>
                            @if($shipment->invoice)
                                <a href="{{ route('invoices.print', $shipment->invoice) }}" class="fw-medium" target="_blank">{{ $shipment->invoice->invoice_number }}</a>
                            @else
                                <p class="fw-medium mb-0">None</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Status Timeline -->
                <div class="card">
                    <div class="card-header">
                        <h6 class="fw-bold mb-0">Status Timeline</h6>
                    </div>
                    <div class="card-body">
                        <ul class="timeline timeline-simple mb-0">
                            <li class="timeline-item {{ in_array($shipment->status, ['pending','packed','dispatched','delivered']) ? 'active' : '' }}">
                                <div class="timeline-dot bg-warning"></div>
                                <div class="timeline-content">
                                    <p class="fw-bold mb-0">Pending</p>
                                    <small class="text-muted">{{ $shipment->created_at->format('d M Y') }}</small>
                                </div>
                            </li>
                            <li class="timeline-item {{ in_array($shipment->status, ['packed','dispatched','delivered']) ? 'active' : '' }}">
                                <div class="timeline-dot bg-info"></div>
                                <div class="timeline-content">
                                    <p class="fw-bold mb-0">Packed</p>
                                    <small class="text-muted">{{ $shipment->packed_at?->format('d M Y') ?? '-' }}</small>
                                </div>
                            </li>
                            <li class="timeline-item {{ in_array($shipment->status, ['dispatched','delivered']) ? 'active' : '' }}">
                                <div class="timeline-dot bg-primary"></div>
                                <div class="timeline-content">
                                    <p class="fw-bold mb-0">Dispatched</p>
                                    <small class="text-muted">{{ $shipment->dispatched_at?->format('d M Y') ?? '-' }}</small>
                                </div>
                            </li>
                            <li class="timeline-item {{ $shipment->status == 'delivered' ? 'active' : '' }}">
                                <div class="timeline-dot bg-success"></div>
                                <div class="timeline-content">
                                    <p class="fw-bold mb-0">Delivered</p>
                                    <small class="text-muted">{{ $shipment->delivered_at?->format('d M Y') ?? '-' }}</small>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Minimal Timeline CSS */
.timeline { position: relative; padding-left: 20px; }
.timeline-item { position: relative; padding-bottom: 20px; padding-left: 30px; }
.timeline-item:last-child { padding-bottom: 0; }
.timeline-item::before {
    content: '';
    position: absolute;
    left: 5px;
    top: 5px;
    bottom: 0;
    width: 2px;
    background: #e9ecef;
}
.timeline-item:last-child::before { display: none; }
.timeline-item.active::before { background: #0d6efd; }
.timeline-dot {
    position: absolute;
    left: -21px;
    top: 2px;
    width: 12px;
    height: 12px;
    border-radius: 50%;
    border: 2px solid #fff;
    z-index: 1;
}
.timeline-item.active .timeline-dot { box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.2); }
</style>
@endsection