@extends('layout.master')

@section('content')
<div class="page-wrapper">
    <div class="content">
        <!-- Page Header -->
        <div class="mb-4">
            <h6 class="fw-bold mb-0 d-flex align-items-center">
                <a href="{{ route('medicine-groups.index') }}" class="text-dark">
                    <i class="ti ti-chevron-left me-1"></i>Medicine Groups
                </a>
                <span class="mx-2">/</span>
                <span class="text-primary">Add New Group</span>
            </h6>
        </div>

        <!-- Validation Errors -->
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

        <form action="{{ route('medicine-groups.store') }}" method="POST">
            @csrf
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="fw-bold mb-0">
                                <i class="ti ti-pill me-2"></i>Group Information
                            </h6>
                        </div>
                        <div class="card-body">
                            <!-- Group Name -->
                            <div class="mb-3">
                                <label class="form-label fw-medium">Group Name <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control @error('name') is-invalid @enderror" 
                                       name="name" 
                                       value="{{ old('name') }}" 
                                       placeholder="e.g., CA BLOOD, AUTISM-1, CKD-1" 
                                       required 
                                       autofocus>
                                @error('name')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                                <small class="text-muted">Enter the medicine group name</small>
                            </div>

                            <!-- Group Code -->
                            <div class="mb-3">
                                <label class="form-label fw-medium">Group Code</label>
                                <input type="text" 
                                       class="form-control @error('code') is-invalid @enderror" 
                                       name="code" 
                                       value="{{ old('code') }}" 
                                       placeholder="e.g., GD-4, CKD, GD-3">
                                @error('code')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                                <small class="text-muted">Optional short code for the group</small>
                            </div>

                            <!-- Description -->
                            <div class="mb-3">
                                <label class="form-label fw-medium">Description</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" 
                                          name="description" 
                                          rows="3" 
                                          placeholder="Enter group description...">{{ old('description') }}</textarea>
                                @error('description')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Status -->
                            <div class="mb-3">
                                <label class="form-label fw-medium">Status</label>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" 
                                           type="checkbox" 
                                           name="is_active" 
                                           id="is_active" 
                                           {{ old('is_active', true) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active">
                                        Active (Group will be visible in the system)
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-end">
                            <a href="{{ route('medicine-groups.index') }}" class="btn btn-light me-2">
                                <i class="ti ti-x me-1"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="ti ti-check me-1"></i> Create Group
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection