@extends('layout.master')
@section('content')

<div class="page-wrapper">
    <div class="content">

        <!-- Back Link -->
        <h6 class="fs-14 mb-3">
            <a href="{{ route('roles.index') }}"><i class="ti ti-chevron-left me-1"></i>Roles</a>
        </h6>

        <!-- Page Header with Role Switcher -->
        <div class="d-flex align-items-sm-center flex-sm-row flex-column gap-2 mb-3 pb-3 border-bottom">
            <div class="flex-grow-1">
                <h4 class="fw-bold mb-0">Assign Permissions</h4>
            </div>
            <div class="text-end d-flex">
                <div class="dropdown">
                    <a href="javascript:void(0);" class="dropdown-toggle btn bg-white btn-md d-inline-flex align-items-center fw-normal rounded border text-dark px-2 py-1 fs-14" data-bs-toggle="dropdown">
                       <span class="text-body me-1">Role : </span> {{ $role->name }}
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end p-2">
                        @foreach(\App\Models\Role::orderBy('name')->get() as $r)
                        <li>
                            <a href="{{ route('roles.permissions.manage', $r) }}" class="dropdown-item rounded-1 {{ $r->id === $role->id ? 'active' : '' }}">
                                {{ $r->name }}
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>

        <!-- Alerts -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Permissions Form -->
        <form action="{{ route('roles.permissions.assign', $role) }}" method="POST" id="permissionsForm">
            @csrf

            @forelse($permissions as $groupName => $groupPerms)
            <div class="card mb-3">
                <div class="card-header">
                    <div class="d-flex align-items-center justify-content-between">
                        <h6 class="fw-bold mb-0">{{ $groupName ?: 'General' }}</h6>
                        <div class="form-check form-check-md">
                            <input class="form-check-input select-all-group" type="checkbox" data-group-index="{{ $loop->index }}">
                            <label class="form-check-label">Allow All</label>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive border">
                        <table class="table table-nowrap mb-0">
                            <thead class="thead-light">
                                <tr>
                                    <th>Permission</th>
                                    <th class="text-center" width="120">Assign</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($groupPerms as $perm)
                                <tr>
                                    <td>
                                        <p class="fw-medium text-dark mb-0">{{ ucwords(str_replace('_', ' ', $perm->name)) }}</p>
                                        <small class="text-muted">{{ $perm->name }}</small>
                                    </td>
                                    <td class="text-center">
                                        <div class="form-check form-check-md d-inline-block">
                                            <input class="form-check-input perm-check" 
                                                   type="checkbox" 
                                                   name="permissions[]" 
                                                   value="{{ $perm->name }}" 
                                                   data-group-index="{{ $loop->parent->index }}" 
                                                   {{ in_array($perm->name, $rolePermissions) ? 'checked' : '' }}>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @empty
            <div class="alert alert-info text-center py-4">
                <i class="ti ti-info-circle me-2 fs-5"></i> No permissions available. 
                <a href="{{ route('permissions.index') }}" class="fw-bold">Create permissions</a> first.
            </div>
            @endforelse

            <!-- Action Buttons -->
            <div class="d-flex justify-content-end gap-2 mt-3">
                <a href="{{ route('roles.index') }}" class="btn btn-white border">Cancel</a>
                <button type="submit" class="btn btn-primary">
                    <i class="ti ti-check me-1"></i> Save Permissions
                </button>
            </div>
        </form>

    </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // ✅ Select All per group
    document.querySelectorAll('.select-all-group').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const groupIndex = this.dataset.groupIndex;
            const isChecked = this.checked;
            document.querySelectorAll(`.perm-check[data-group-index="${groupIndex}"]`).forEach(perm => {
                perm.checked = isChecked;
            });
        });
    });

    // ✅ Update "Select All" state when individual checkboxes change
    document.querySelectorAll('.perm-check').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const groupIndex = this.dataset.groupIndex;
            const allPermsInGroup = document.querySelectorAll(`.perm-check[data-group-index="${groupIndex}"]`);
            const selectAllCheckbox = document.querySelector(`.select-all-group[data-group-index="${groupIndex}"]`);
            selectAllCheckbox.checked = Array.from(allPermsInGroup).every(c => c.checked);
        });
    });
});
</script>
@endpush