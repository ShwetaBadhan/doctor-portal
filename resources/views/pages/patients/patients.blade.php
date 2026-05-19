@extends('layout.master')
@section('content')
    <!-- ========================
                       Start Page Content
                      ========================= -->

    <div class="page-wrapper">

        <!-- Start Content -->
        <div class="content">

            <!-- Start Page Header -->
            <div class="d-flex align-items-sm-center flex-sm-row flex-column gap-2 pb-3 mb-3 border-1 border-bottom">
                <div class="flex-grow-1">
                    <h4 class="fw-bold mb-0">Patients List <span
                            class="badge badge-soft-primary fw-medium border py-1 px-2 border-primary fs-13 ms-1">Total
                            Patients : {{ count($patients) }}</span></h4>
                </div>
                <div class="text-end d-flex">
                    <!-- dropdown-->
                    {{-- <div class="dropdown me-1">
                        <a href="javascript:void(0);"
                            class="btn btn-md fs-14 fw-normal border bg-white rounded text-dark d-inline-flex align-items-center"
                            data-bs-toggle="dropdown">
                            Export<i class="ti ti-chevron-down ms-2"></i>
                        </a>
                        <ul class="dropdown-menu p-2">
                            <li>
                                <a class="dropdown-item" href="#">Download as PDF</a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="#">Download as Excel</a>
                            </li>
                        </ul>
                    </div> --}}
                    <div
                        class="bg-white border shadow-sm rounded px-1 pb-0 text-center d-flex align-items-center justify-content-center">

                        <a href="{{ route('patients.index') }}"
                            class="bg-light rounded p-1 d-flex align-items-center justify-content-center"> <i
                                class="ti ti-list fs-14 text-body"></i></a>
                        <a href="{{ route('patients.index') }}"
                            class="bg-white rounded p-1 d-flex align-items-center justify-content-center"> <i
                                class="ti ti-layout-grid fs-14 text-body"></i> </a>
                    </div>
                    @can('create-patients')
                        <a href="{{ route('patients.create') }}" class="btn btn-primary ms-2 fs-13 btn-md"><i
                                class="ti ti-plus me-1"></i>New Patient</a>
                    @endcan

                </div>
            </div>
            <!-- End Page Header -->

            <!--  Start Filter -->
            <div class=" d-flex align-items-center justify-content-between flex-wrap">
                <div>
                    <div class="search-set mb-3">
                        <div class="d-flex align-items-center flex-wrap gap-2">
                            <div class="table-search d-flex align-items-center mb-0">
                                <div class="search-input">
                                    <a href="javascript:void(0);" class="btn-searchset"></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


            </div>
            <!--  End Filter -->
            <!-- SweetAlert Session Messages -->
            @if (session('success'))
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: @json(session('success')),
                            timer: 4000,
                            timerProgressBar: true,
                            showConfirmButton: false,
                            toast: true,
                            position: 'top-end'
                        });
                    });
                </script>
            @endif

            @if (session('error'))
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: @json(session('error')),
                            confirmButtonColor: '#dc3545'
                        });
                    });
                </script>
            @endif

            @if ($errors->any())
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        const errorList = @json($errors->all()).map(err => `<li>${err}</li>`).join('');
                        Swal.fire({
                            icon: 'error',
                            title: 'Validation Error',
                            html: `<ul class="text-start mb-0">${errorList}</ul>`,
                            confirmButtonText: 'Got it',
                            confirmButtonColor: '#dc3545'
                        });
                    });
                </script>
            @endif
            <!--  Start Table -->
            <div class="table-responsive">
                <table class="table datatable table-nowrap">
                    <thead class="">
                        <tr>
                            <th>Patient</th>
                            <th>Phone</th>
                            <th>Doctor</th>
                            <th>Address</th>
                            <th>Last Visit</th>
                            <th>Status</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($patients as $patient)
                            <tr>
                                <!-- Patient Name & Image -->
                                <td>
                                    <div class="d-flex align-items-center">
                                        <a href="{{ route('patients.show', $patient->id) }}" class="avatar avatar-md me-2">
                                            @if ($patient->profile_image)
                                                <img src="{{ Storage::url($patient->profile_image) }}"
                                                    alt="{{ $patient->first_name }}" class="rounded-circle">
                                            @else
                                                <span
                                                    class="avatar-text bg-light text-muted rounded-circle d-flex align-items-center justify-content-center w-100 h-100">
                                                    {{ substr($patient->first_name, 0, 1) }}
                                                </span>
                                            @endif
                                        </a>
                                        <a href="{{ route('patients.show', $patient->id) }}" class="text-dark fw-semibold">
                                            {{ $patient->first_name }} {{ $patient->last_name }}
                                            <span class="text-body fs-13 fw-normal d-block">
                                                {{ $patient->age }}, {{ ucfirst($patient->gender) }}
                                            </span>
                                        </a>
                                    </div>
                                </td>

                                <!-- Phone -->
                                <td>{{ $patient->phone }}</td>

                                <!-- Doctor -->
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar me-2 flex-shrink-0 bg-light text-muted rounded-circle d-flex align-items-center justify-content-center"
                                            style="width: 32px; height: 32px;">
                                            <i class="ti ti-user fs-14"></i>
                                        </div>
                                        <div>
                                            <h6 class="fs-14 mb-1">
                                                <a href="javascript:void(0);" class="fw-semibold text-dark">
                                                    {{ $patient->primary_doctor ?? 'N/A' }}
                                                </a>
                                            </h6>
                                            <p class="mb-0 fs-13 text-muted">Doctor</p>
                                        </div>
                                    </div>
                                </td>

                                <!-- Address -->
                                <td>{{ $patient->city }}, {{ $patient->state }}</td>

                                <!-- Last Visit (using created_at as fallback) -->
                                <td>{{ $patient->updated_at ? $patient->updated_at->format('d M Y') : $patient->created_at->format('d M Y') }}
                                </td>

                                <!-- Status Badge -->
                                <td>
                                    @if ($patient->status == 'available')
                                        <span
                                            class="badge badge-soft-success rounded text-success border border-success fs-13 fw-medium">Available</span>
                                    @else
                                        <span
                                            class="badge badge-soft-danger rounded text-danger border border-danger fs-13 fw-medium">Unavailable</span>
                                    @endif
                                </td>

                                <!-- Actions -->
                                <td class="action-item">
                                    <div class="d-flex align-items-center gap-1">
                                        @can('view-patient-appointment')
                                            <!-- Appointment Button (optional) -->
                                            <a href="{{ route('appointments.create', ['patient_id' => $patient->id]) }}"
                                                class="btn btn-primary">
                                                <i class="ti ti-calendar-event me-1"></i>
                                            </a>
                                        @endcan
                                        <a href="javascript:void(0);"
                                            class="shadow-sm fs-14 d-inline-flex border rounded-2 p-1"
                                            data-bs-toggle="dropdown" title="More">
                                            <i class="ti ti-dots-vertical"></i>
                                        </a>
                                        <ul class="dropdown-menu p-2">
                                            <li>
                                                @can('edit-patients')
                                                    <a href="{{ route('patients.edit', $patient->id) }}"
                                                        class="dropdown-item d-flex align-items-center">Edit</a>
                                                @endcan

                                            </li>
                                            <li>
                                                @can('view-patient-details')
                                                    <a href="{{ route('patients.show', $patient->id) }}"
                                                        class="dropdown-item d-flex align-items-center">View</a>
                                                @endcan
                                            </li>
                                            <li>
                                                <!-- Assign Medicines Button (opens patient-specific modal) -->
                                            <li>
                                                @can('assign-medicines-to-patients')
                                                    <a href="javascript:void(0);"
                                                        class="dropdown-item d-flex align-items-center" data-bs-toggle="modal"
                                                        data-bs-target="#assignModal{{ $patient->id }}">
                                                        <i class="ti ti-pills me-2 fs-14"></i> Assign Medicines
                                                    </a>
                                                @endcan
                                            </li>
                                            <li>
                                                @can('delete-patients')
                                                    <!-- Trigger modal with data attributes -->
                                                    <a href="javascript:void(0);"
                                                        class="dropdown-item d-flex align-items-center text-danger"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#delete_modal{{ $patient->id }}">
                                                        <i class="ti ti-trash me-2 fs-14"></i> Delete
                                                    </a>
                                                @endcan
                                            </li>
                                        </ul>
                                    </div>
                                </td>

                            </tr>

                            <!-- Start Delete Modal -->
                            <div class="modal fade" id="delete_modal{{ $patient->id }}" tabindex="-1">
                                <div class="modal-dialog modal-dialog-centered modal-sm">
                                    <div class="modal-content">
                                        <div class="modal-body text-center position-relative">
                                            <img src="{{ asset('assets/img/bg/delete-modal-bg-01.png') }}" alt=""
                                                class="img-fluid position-absolute top-0 start-0 z-0">
                                            <img src="{{ asset('assets/img/bg/delete-modal-bg-02.png') }}" alt=""
                                                class="img-fluid position-absolute bottom-0 end-0 z-0">

                                            <div class="mb-3 position-relative z-1">
                                                <span class="avatar avatar-lg bg-danger text-white">
                                                    <i class="ti ti-trash fs-24"></i>
                                                </span>
                                            </div>

                                            <h5 class="fw-bold mb-1 position-relative z-1">Delete Confirmation</h5>
                                            <p class="mb-3 position-relative z-1">
                                                Are you sure you want to delete <strong id="delete-patient-name">this
                                                    patient</strong>?
                                            </p>

                                            <div class="d-flex justify-content-center">
                                                <a href="javascript:void(0);"
                                                    class="btn btn-light position-relative z-1 me-3"
                                                    data-bs-dismiss="modal">Cancel</a>

                                                <!-- ✅ PURE LARAVEL FORM - No JavaScript needed for logic -->
                                                <form action="{{ route('patients.destroy', $patient->id) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger position-relative z-1">Yes,
                                                        Delete</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- End Delete Modal -->
                        @endforeach
                    </tbody>
                </table>
            </div>
            <!--  End Table -->

        </div>
        <!-- End Content -->

        @include('components.copyright')

    </div>

    <!-- ========================
                       End Page Content
                      ========================= -->

    <!-- Start Delete Modal  -->
    <div class="modal fade" id="delete_modal">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content">
                <div class="modal-body text-center position-relative">
                    <img src="assets/img/bg/delete-modal-bg-01.png" alt=""
                        class="img-fluid position-absolute top-0 start-0 z-0">
                    <img src="assets/img/bg/delete-modal-bg-02.png" alt=""
                        class="img-fluid position-absolute bottom-0 end-0 z-0">
                    <div class="mb-3 position-relative z-1">
                        <span class="avatar avatar-lg bg-danger text-white"><i class="ti ti-trash fs-24"></i></span>
                    </div>
                    <h5 class="fw-bold mb-1 position-relative z-1">Delete Confirmation</h5>
                    <p class="mb-3 position-relative z-1">Are you sure want to delete?</p>
                    <div class="d-flex justify-content-center">
                        <a href="javascript:void(0);" class="btn btn-light position-relative z-1 me-3"
                            data-bs-dismiss="modal">Cancel</a>
                        <a href="" class="btn btn-danger position-relative z-1" data-bs-dismiss="modal">Yes,
                            Delete</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Delete Modal  -->
    <!-- Assign Medicine Modals (One per patient) -->
    @foreach ($patients as $patient)
        <div class="modal fade" id="assignModal{{ $patient->id }}" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <form action="{{ route('patients.medicines.assign-group', $patient->id) }}" method="POST">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title">
                                <i class="ti ti-pills me-2"></i>Assign Medicines
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <p class="text-muted mb-3">
                                Select a group to auto-assign all medicines to <strong>{{ $patient->first_name }}
                                    {{ $patient->last_name }}</strong>
                            </p>

                            <div class="mb-3">
                                <label class="form-label fw-medium">Medicine Group <span
                                        class="text-danger">*</span></label>
                                <select name="medicine_group_id" class="select" required>
                                    <option value="">Select group...</option>
                                    @foreach ($medicineGroups as $group)
                                        <option value="{{ $group->id }}">
                                            {{ $group->name }}
                                            @if ($group->code)
                                                <span class="text-muted">({{ $group->code }})</span>
                                            @endif
                                            - {{ $group->medicines_count }} medicines
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Start Date</label>
                                    <input type="date" name="start_date" class="form-control">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">End Date</label>
                                    <input type="date" name="end_date" class="form-control">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Notes</label>
                                <textarea name="notes" class="form-control" rows="2" placeholder="Optional instructions..."></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">
                                <i class="ti ti-check me-1"></i> Assign Group
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach
    <!-- Success/Error Messages -->
    <div id="assignMessage"></div>
@endsection
