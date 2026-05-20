@extends('layout.master')

@section('content')
<div class="page-wrapper">
    <div class="content">

        <!-- Page Header -->
        <div class="d-flex align-items-sm-center flex-sm-row flex-column gap-2 mb-3 pb-3 border-bottom">
            <div class="flex-grow-1">
                <h4 class="fw-bold mb-0">Roles</h4>
            </div>
            <div class="text-end d-flex">
                <a href="javascript:void(0);" class="btn btn-primary ms-2 fs-13 btn-md" data-bs-toggle="modal"
                    data-bs-target="#add_role">
                    <i class="ti ti-plus me-1"></i>New Role
                </a>
            </div>
        </div>
        <!-- End Page Header -->

        <!-- SweetAlert Messages -->
        @if (session('success'))
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: @json(session('success')),
                        timer: 3000,
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

        <!-- Roles Table -->
        <div class="table-responsive">
            <table class="table table-nowrap datatable">
                <thead class="thead-light">
                    <tr>
                        <th>Role</th>
                        <th>Created On</th>
                        <th>Status</th>
                        <th>Permissions</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($roles as $role)
                        <tr>
                            <td class="fw-medium">{{ $role->name }}</td>
                            <td>{{ $role->created_at?->format('d M Y') ?? '-' }}</td>
                            <td>
                                <span class="badge badge-soft-{{ $role->status ? 'success' : 'danger' }} 
                                      border border-{{ $role->status ? 'success' : 'danger' }} 
                                      px-2 py-1 fs-13 fw-medium">
                                    {{ $role->status ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td>
                                <!-- ✅ Permissions Button - Modal Open Karega -->
                                <button type="button" 
                                        class="btn btn-sm btn-primary"
                                        data-bs-toggle="modal"
                                        data-bs-target="#permissionsModal{{ $role->id }}">
                                    <i class="ti ti-shield-half me-1"></i>Manage Permissions
                                </button>
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
                                                class="dropdown-item d-flex align-items-center" 
                                                data-bs-toggle="modal"
                                                data-bs-target="#edit_role_{{ $role->id }}">
                                                <i class="ti ti-edit me-2"></i>Edit
                                            </a>
                                        </li>
                                        <li>
                                            <a href="javascript:void(0);"
                                                class="dropdown-item d-flex align-items-center text-danger"
                                                data-bs-toggle="modal" 
                                                data-bs-target="#delete_role{{ $role->id }}">
                                                <i class="ti ti-trash me-2"></i>Delete
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>

                        <!-- ✅ Permissions Modal - Har Role Ke Liye -->
                        @include('pages.user-management.role-permissions-modal')

                        <!-- Edit Role Modal -->
                        <div id="edit_role_{{ $role->id }}" class="modal fade" tabindex="-1">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="text-dark modal-title fw-bold">Edit Role</h4>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <form action="{{ route('roles.update', $role->id) }}" method="POST">
                                        @csrf @method('PUT')
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label class="form-label">Role Name<span class="text-danger ms-1">*</span></label>
                                                <input type="text" name="name" class="form-control" 
                                                       value="{{ old('name', $role->name) }}" required>
                                            </div>
                                            <div class="mb-0">
                                                <label class="form-label">Status<span class="text-danger ms-1">*</span></label>
                                                <select name="status" class="select" required>
                                                    <option value="1" {{ old('status', $role->status) == '1' ? 'selected' : '' }}>Active</option>
                                                    <option value="0" {{ old('status', $role->status) == '0' ? 'selected' : '' }}>Inactive</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-white border" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-primary">Save Changes</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Delete Role Modal -->
                        <div class="modal fade" id="delete_role{{ $role->id }}">
                            <div class="modal-dialog modal-dialog-centered modal-sm">
                                <div class="modal-content">
                                    <div class="modal-body text-center">
                                        <div class="mb-3">
                                            <span class="avatar avatar-lg bg-danger text-white rounded-circle">
                                                <i class="ti ti-trash fs-24"></i>
                                            </span>
                                        </div>
                                        <h5 class="fw-bold mb-1">Delete Confirmation</h5>
                                        <p class="mb-3">Are you sure you want to delete <strong>{{ $role->name }}</strong>?</p>
                                        <form action="{{ route('roles.destroy', $role->id) }}" method="POST">
                                            @csrf @method('DELETE')
                                            <div class="d-flex justify-content-center gap-2">
                                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-danger">Yes, Delete</button>
                                            </div>
                                        </form>
                                    </div>
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

<!-- Add Role Modal -->
<div id="add_role" class="modal fade">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="text-dark modal-title fw-bold">New Role</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('roles.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Role Name<span class="text-danger ms-1">*</span></label>
                        <input type="text" name="name" class="form-control" placeholder="e.g., Doctor, Nurse"
                            required value="{{ old('name') }}">
                    </div>
                    <div class="mb-0">
                        <label class="form-label">Status<span class="text-danger ms-1">*</span></label>
                        <select name="status" class="select" required>
                            <option value="1" {{ old('status') == '1' ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-white border" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add New Role</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection