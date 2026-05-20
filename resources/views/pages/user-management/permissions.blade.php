@extends('layout.master')
@section('content')
    <div class="page-wrapper">
        <div class="content">

            <!-- Page Header -->
            <div class="d-flex align-items-sm-center flex-sm-row flex-column gap-2 mb-3 pb-3 border-bottom">
                <div class="flex-grow-1">
                    <h4 class="fw-bold mb-0">Permissions</h4>
                </div>
                <div class="text-end d-flex">
                    <a href="javascript:void(0);" class="btn btn-primary ms-2 fs-13 btn-md" data-bs-toggle="modal"
                        data-bs-target="#add_permission">
                        <i class="ti ti-plus me-1"></i>New Permission
                    </a>
                </div>
            </div>
            <!-- End Page Header -->
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

            <div class="table-responsive">
                <table class="table datatable table-nowrap">
                    <thead class="thead-light">
                        <tr>
                            <th>Permission</th>
                            <th>Group</th>
                            <th>Guard</th>
                            <th>Created On</th>
                            <th>Status</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($permissions as $permission)
                            <tr>
                                <td class="fw-medium">{{ $permission->name }}</td>
                                <td>
                                    @if ($permission->group_name)
                                        <span class="badge badge-soft-info border border-info px-2 py-1 fs-12">
                                            {{ $permission->group_name }}
                                        </span>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td><small class="text-muted">{{ $permission->guard_name }}</small></td>
                                <td>{{ $permission->created_at ? $permission->created_at->format('d M Y') : '-' }}</td>
                                <td>
                                    <span
                                        class="badge badge-soft-{{ $permission->status ? 'success' : 'danger' }} 
                                         border border-{{ $permission->status ? 'success' : 'danger' }} 
                                         px-2 py-1 fs-13 fw-medium">
                                        {{ $permission->status ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-1">
                                        <a href="javascript:void(0);"
                                            class="shadow-sm fs-14 d-inline-flex border rounded-2 p-1 me-1"
                                            data-bs-toggle="dropdown">
                                            <i class="ti ti-dots-vertical"></i>
                                        </a>
                                        <ul class="dropdown-menu p-2">
                                            <li>
                                                <a href="javascript:void(0);"
                                                    class="dropdown-item d-flex align-items-center" data-bs-toggle="modal"
                                                    data-bs-target="#edit_permission{{ $permission->id }}">
                                                    <i class="ti ti-edit me-2"></i>Edit
                                                </a>
                                            </li>
                                            <li>
                                                <a href="javascript:void(0);"
                                                    class="dropdown-item d-flex align-items-center text-danger"
                                                    onclick='populateDeleteModal(@json($permission))'
                                                    data-bs-toggle="modal" data-bs-target="#delete_permission">
                                                    <i class="ti ti-trash me-2"></i>Delete
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            <!-- Start Edit Permission Modal -->
                            <div id="edit_permission{{ $permission->id }}" class="modal fade">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="text-dark modal-title fw-bold">Edit Permission</h4>
                                            <button type="button" class="btn-close btn-close-modal custom-btn-close"
                                                data-bs-dismiss="modal" aria-label="Close">
                                                <i class="ti ti-x"></i>
                                            </button>
                                        </div>
                                        <form action="{{ route('permissions.update', $permission->id) }}" method="POST">
                                            @csrf @method('PUT')
                                            <div class="modal-body">

                                                <!-- Permission Name -->
                                                <div class="mb-3">
                                                    <label class="form-label">Permission Name<span
                                                            class="text-danger ms-1">*</span></label>
                                                    <input type="text" name="name"
                                                        value="{{ old('name', $permission->name) }}" class="form-control"
                                                        required>
                                                    @error('name')
                                                        <small class="text-danger">{{ $message }}</small>
                                                    @enderror
                                                </div>

                                                <!-- ✅ Assign to Roles Section -->
                                                <div class="mb-3">
                                                    <label class="form-label">Assign to Roles <small
                                                            class="text-muted">(Optional)</small></label>
                                                    <div class="border rounded p-2"
                                                        style="max-height: 200px; overflow-y: auto;">
                                                        @foreach ($allRoles as $roleItem)
                                                            <div class="form-check">
                                                                <input type="checkbox" name="roles[]"
                                                                    value="{{ $roleItem->name }}"
                                                                    id="role_{{ $permission->id }}_{{ $roleItem->id }}"
                                                                    class="form-check-input"
                                                                    {{ $permission->roles->contains('name', $roleItem->name) ? 'checked' : '' }}>
                                                                <label
                                                                    for="role_{{ $permission->id }}_{{ $roleItem->id }}"
                                                                    class="form-check-label">
                                                                    {{ $roleItem->name }}
                                                                </label>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>

                                                <!-- Group Name - ✅ Fixed: group_name instead of 'name' -->
                                                <div class="mb-3">
                                                    <label class="form-label">Group <small
                                                            class="text-muted">(Optional)</small></label>
                                                    <input type="text" name="group_name"
                                                        value="{{ old('group_name', $permission->group_name) }}"
                                                        class="form-control">
                                                </div>

                                                <!-- Guard Name - ✅ Fixed: comparison with 'web'/'api' instead of '1' -->
                                                <div class="mb-3">
                                                    <label class="form-label">Guard</label>
                                                    <select name="guard_name" class="select">
                                                        <option value="web"
                                                            {{ old('guard_name', $permission->guard_name) == 'web' ? 'selected' : '' }}>
                                                            Web
                                                        </option>
                                                        <option value="api"
                                                            {{ old('guard_name', $permission->guard_name) == 'api' ? 'selected' : '' }}>
                                                            API
                                                        </option>
                                                    </select>
                                                </div>

                                                <!-- Status -->
                                                <div class="mb-0">
                                                    <label class="form-label">Status<span
                                                            class="text-danger ms-1">*</span></label>
                                                    <select name="status" class="select" required>
                                                        <option value="1"
                                                            {{ old('status', $permission->status) == '1' ? 'selected' : '' }}>
                                                            Active</option>
                                                        <option value="0"
                                                            {{ old('status', $permission->status) == '0' ? 'selected' : '' }}>
                                                            Inactive</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="modal-footer d-flex align-items-center gap-1">
                                                <button type="button" class="btn btn-white border"
                                                    data-bs-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-primary">Save Changes</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>
        @include('components.copyright')
    </div>

    <!-- ========================
                 Modals Section
            ========================== -->

    <!-- Start Add Permission Modal -->
    <div id="add_permission" class="modal fade">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="text-dark modal-title fw-bold">New Permission</h4>
                    <button type="button" class="btn-close btn-close-modal custom-btn-close" data-bs-dismiss="modal"
                        aria-label="Close">
                        <i class="ti ti-x"></i>
                    </button>
                </div>
                <form action="{{ route('permissions.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <!-- Permission Name -->
                        <div class="mb-3">
                            <label class="form-label">Permission Name<span class="text-danger ms-1">*</span></label>
                            <input type="text" name="name" class="form-control"
                                placeholder="e.g., view reports, manage settings" required value="{{ old('name') }}">
                            @error('name')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <!-- Group Name (Optional) -->
                        <div class="mb-3">
                            <label class="form-label">Group <small class="text-muted">(Optional)</small></label>
                            <input type="text" name="group_name" class="form-control"
                                placeholder="e.g., Users, Reports, Settings" value="{{ old('group_name') }}">
                            <small class="text-muted">Used to organize permissions in lists</small>
                        </div>

                        <!-- Guard Name -->
                        <div class="mb-3">
                            <label class="form-label">Guard</label>
                            <select name="guard_name" class="select select">
                                <option value="web" {{ old('guard_name') == 'web' ? 'selected' : '' }}>Web</option>
                                <option value="api" {{ old('guard_name') == 'api' ? 'selected' : '' }}>API</option>
                            </select>
                        </div>

                        <!-- Status -->
                        <div class="mb-0">
                            <label class="form-label">Status<span class="text-danger ms-1">*</span></label>
                            <select name="status" class="select select" required>
                                <option value="1" {{ old('status') == '1' ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer d-flex align-items-center gap-1">
                        <button type="button" class="btn btn-white border" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Add Permission</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- End Add Permission Modal -->



    <!-- Start Delete Permission Modal -->
    <div class="modal fade" id="delete_permission">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content">
                <div class="modal-body text-center position-relative z-1">
                    <img src="assets/img/bg/delete-modal-bg-01.png" alt=""
                        class="img-fluid position-absolute top-0 start-0 z-n1">
                    <img src="assets/img/bg/delete-modal-bg-02.png" alt=""
                        class="img-fluid position-absolute bottom-0 end-0 z-n1">
                    <div class="mb-3">
                        <span class="avatar avatar-lg bg-danger text-white">
                            <i class="ti ti-trash fs-24"></i>
                        </span>
                    </div>
                    <h5 class="fw-bold mb-1">Delete Confirmation</h5>
                    <p class="mb-3">Are you sure you want to delete <strong id="delete_permission_name"></strong>?</p>
                    <p class="mb-3 small text-muted">This action cannot be undone.</p>
                    <form id="deletePermissionForm" method="POST">
                        @csrf
                        @method('DELETE')
                        <div class="d-flex justify-content-center gap-2">
                            <button type="button" class="btn btn-light position-relative z-1"
                                data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-danger position-relative z-1">Yes, Delete</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- End Delete Permission Modal -->
@endsection
