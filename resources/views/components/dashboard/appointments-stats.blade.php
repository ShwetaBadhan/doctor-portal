 <div class="col-12 d-flex">
    <div class="card shadow-sm flex-fill w-100">
        <div class="card-header d-flex align-items-center justify-content-between">
            <h5 class="fw-bold mb-0">Recent Shipments</h5> 
            <a href="{{ route('shipments.index') }}" class="btn fw-normal btn-outline-white">View All</a>
        </div>
        <div class="card-body">
            <!-- Table start -->
            <div class="table-responsive table-nowrap">
                <table class="table border">
                    <thead class="thead-light">
                        <tr>
                            <th>Tracking #</th>
                            <th>Patient / Recipient</th>
                            <th>Date</th>
                            <th>Courier</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentShipments as $shipment)
                        <tr>
                            <td>
                                <a href="{{ route('shipments.show', $shipment) }}" class="fw-semibold text-primary">
                                    {{ $shipment->tracking_number }}
                                </a>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar me-2 bg-light text-primary rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="ti ti-user fs-18"></i>
                                    </div>
                                    <div>
                                      <h6 class="fs-14 mb-1 fw-medium">{{ $shipment->recipient_name }}</h6>
                                      <p class="mb-0 fs-13 text-muted">{{ $shipment->recipient_phone }}</p>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $shipment->created_at->format('d M Y - h:i A') }}</td>
                            <td>{{ $shipment->courier_name ?? 'N/A' }}</td>
                            <td>
                                @php
                                    $statusBadge = match($shipment->status) {
                                        'pending'    => 'badge-soft-warning border-warning text-warning',
                                        'packed'     => 'badge-soft-info border-info text-info',
                                        'dispatched' => 'badge-soft-primary border-primary text-primary',
                                        'delivered'  => 'badge-soft-success border-success text-success',
                                        'cancelled'  => 'badge-soft-danger border-danger text-danger',
                                        default      => 'badge-soft-secondary border-secondary text-secondary'
                                    };
                                @endphp
                                <span class="badge fs-13 py-1 {{ $statusBadge }} border rounded fw-medium">
                                    {{ ucfirst($shipment->status) }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-3">No shipments found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <!-- Table end -->
        </div>
    </div> 
</div>