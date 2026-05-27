@props(['medicine', 'patientId', 'index', 'existingAssignment' => null])

<div class="medicine-item card border-0 shadow-sm mb-2" data-medicine-id="{{ $medicine->id }}">
    <div class="card-body py-2 px-3">
        <div class="d-flex align-items-start gap-2">
            <!-- Checkbox -->
            <div class="pt-1">
                <input class="form-check-input medicine-checkbox" 
                       type="checkbox" 
                       name="medicines[{{ $index }}][assign]" 
                       value="1"
                       data-medicine-name="{{ $medicine->name }}"
                       {{ $existingAssignment ? 'checked' : '' }}>
            </div>

            <!-- Medicine Info -->
            <div class="flex-grow-1">
                <h6 class="mb-1 fw-semibold">{{ $medicine->name }}</h6>
                @if($medicine->code)
                    <small class="text-muted">Code: {{ $medicine->code }}</small>
                @endif
            </div>

            <!-- Editable Fields (Only show when checkbox checked) -->
            <div class="medicine-fields" style="min-width: 200px;">
                <div class="row g-2">
                    <div class="col-6">
                        <input type="text" 
                               name="medicines[{{ $index }}][dosage]" 
                               class="form-control form-control-sm" 
                               placeholder="Dosage"
                               value="{{ $existingAssignment->dosage ?? $medicine->dosage ?? '' }}"
                               {{ $existingAssignment ? '' : 'disabled' }}>
                    </div>
                    <div class="col-6">
                        <input type="text" 
                               name="medicines[{{ $index }}][quantity]" 
                               class="form-control form-control-sm" 
                               placeholder="Qty"
                               value="{{ $existingAssignment->quantity ?? $medicine->quantity ?? '' }}"
                               {{ $existingAssignment ? '' : 'disabled' }}>
                    </div>
                    <div class="col-12">
                        <input type="text" 
                               name="medicines[{{ $index }}][instructions]" 
                               class="form-control form-control-sm" 
                               placeholder="Instructions (e.g., AM, DT)"
                               value="{{ $existingAssignment->instructions ?? $medicine->instructions ?? '' }}"
                               {{ $existingAssignment ? '' : 'disabled' }}>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const checkbox = document.querySelector('.medicine-checkbox[data-medicine-id="{{ $medicine->id }}"]');
    const fields = checkbox.closest('.medicine-item').querySelector('.medicine-fields');
    
    checkbox?.addEventListener('change', function() {
        const inputs = fields.querySelectorAll('input');
        inputs.forEach(input => input.disabled = !this.checked);
        
        // Update submit button state
        updateSubmitButton('{{ $patientId }}');
    });
});
</script>
@endpush