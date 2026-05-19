@extends('layout.master')

@section('content')
<div class="page-wrapper">
    <div class="content">
        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h6 class="fw-bold mb-0 d-flex align-items-center">
                <!-- Route: medicine-groups.index -->
                <a href="{{ route('medicine-groups.index') }}" class="text-dark">
                    <i class="ti ti-chevron-left me-1"></i>Medicine Groups
                </a>
                <span class="mx-2">/</span>
                <!-- FIXED: Changed $group to $medicineGroup -->
                <span class="text-primary">{{ $medicineGroup->name }}</span>
            </h6>
            <div>
                <!-- FIXED: Use medicines.create with group ID query param -->
                <a href="{{ route('medicines.create', ['group' => $medicineGroup->id]) }}" class="btn btn-primary">
                    <i class="ti ti-plus me-1"></i> Add Medicine
                </a>
                <!-- FIXED: Use medicine-groups.edit and $medicineGroup -->
                <a href="{{ route('medicine-groups.edit', $medicineGroup) }}" class="btn btn-light">
                    <i class="ti ti-edit me-1"></i> Edit Group
                </a>
            </div>
        </div>

        <!-- Group Details Card -->
        <div class="card mb-4">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="fw-bold mb-3">
                            <i class="ti ti-info-circle me-2"></i>Group Details
                        </h6>
                        <dl class="row mb-0">
                            <dt class="col-sm-4 text-muted">Name</dt>
                            <dd class="col-sm-8 fw-medium">{{ $medicineGroup->name }}</dd>
                            
                            <dt class="col-sm-4 text-muted">Code</dt>
                            <dd class="col-sm-8">
                                @if($medicineGroup->code)
                                    <span class="badge bg-light text-dark">{{ $medicineGroup->code }}</span>
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </dd>
                            
                            <dt class="col-sm-4 text-muted">Description</dt>
                            <dd class="col-sm-8">{{ $medicineGroup->description ?? 'N/A' }}</dd>
                            
                            <dt class="col-sm-4 text-muted">Status</dt>
                            <dd class="col-sm-8">
                                <span class="badge bg-{{ $medicineGroup->is_active ? 'success' : 'secondary' }}">
                                    {{ $medicineGroup->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </dd>
                            
                            <dt class="col-sm-4 text-muted">Total Medicines</dt>
                            <dd class="col-sm-8">
                                <span class="badge bg-primary">{{ $medicineGroup->medicines->count() }}</span>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Medicines Table -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="fw-bold mb-0">
                    <i class="ti ti-pills me-2"></i>Medicines ({{ $medicineGroup->medicines->count() }})
                </h6>
            </div>
            <div class="card-body">
                @if($medicineGroup->medicines->count())
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th width="50">S.No</th>
                                <th>Medicine Name</th>
                                <th>Dosage</th>
                                <th>Quantity</th>
                                <th>Instructions</th>
                                <th>Route</th>
                                <th width="100">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- FIXED: Loop through $medicineGroup->medicines -->
                            @foreach($medicineGroup->medicines as $medicine)
                            <tr>
                                <td>{{ $medicine->sort_order ?? $loop->iteration }}</td>
                                <td class="fw-medium">{{ $medicine->name }}</td>
                                <td>
                                    @if($medicine->dosage)
                                        <span class="badge bg-light text-dark">{{ $medicine->dosage }}</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>{{ $medicine->quantity ?? '-' }}</td>
                                <td>
                                    <small class="text-muted">{{ $medicine->instructions ?? '-' }}</small>
                                </td>
                                <td>
                                    @if($medicine->route)
                                        <span class="badge bg-info text-dark">{{ $medicine->route }}</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <!-- Route: medicines.edit -->
                                        <a href="{{ route('medicines.edit', $medicine) }}" 
                                           class="btn btn-light" 
                                           title="Edit">
                                            <i class="ti ti-edit"></i>
                                        </a>
                                        <!-- Delete Button -->
                                        <button onclick="confirmDeleteMedicine({{ $medicine->id }}, '{{ $medicine->name }}')" 
                                                class="btn btn-light text-danger" 
                                                title="Delete">
                                            <i class="ti ti-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="text-center py-5 text-muted">
                    <i class="ti ti-pill fs-1 mb-3 d-block"></i>
                    <p>No medicines added to this group yet.</p>
                    <!-- FIXED: Use medicines.create with group ID query param -->
                    <a href="{{ route('medicines.create', ['group' => $medicineGroup->id]) }}" class="btn btn-primary">
                        <i class="ti ti-plus me-1"></i> Add First Medicine
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
function confirmDeleteMedicine(id, name) {
    Swal.fire({
        title: 'Delete Medicine?',
        html: `Are you sure you want to delete <strong>${name}</strong>?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete',
        cancelButtonText: 'Cancel',
        confirmButtonColor: '#dc3545'
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.createElement('form');
            form.method = 'POST';
            // Note: If your routes have an '/admin/' prefix, change this to '/admin/medicines/${id}'
            form.action = `/medicines/${id}`;
            form.innerHTML = `@csrf @method('DELETE')`;
            document.body.appendChild(form);
            form.submit();
        }
    });
}
</script>
@endsection