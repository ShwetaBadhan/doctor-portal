<!-- Full Width Stats & Chart -->
<div class="col-xl-8">
    <div class="card shadow-sm flex-fill w-100">
        <div class="card-header d-flex align-items-center justify-content-between">
            <h5 class="fw-bold mb-0">Appointment Statistics</h5> 
            <div class="dropdown">
                <a href="javascript:void(0);" class="btn btn-sm px-2 border shadow-sm btn-outline-white d-inline-flex align-items-center" data-bs-toggle="dropdown">
                    {{ ucfirst($period) }} <i class="ti ti-chevron-down ms-1"></i>
                </a>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="{{ route('dashboard', ['period' => 'monthly']) }}">Monthly</a></li>
                    <li><a class="dropdown-item" href="{{ route('dashboard', ['period' => 'weekly']) }}">Weekly</a></li>
                    <li><a class="dropdown-item" href="{{ route('dashboard', ['period' => 'yearly']) }}">Yearly</a></li>
                </ul>
            </div>
        </div>
        <div class="card-body pb-0">
            <div class="row row-gap-3 mb-3">
                <div class="col-md-3 col-sm-6">
                    <div class="bg-light border p-2 text-center rounded-2">
                        <p class="mb-1 text-body text-truncate"><i class="ti ti-point-filled me-1 text-primary"></i>All Appointments</p>
                        <h5 class="fw-bold mb-0">{{ number_format($allAppointments) }}</h5>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="bg-light border p-2 text-center rounded-2">
                        <p class="mb-1 text-body"><i class="ti ti-point-filled me-1 text-danger"></i>Cancelled</p>
                        <h5 class="fw-bold mb-0">{{ number_format($cancelledAppointments) }}</h5>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="bg-light border p-2 text-center rounded-2">
                        <p class="mb-1 text-body"><i class="ti ti-point-filled me-1 text-warning"></i>Pending</p>
                        <h5 class="fw-bold mb-0">{{ number_format($pendingAppointments) }}</h5>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="bg-light border p-2 text-center rounded-2">
                        <p class="mb-1 text-body"><i class="ti ti-point-filled me-1 text-success"></i>Completed</p>
                        <h5 class="fw-bold mb-0">{{ number_format($completedAppointments) }}</h5>
                    </div>
                </div>
            </div>
            <!-- Chart Container -->
            <div class="chart-set" id="s-col-19"></div>
        </div>
    </div>
</div>

<!-- col start -->
<div class="col-xl-4">
    <div class="card shadow-sm">
        <div class="card-header d-flex align-items-center justify-content-between">
            <h5 class="fw-bold mb-0 text-truncate">Upcoming Appointments</h5> 
            
        </div>
        <div class="card-body">
            <div class="datepic mb-1"></div> 
            @forelse($recentAppointments as $app)
            <div class="mb-3 bg-light p-3 rounded-2 d-flex align-items-center justify-content-between">
                <div>
                    <h6 class="fs-14 fw-semibold mb-1">{{ $app->patient->first_name }} {{ $app->patient->last_name }}</h6>
                    <p class="mb-0 text-body text-truncate">
                        <i class="ti ti-calendar-time me-1 text-body"></i> 
                        {{ \Carbon\Carbon::parse($app->appointment_date)->format('D, d M Y') }}, {{ $app->appointment_time }}
                    </p>
                </div>
                <div class="avatar-list-stacked avatar-group-sm event flex-shrink-0"> 
                    @if($app->patient?->profile_image)
                        <span class="avatar avatar-lg rounded-circle border-0"><img src="{{ Storage::url($app->patient->profile_image) }}" class="img-fluid rounded-circle border border-white" alt="Patient"></span>
                    @endif
                    @if($app->user?->profile_photo)
                        <span class="avatar avatar-lg rounded-circle border-0"><img src="{{ asset('storage/' . $app->user->profile_photo) }}" class="img-fluid rounded-circle border border-white" alt="Doctor"></span>
                    @endif
                </div>
            </div>
            @empty
            <p class="text-center text-muted py-3">No upcoming appointments scheduled.</p>
            @endforelse
            <a href="{{ route('appointments.index') }}" class="btn btn-light w-100">View All Appointments</a>
        </div>
    </div>
</div>
<!-- col end -->