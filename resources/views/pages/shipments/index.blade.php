@extends('layout.master')

@section('content')
    <div class="page-wrapper">
        <div class="content">

            <!-- Page Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h6 class="fw-bold mb-0">
                    <i class="ti ti-truck-delivery me-2 text-primary"></i>Shipments
                </h6>
                @can('create-shipments')
                    <a href="{{ route('shipments.create') }}" class="btn btn-primary">
                        <i class="ti ti-plus me-1"></i> Create Shipment
                    </a>
                @endcan

            </div>

            <!-- Search & Filter -->
            <div class="card mb-4">
                <div class="card-body">
                    <form method="GET" action="{{ route('shipments.index') }}" class="row g-3">
                        <div class="col-md-4">
                            <input type="text" name="search" class="form-control"
                                placeholder="Search tracking #, recipient, phone..." value="{{ request('search') }}">
                        </div>
                        <div class="col-md-3">
                            <select name="status" class="select">
                                <option value="">All Status</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="packed" {{ request('status') == 'packed' ? 'selected' : '' }}>Packed</option>
                                <option value="dispatched" {{ request('status') == 'dispatched' ? 'selected' : '' }}>Dispatched
                                </option>
                                <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>Delivered
                                </option>
                                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled
                                </option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="ti ti-search me-1"></i> Filter
                            </button>
                        </div>
                        <div class="col-md-3 text-md-end">
                            <a href="{{ route('shipments.dashboard') }}" class="btn btn-light">
                                <i class="ti ti-layout-dashboard me-1"></i> Dashboard
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Shipments Table -->
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Tracking #</th>
                                    <th>Recipient</th>
                                    <th>Destination</th>
                                    <th>Courier</th>
                                    <th>Status</th>
                                    <th>Created</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($shipments as $shipment)
                                    <tr>
                                        <td>
                                            <a href="{{ route('shipments.show', $shipment) }}"
                                                class="fw-medium text-primary">
                                                {{ $shipment->tracking_number }}
                                            </a>
                                            @if ($shipment->invoice)
                                                <br><small class="text-muted">Inv:
                                                    {{ $shipment->invoice->invoice_number }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="fw-medium">{{ $shipment->recipient_name }}</div>
                                            <small class="text-muted">{{ $shipment->recipient_phone }}</small>
                                        </td>
                                        <td>
                                            {{ $shipment->recipient_city }}, {{ $shipment->recipient_state }}
                                            <br><small class="text-muted">{{ $shipment->recipient_pincode }}</small>
                                        </td>
                                        <td>
                                            {{ $shipment->courier_name ?? '-' }}
                                        </td>
                                        <td>
                                            <span class="badge {{ $shipment->getStatusBadgeClass() }} border">
                                                {{ $shipment->getStatusLabel() }}
                                            </span>
                                            @if ($shipment->dispatched_at)
                                                <br><small
                                                    class="text-muted">{{ $shipment->dispatched_at->format('d M') }}</small>
                                            @endif
                                        </td>
                                        <td>{{ $shipment->created_at->format('d M Y') }}</td>
                                        <td class="text-end">
                                            <div class="btn-group btn-group-sm">
                                                @can('edit-shipments')
                                                    <a href="{{ route('shipments.show', $shipment) }}" class="btn btn-light"
                                                        title="View">
                                                        <i class="ti ti-eye"></i>
                                                    </a>
                                                @endcan
                                                @can('view-shipments')
                                                    <a href="{{ route('shipments.edit', $shipment) }}" class="btn btn-light"
                                                        title="Edit">
                                                        <i class="ti ti-edit"></i>
                                                    </a>
                                                @endcan
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-5 text-muted">
                                            <i class="ti ti-truck-delivery fs-1 mb-3 d-block"></i>
                                            <p>No shipments found.</p>
                                            <a href="{{ route('shipments.create') }}" class="btn btn-primary btn-sm">
                                                <i class="ti ti-plus me-1"></i> Create First Shipment
                                            </a>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if ($shipments->hasPages())
                        <div class="mt-3">
                            {{ $shipments->withQueryString()->links() }}
                        </div>
                    @endif
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
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#0d6efd'
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `/admin/shipments/${shipmentId}/status`;
                    form.innerHTML =
                        `@csrf @method('PATCH')<input type="hidden" name="status" value="${newStatus}">`;
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }
    </script>
@endsection
