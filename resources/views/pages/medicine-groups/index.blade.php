@extends('layout.master')

@section('content')
<div class="page-wrapper">
    <div class="content">
        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h6 class="fw-bold mb-0">
                <i class="ti ti-pill me-2 text-primary"></i>Medicine Groups
            </h6>
            <a href="{{ route('medicine-groups.create') }}" class="btn btn-primary">
                <i class="ti ti-plus me-1"></i> Add New Group
            </a>
        </div>

        <!-- Success Message -->
        @if(session('success'))
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: @json(session('success')),
                        timer: 3000,
                        showConfirmButton: false,
                        toast: true,
                        position: 'top-end'
                    });
                });
            </script>
        @endif

        <!-- Groups Table -->
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th width="50">#</th>
                                <th>Group Name</th>
                                <th>Code</th>
                                <th>Description</th>
                                <th width="100">Medicines</th>
                                <th width="100">Status</th>
                                <th width="150">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($groups as $group)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <a href="{{ route('medicine-groups.show', $group) }}" class="text-primary fw-medium">
                                        {{ $group->name }}
                                    </a>
                                </td>
                                <td>
                                    @if($group->code)
                                        <span class="badge bg-light text-dark">{{ $group->code }}</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    <small class="text-muted">{{ Str::limit($group->description, 50) ?? 'N/A' }}</small>
                                </td>
                                <td>
                                    <span class="badge bg-primary">{{ $group->medicines_count ?? 0 }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $group->is_active ? 'success' : 'secondary' }}">
                                        {{ $group->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('medicine-groups.show', $group) }}" class="btn btn-light" title="View Details">
                                            <i class="ti ti-eye"></i>
                                        </a>
                                        <a href="{{ route('medicine-groups.edit', $group) }}" class="btn btn-light" title="Edit">
                                            <i class="ti ti-edit"></i>
                                        </a>
                                        <button onclick="confirmDelete({{ $group->id }}, '{{ $group->name }}')" class="btn btn-light text-danger" title="Delete">
                                            <i class="ti ti-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted">
                                    <i class="ti ti-pill fs-1 mb-3 d-block"></i>
                                    <p>No medicine groups found.</p>
                                    <a href="{{ route('medicine-groups.create') }}" class="btn btn-primary btn-sm">
                                        <i class="ti ti-plus me-1"></i> Create First Group
                                    </a>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                @if($groups->hasPages())
                <div class="mt-3">
                    {{ $groups->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
function confirmDelete(id, name) {
    Swal.fire({
        title: 'Delete Medicine Group?',
        html: `Are you sure you want to delete <strong>${name}</strong>?<br><small class="text-muted">All medicines in this group will also be deleted.</small>`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete',
        cancelButtonText: 'Cancel',
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d'
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/medicine-groups/${id}`;
            form.innerHTML = `@csrf @method('DELETE')`;
            document.body.appendChild(form);
            form.submit();
        }
    });
}
</script>
@endsection