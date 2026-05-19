@extends('layout.master')

@section('content')
<div class="page-wrapper">
    <div class="content">
        <!-- Page Header -->
        <div class="mb-4">
            <h6 class="fw-bold mb-0 d-flex align-items-center">
                <!-- FIXED: Access group via relationship -->
                <a href="{{ route('medicine-groups.show', $medicine->group) }}" class="text-dark">
                    <i class="ti ti-chevron-left me-1"></i>{{ $medicine->group->name }}
                </a>
                <span class="mx-2">/</span>
                <span class="text-primary">Edit Medicine</span>
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

        <!-- FIXED: Use simple resource route for update -->
        <form action="{{ route('medicines.update', $medicine) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="fw-bold mb-0">
                                <i class="ti ti-pills me-2"></i>Medicine Information
                            </h6>
                        </div>
                        <div class="card-body">
                            <!-- Sort Order -->
                            <div class="mb-3">
                                <label class="form-label fw-medium">Serial Number (S.NO)</label>
                                <input type="number" 
                                       class="form-control @error('sort_order') is-invalid @enderror" 
                                       name="sort_order" 
                                       value="{{ old('sort_order', $medicine->sort_order) }}" 
                                       placeholder="e.g., 1, 2, 3"
                                       min="1">
                                @error('sort_order')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Medicine Name -->
                            <div class="mb-3">
                                <label class="form-label fw-medium">Medicine Name <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control @error('name') is-invalid @enderror" 
                                       name="name" 
                                       value="{{ old('name', $medicine->name) }}" 
                                       placeholder="e.g., G.T-5 (SD5)" 
                                       required 
                                       autofocus>
                                @error('name')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Dosage -->
                            <div class="mb-3">
                                <label class="form-label fw-medium">Dosage</label>
                                <input type="text" 
                                       class="form-control @error('dosage') is-invalid @enderror" 
                                       name="dosage" 
                                       value="{{ old('dosage', $medicine->dosage) }}" 
                                       placeholder="e.g., 3*4, 6*4">
                                @error('dosage')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Quantity -->
                            <div class="mb-3">
                                <label class="form-label fw-medium">Quantity</label>
                                <input type="text" 
                                       class="form-control @error('quantity') is-invalid @enderror" 
                                       name="quantity" 
                                       value="{{ old('quantity', $medicine->quantity) }}" 
                                       placeholder="e.g., 30ML, 50ML">
                                @error('quantity')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Instructions -->
                            <div class="mb-3">
                                <label class="form-label fw-medium">Instructions</label>
                                <textarea class="form-control @error('instructions') is-invalid @enderror" 
                                          name="instructions" 
                                          rows="3" 
                                          placeholder="e.g., AM ONLY, APPLY ON WHOLE SKIN">{{ old('instructions', $medicine->instructions) }}</textarea>
                                @error('instructions')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Route -->
                            <div class="mb-3">
                                <label class="form-label fw-medium">Route of Administration</label>
                                <select class="select @error('route') is-invalid @enderror" name="route">
                                    <option value="">Select Route</option>
                                    <option value="ORAL" {{ old('route', $medicine->route) == 'ORAL' ? 'selected' : '' }}>ORAL</option>
                                    <option value="EXTERNAL" {{ old('route', $medicine->route) == 'EXTERNAL' ? 'selected' : '' }}>EXTERNAL (APPLY)</option>
                                    <option value="GULBULES" {{ old('route', $medicine->route) == 'GULBULES' ? 'selected' : '' }}>GULBULES</option>
                                    <option value="INJECTION" {{ old('route', $medicine->route) == 'INJECTION' ? 'selected' : '' }}>INJECTION</option>
                                    <option value="SIP SIP" {{ old('route', $medicine->route) == 'SIP SIP' ? 'selected' : '' }}>SIP SIP</option>
                                    <option value="EAR PLUG" {{ old('route', $medicine->route) == 'EAR PLUG' ? 'selected' : '' }}>EAR PLUG</option>
                                    <option value="APPLY" {{ old('route', $medicine->route) == 'APPLY' ? 'selected' : '' }}>APPLY</option>
                                </select>
                                @error('route')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="card-footer text-end">
                            <!-- FIXED: Back link uses medicine relationship -->
                            <a href="{{ route('medicine-groups.show', $medicine->group) }}" class="btn btn-light me-2">
                                <i class="ti ti-x me-1"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="ti ti-check me-1"></i> Update Medicine
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection