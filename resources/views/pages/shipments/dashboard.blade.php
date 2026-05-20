@extends('layout.master')

@section('content')
<div class="page-wrapper">
    <div class="content">
        
        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h6 class="fw-bold mb-0">
                <i class="ti ti-truck-delivery me-2 text-primary"></i>Shipment Dashboard
            </h6>
            <a href="{{ route('shipments.create') }}" class="btn btn-primary">
                <i class="ti ti-plus me-1"></i> Create Shipment
            </a>
        </div>

        <!-- Status Counters -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card border-warning bg-warning bg-opacity-10">
                    <div class="card-body text-center">
                        <i class="ti ti-clock fs-1 text-warning mb-2"></i>
                        <h3 class="fw-bold mb-0">{{ $counters['pending'] }}</h3>
                        <p class="text-muted mb-0">Pending</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-info bg-info bg-opacity-10">
                    <div class="card-body text-center">
                        <i class="ti ti-package fs-1 text-info mb-2"></i>
                        <h3 class="fw-bold mb-0">{{ $counters['packed'] }}</h3>
                        <p class="text-muted mb-0">Packed</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-primary bg-primary bg-opacity-10">
                    <div class="card-body text-center">
                        <i class="ti ti-truck fs-1 text-primary mb-2"></i>
                        <h3 class="fw-bold mb-0">{{ $counters['dispatched'] }}</h3>
                        <p class="text-muted mb-0">Dispatched</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-success bg-success bg-opacity-10">
                    <div class="card-body text-center">
                        <i class="ti ti-check fs-1 text-success mb-2"></i>
                        <h3 class="fw-bold mb-0">{{ $counters['delivered'] }}</h3>
                        <p class="text-muted mb-0">Delivered</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Shipments -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="fw-bold mb-0">Recent Shipments</h6>
                <a href="{{ route('shipments.index') }}" class="btn btn-sm btn-light">View All</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Tracking #</th>
                                <th>Recipient</th>
                                <th>City</th>
                                <th>Status</th>
                                <th>Created</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recent as $shipment)
                            <tr>
                                <td>
                                    <span class="fw-medium">{{ $shipment->tracking_number }}</span>
                                    @if($shipment->courier_name)
                                        <br><small class="text-muted">{{ $shipment->courier_name }}</small>
                                    @endif
                                </td>
                                <td>
                                    <div class="fw-medium">{{ $shipment->recipient_name }}</div>
                                    <small class="text-muted">{{ $shipment->recipient_phone }}</small>
                                </td>
                                <td>{{ $shipment->recipient_city }}, {{ $shipment->recipient_state }}</td>
                                <td>
                                    <span class="badge {{ $shipment->getStatusBadgeClass() }} border">
                                        {{ $shipment->getStatusLabel() }}
                                    </span>
                                </td>
                                <td>{{ $shipment->created_at->format('d M Y') }}</td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('shipments.show', $shipment) }}" class="btn btn-light" title="View">
                                            <i class="ti ti-eye"></i>
                                        </a>
                                        <!-- Quick Status Update -->
                                        <select class="select select-sm" onchange="updateStatus({{ $shipment->id }}, this.value)" title="Update Status">
                                            <option value="">Status</option>
                                            <option value="packed" {{ $shipment->status=='packed'?'selected':'' }}>✓ Packed</option>
                                            <option value="dispatched" {{ $shipment->status=='dispatched'?'selected':'' }}>🚚 Dispatched</option>
                                            <option value="delivered" {{ $shipment->status=='delivered'?'selected':'' }}>✅ Delivered</option>
                                            <option value="cancelled" {{ $shipment->status=='cancelled'?'selected':'' }}>❌ Cancel</option>
                                        </select>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function updateStatus(shipmentId, newStatus) {
    if (!newStatus) return;
    
    Swal.fire({
        title: 'Update Status?',
        text: `Mark this shipment as "${newStatus}"?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Yes, update',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/shipments/${shipmentId}/status`;
            form.innerHTML = `@csrf @method('PATCH')<input type="hidden" name="status" value="${newStatus}">`;
            document.body.appendChild(form);
            form.submit();
        }
    });
}
</script>
@endsection