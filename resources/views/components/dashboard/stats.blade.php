<!-- Shipments Card (Replaces Doctors) -->
@can('view-shipments-stats')
  <div class="col-xl-3 col-md-6">
    <div class="position-relative border card rounded-2 shadow-sm">
        <img src="assets/img/bg/bg-01.svg" alt="img" class="position-absolute start-0 top-0">
        <div class="card-body">
            <div class="d-flex align-items-center mb-2 justify-content-between">
                <span class="avatar bg-primary rounded-circle">
                    <i class="ti ti-truck-delivery fs-24"></i>
                </span>
                <div class="text-end">
                    <span class="badge px-2 py-1 fs-12 fw-medium d-inline-flex mb-1 
                        {{ $shipmentsChange >= 0 ? 'bg-success' : 'bg-danger' }}">
                        {{ $shipmentsChange >= 0 ? '+' : '' }}{{ $shipmentsChange }}%
                    </span>
                    <p class="fs-13 mb-0">in last 7 Days</p>
                </div>
            </div>
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <p class="mb-1">Shipments</p>
                    <h3 class="fw-bold mb-0">{{ number_format($shipmentsTotal) }}</h3>
                </div>
                <div>
                    <div id="s-col" class="chart-set"></div>
                </div>
            </div>
        </div>
    </div>
</div>  
@endcan

@can('view-patient-stats')
    <div class="col-xl-3 col-md-6">
    <div class="position-relative border card rounded-2 shadow-sm">
        <img src="assets/img/bg/bg-02.svg" alt="img" class="position-absolute start-0 top-0">
        <div class="card-body">
            <div class="d-flex align-items-center mb-2 justify-content-between">
                <span class="avatar bg-danger rounded-circle">
                    <i class="ti ti-users fs-24"></i>
                </span>
                <div class="text-end">
                    <span class="badge px-2 py-1 fs-12 fw-medium d-inline-flex mb-1 
                        {{ $patientsChange >= 0 ? 'bg-success' : 'bg-danger' }}">
                        {{ $patientsChange >= 0 ? '+' : '' }}{{ $patientsChange }}%
                    </span>
                    <p class="fs-13 mb-0">in last 7 Days</p>
                </div>
            </div>
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <p class="mb-1">Patients</p>
                    <h3 class="fw-bold mb-0">{{ number_format($patientsTotal) }}</h3>
                </div>
                <div>
                    <div id="s-col-2" class="chart-set"></div>
                </div>
            </div>
        </div>
    </div>
</div>

@endcan
<!-- Patients Card -->
@can('view-appointment-stats')
    <!-- Appointments Card -->
<div class="col-xl-3 col-md-6">
    <div class="position-relative border card rounded-2 shadow-sm">
        <img src="assets/img/bg/bg-03.svg" alt="img" class="position-absolute start-0 top-0">
        <div class="card-body">
            <div class="d-flex align-items-center mb-2 justify-content-between">
                <span class="avatar bg-info rounded-circle">
                    <i class="ti ti-calendar-event fs-24"></i>
                </span>
                <div class="text-end">
                    <span class="badge px-2 py-1 fs-12 fw-medium d-inline-flex mb-1 
                        {{ $appointmentsChange >= 0 ? 'bg-success' : 'bg-danger' }}">
                        {{ $appointmentsChange >= 0 ? '+' : '' }}{{ $appointmentsChange }}%
                    </span>
                    <p class="fs-13 mb-0">in last 7 Days</p>
                </div>
            </div>
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <p class="mb-1">Appointments</p>
                    <h3 class="fw-bold mb-0">{{ number_format($appointmentsTotal) }}</h3>
                </div>
                <div>
                    <div id="s-col-3" class="chart-set"></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endcan

@can('view-revenue-stats')
    <!-- Revenue Card -->
<div class="col-xl-3 col-md-6">
    <div class="position-relative border card rounded-2 shadow-sm">
        <img src="assets/img/bg/bg-04.svg" alt="img" class="position-absolute start-0 top-0">
        <div class="card-body">
            <div class="d-flex align-items-center mb-2 justify-content-between">
                <span class="avatar bg-success rounded-circle">
                    <i class="ti ti-currency-rupee fs-24"></i>
                </span>
                <div class="text-end">
                    <span class="badge px-2 py-1 fs-12 fw-medium d-inline-flex mb-1 
                        {{ $revenueChange >= 0 ? 'bg-success' : 'bg-danger' }}">
                        {{ $revenueChange >= 0 ? '+' : '' }}{{ $revenueChange }}%
                    </span>
                    <p class="fs-13 mb-0">in last 7 Days</p>
                </div>
            </div>
            <div class="d-flex align-items-center justify-content-between overflow-hidden">
                <div>
                    <p class="mb-1">Revenue</p>
                    <h3 class="fw-bold mb-0 text-truncate">₹{{ number_format($revenueTotal, 2) }}</h3>
                </div>
                <div>
                    <div id="s-col-4" class="chart-set"></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endcan

