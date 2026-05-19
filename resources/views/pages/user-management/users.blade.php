@extends('layout.master')
@section('content')
    <div class="page-wrapper">
        <div class="content">

            <!-- Start Page Header -->
            <div class="d-flex align-items-sm-center flex-sm-row flex-column gap-2 mb-3 pb-3 border-bottom">
                <div class="flex-grow-1">
                    <h4 class="fw-bold mb-0">Users</h4>
                </div>
                <div class="text-end d-flex">
                    <a href="javascript:void(0);" class="btn btn-primary ms-2 fs-13 btn-md" data-bs-toggle="modal"
                        data-bs-target="#add_user">
                        <i class="ti ti-plus me-1"></i>New User
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
            <!-- Users Table -->
            <div class="table-responsive">
                <table class="table table-nowrap">
                    <thead class="thead-light">
                        <tr>
                            <th>User</th>
                            <th>Role</th>
                            <th>Phone</th>
                            <th>Status</th>
                            <th>Joined</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div
                                            class="avatar avatar-md me-2 bg-light text-dark d-flex align-items-center justify-content-center overflow-hidden">
                                            @if ($user->profile_photo)
                                                <img src="{{ asset('storage/' . $user->profile_photo) }}" alt="Photo"
                                                    class="img-fluid" style="width: 40px; height: 40px; object-fit: cover;">
                                            @else
                                                <i class="ti ti-user fs-20"></i>
                                            @endif
                                        </div>
                                        <div>
                                            <p class="fw-medium text-dark mb-0">{{ $user->name }}</p>
                                            <small class="text-muted">{{ $user->email }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @if ($user->roles->isNotEmpty())
                                        <span class="badge badge-soft-primary border border-primary px-2 py-1 fs-12">
                                            {{ $user->roles->first()->name }}
                                        </span>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td>{{ $user->phone ?? '—' }}</td>
                                <td>
                                    <span
                                        class="badge badge-soft-{{ $user->status ? 'success' : 'danger' }} 
                                         border border-{{ $user->status ? 'success' : 'danger' }} 
                                         px-2 py-1 fs-13 fw-medium">
                                        {{ $user->status ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td>{{ $user->created_at ? $user->created_at->format('d M Y') : '—' }}</td>
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
                                                    data-bs-target="#edit_user{{ $user->id }}">
                                                    <i class="ti ti-edit me-2"></i>Edit
                                                </a>
                                            </li>
                                            <li>
                                                <a href="javascript:void(0);"
                                                    class="dropdown-item d-flex align-items-center text-danger"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#delete_user{{ $user->id }}">
                                                    <i class="ti ti-trash me-2"></i>Delete
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            <!-- Start Edit User Modal -->
                            <div id="edit_user{{ $user->id }}" class="modal fade">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="text-dark modal-title fw-bold">Edit User</h4>
                                            <button type="button" class="btn-close btn-close-modal custom-btn-close"
                                                data-bs-dismiss="modal" aria-label="Close"><i class="ti ti-x"></i></button>
                                        </div>
                                        <form action="{{ route('users.update', $user->id) }}" method="POST"
                                            enctype="multipart/form-data">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label class="form-label">Name<span
                                                            class="text-danger ms-1">*</span></label>
                                                    <input type="text" name="name"
                                                        value="{{ old('name', $user->name) }}" class="form-control"
                                                        required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Email<span
                                                            class="text-danger ms-1">*</span></label>
                                                    <input type="email" name="email"
                                                        value="{{ old('email', $user->email) }}" class="form-control"
                                                        required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Phone</label>
                                                    <input type="text" name="phone"
                                                        value="{{ old('phone', $user->phone) }}" class="form-control"
                                                        maxlength="20">
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Role<span
                                                            class="text-danger ms-1">*</span></label>
                                                    <select name="role" id="edit_role" class="select select"
                                                        required>
                                                        @foreach ($roles as $role)
                                                            <option value="{{ $role->name }}">{{ $role->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">New Password <small class="text-muted">(Leave
                                                            blank to keep current)</small></label>
                                                    <input type="password" name="password" class="form-control"
                                                        placeholder="Min 8 characters">
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Confirm Password</label>
                                                    <input type="password" name="password_confirmation" class="form-control"
                                                        placeholder="Re-enter new password">
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Status<span
                                                            class="text-danger ms-1">*</span></label>
                                                    <select name="status" class="select" required>
                                                        <option value="1"
                                                            {{ old('status', $user->status) == '1' ? 'selected' : '' }}>
                                                            Active</option>
                                                        <option value="0"
                                                            {{ old('status', $user->status) == '0' ? 'selected' : '' }}>
                                                            Inactive</option>
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Current Photo</label>
                                                    <div class="d-flex align-items-center gap-2">
                                                        <img id="edit_photo_preview" src=""
                                                            class="rounded-circle border"
                                                            style="width: 50px; height: 50px; object-fit: cover;">
                                                        <span id="edit_photo_name" class="text-muted small">No
                                                            photo</span>
                                                    </div>
                                                </div>
                                                <div class="mb-0">
                                                    <label class="form-label">Change Photo</label>
                                                    <input type="file" name="profile_photo" id="edit_profile_photo"
                                                        class="form-control" accept="image/png, image/jpeg, image/gif">
                                                    <small class="text-muted">Max 800KB • JPG, JPEG, PNG, GIF</small>
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
                            <!-- End Edit User Modal -->

                            <!-- Start Delete User Modal -->
                            <div class="modal fade" id="delete_user{{ $user->id }}">
                                <div class="modal-dialog modal-dialog-centered modal-sm">
                                    <div class="modal-content">
                                        <div class="modal-body text-center position-relative z-1">
                                            <img src="assets/img/bg/delete-modal-bg-01.png" alt=""
                                                class="img-fluid position-absolute top-0 start-0 z-n1">
                                            <img src="assets/img/bg/delete-modal-bg-02.png" alt=""
                                                class="img-fluid position-absolute bottom-0 end-0 z-n1">
                                            <div class="mb-3">
                                                <span class="avatar avatar-lg bg-danger text-white"><i
                                                        class="ti ti-trash fs-24"></i></span>
                                            </div>
                                            <h5 class="fw-bold mb-1">Delete Confirmation</h5>
                                            <p class="mb-3">Are you sure you want to delete <strong
                                                    id="delete_user_name"></strong>?</p>
                                            <p class="mb-3 small text-muted">This action cannot be undone.</p>
                                            <form action="{{ route('users.destroy', $user->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <div class="d-flex justify-content-center gap-2">
                                                    <button type="button" class="btn btn-light position-relative z-1"
                                                        data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit"
                                                        class="btn btn-danger position-relative z-1">Yes, Delete</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- End Delete User Modal -->
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

    <!-- Start Add User Modal -->
    <div id="add_user" class="modal fade">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="text-dark modal-title fw-bold">New User</h4>
                    <button type="button" class="btn-close btn-close-modal custom-btn-close" data-bs-dismiss="modal"
                        aria-label="Close"><i class="ti ti-x"></i></button>
                </div>
                <form action="{{ route('users.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Name<span class="text-danger ms-1">*</span></label>
                            <input type="text" name="name" class="form-control" placeholder="Full name" required
                                value="{{ old('name') }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email<span class="text-danger ms-1">*</span></label>
                            <input type="email" name="email" class="form-control" placeholder="Email address"
                                required value="{{ old('email') }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Phone</label>
                            <input type="text" name="phone" class="form-control" placeholder="Phone number"
                                maxlength="20" value="{{ old('phone') }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Role<span class="text-danger ms-1">*</span></label>
                            <select name="role" class="select select" required>
                                <option value="">Select Role</option>
                                @foreach ($roles as $role)
                                    <option value="{{ $role->name }}"
                                        {{ old('role') == $role->name ? 'selected' : '' }}>{{ $role->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Password<span class="text-danger ms-1">*</span></label>
                            <input type="password" name="password" class="form-control" placeholder="Min 8 characters"
                                required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Confirm Password<span class="text-danger ms-1">*</span></label>
                            <input type="password" name="password_confirmation" class="form-control"
                                placeholder="Re-enter password" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Status<span class="text-danger ms-1">*</span></label>
                            <select name="status" class="select select" required>
                                <option value="1"
                                    {{ old('status') === '1' || old('status') === null ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ old('status') === '0' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>
                        <div class="mb-0">
                            <label class="form-label">Profile Photo</label>
                            <input type="file" name="profile_photo" class="form-control"
                                accept="image/png, image/jpeg, image/gif">
                            <small class="text-muted">Max 800KB • JPG, JPEG, PNG, GIF</small>
                        </div>
                    </div>
                    <div class="modal-footer d-flex align-items-center gap-1">
                        <button type="button" class="btn btn-white border" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Create User</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- End Add User Modal -->
@endsection
