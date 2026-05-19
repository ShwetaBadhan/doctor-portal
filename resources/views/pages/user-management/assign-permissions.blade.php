@extends('layout.master')
@section('content')

<div class="page-wrapper">
    <div class="content">

        <!-- Back Link -->
        <h6 class="fs-14 mb-3">
            <a href="{{ route('roles.index') }}"><i class="ti ti-chevron-left me-1"></i>Roles</a>
        </h6>

        <!-- Page Header -->
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h4 class="fw-bold mb-0">Assign Permissions - {{ $role->name }}</h4>
            <div class="dropdown">
                <button class="btn btn-light dropdown-toggle" type="button" data-bs-toggle="dropdown">
                    Switch Role: {{ $role->name }}
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    @foreach(\App\Models\Role::orderBy('name')->get() as $r)
                    <li>
                        <a href="{{ route('roles.permissions.manage', $r) }}" 
                           class="dropdown-item {{ $r->id === $role->id ? 'active' : '' }}">
                            {{ $r->name }}
                        </a>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>

        <!-- Alerts -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Single Permissions Table -->
        <form action="{{ route('roles.permissions.assign', $role) }}" method="POST">
            @csrf
            
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="fw-bold mb-0">All Permissions</h6>
                    <div class="form-check form-check-md">
                        <input class="form-check-input" type="checkbox" id="selectAll">
                        <label class="form-check-label fw-medium" for="selectAll">Select All</label>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0 datatable">
                            <thead class="table-light">
                                <tr>
                                    <th width="50">
                                        <div class="form-check form-check-md">
                                            <input class="form-check-input" type="checkbox" id="selectAllHeader">
                                        </div>
                                    </th>
                                    <th>Permission Name</th>
                                    <th>Identifier</th>
                                    <th width="150">Group</th>
                                </tr>
                            </thead>
                           <tbody>
    @foreach($permissions as $permission)
    <tr>
        <td>
            <div class="form-check form-check-md">
                <input class="form-check-input perm-checkbox" 
                       type="checkbox" 
                       name="permissions[]" 
                       value="{{ $permission->name }}"
                       id="perm_{{ $permission->id }}"
                       {{-- ✅ KEY FIX: Check if role has this permission --}}
                       {{ $role->hasPermissionTo($permission->name) ? 'checked' : '' }}>
            </div>
        </td>
        <td>
            <label for="perm_{{ $permission->id }}" class="fw-medium mb-0" style="cursor: pointer;">
                {{ ucwords(str_replace('_', ' ', $permission->name)) }}
            </label>
        </td>
        <td><code class="text-muted small">{{ $permission->name }}</code></td>
        <td>
            <span class="badge bg-light text-dark border">
                {{ $permission->group_name ?? 'General' }}
            </span>
        </td>
    </tr>
    @endforeach
</tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="d-flex justify-content-end gap-2 mt-3">
                <a href="{{ route('roles.index') }}" class="btn btn-light">Cancel</a>
                <button type="submit" class="btn btn-primary">
                    <i class="ti ti-check me-1"></i> Save Permissions
                </button>
            </div>
        </form>

    </div>
</div>


<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectAll = document.getElementById('selectAll');
    const checkboxes = document.querySelectorAll('.perm-checkbox');

    // Initialize Select All state based on pre-checked permissions
    if (selectAll && checkboxes.length > 0) {
        const allChecked = Array.from(checkboxes).every(cb => cb.checked);
        selectAll.checked = allChecked;
    }

    // Select All functionality
    if (selectAll) {
        selectAll.addEventListener('change', function() {
            checkboxes.forEach(cb => cb.checked = this.checked);
        });
    }

    // Update Select All when individual checkboxes change
    checkboxes.forEach(cb => {
        cb.addEventListener('change', function() {
            const allChecked = Array.from(checkboxes).every(c => c.checked);
            if (selectAll) selectAll.checked = allChecked;
        });
    });
});
</script>


@endsection