// public/js/patient-medicines.js

document.addEventListener('DOMContentLoaded', function() {
    initMedicineGroupSelector();
    initCheckAllFunctionality();
    initMedicineCheckboxToggle();
});

function initMedicineGroupSelector() {
    document.querySelectorAll('.medicine-group-select').forEach(select => {
        select.addEventListener('change', handleGroupChange);
    });
}

function handleGroupChange() {
    const patientId = this.dataset.patientId;
    const groupId = this.value;
    
    if (!groupId) return resetMedicinesUI(patientId);
    
    showLoading(patientId);
    
    fetch(`/medicine-groups/${groupId}/medicines?patient_id=${patientId}`)
        .then(res => res.json())
        .then(data => renderMedicines(data, patientId))
        .catch(err => handleError(err, patientId));
}

function renderMedicines(data, patientId) {
    hideLoading(patientId);
    
    if (!data.medicines.length) {
        showNoMedicines(patientId);
        return;
    }
    
    const container = document.getElementById(`medicinesContainer_${patientId}`);
    container.innerHTML = data.medicines.map((med, i) => createMedicineItem(med, i, patientId)).join('');
    
    attachMedicineListeners(container, patientId);
    showMedicinesList(patientId);
    updateSubmitButton(patientId);
}

function createMedicineItem(med, index, patientId) {
    return `
        <div class="medicine-item card border-0 shadow-sm mb-2" data-medicine-id="${med.id}">
            <div class="card-body py-2 px-3">
                <div class="d-flex align-items-start gap-2">
                    <div class="pt-1">
                        <input class="form-check-input medicine-checkbox" type="checkbox" 
                               name="medicines[${index}][assign]" value="1"
                               ${med.already_assigned ? 'checked' : ''}>
                        <input type="hidden" name="medicines[${index}][medicine_id]" value="${med.id}">
                        ${med.already_assigned ? `<input type="hidden" name="medicines[${index}][patient_medicine_id]" value="${med.patient_medicine_id}">` : ''}
                    </div>
                    <div class="flex-grow-1">
                        <h6 class="mb-1 fw-semibold">${med.name}</h6>
                        ${med.code ? `<small class="text-muted">Code: ${med.code}</small>` : ''}
                    </div>
                    <div class="medicine-fields" style="min-width:220px">
                        <div class="row g-2">
                            <div class="col-6"><input type="text" name="medicines[${index}][dosage]" class="form-control form-control-sm" placeholder="Dosage" value="${med.dosage||''}" ${med.already_assigned?'':'disabled'}></div>
                            <div class="col-6"><input type="text" name="medicines[${index}][quantity]" class="form-control form-control-sm" placeholder="Qty" value="${med.quantity||''}" ${med.already_assigned?'':'disabled'}></div>
                            <div class="col-12"><input type="text" name="medicines[${index}][instructions]" class="form-control form-control-sm" placeholder="Instructions" value="${med.instructions||''}" ${med.already_assigned?'':'disabled'}></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `;
}

function attachMedicineListeners(container, patientId) {
    container.querySelectorAll('.medicine-checkbox').forEach(cb => {
        cb.addEventListener('change', function() {
            const fields = this.closest('.medicine-item').querySelector('.medicine-fields');
            fields.querySelectorAll('input').forEach(input => {
                if (input.name.match(/\[(dosage|quantity|instructions)\]/)) {
                    input.disabled = !this.checked;
                }
            });
            updateSubmitButton(patientId);
        });
    });
}

function updateSubmitButton(patientId) {
    const container = document.getElementById(`medicinesContainer_${patientId}`);
    if (!container) return;
    
    const count = container.querySelectorAll('.medicine-checkbox:checked').length;
    document.getElementById(`selectedCount_${patientId}`).textContent = `${count} selected`;
    document.getElementById(`submitCount_${patientId}`).textContent = count;
    document.getElementById(`submitAssign_${patientId}`).disabled = count === 0;
}

// Helper functions
function resetMedicinesUI(pid) { /* ... */ }
function showLoading(pid) { document.getElementById(`medicinesLoading_${pid}`).classList.remove('d-none'); }
function hideLoading(pid) { document.getElementById(`medicinesLoading_${pid}`).classList.add('d-none'); }
function showNoMedicines(pid) { document.getElementById(`noMedicines_${pid}`).classList.remove('d-none'); }
function showMedicinesList(pid) { document.getElementById(`medicinesList_${pid}`).classList.remove('d-none'); }
function handleError(err, pid) { console.error(err); hideLoading(pid); Swal.fire('Error', 'Failed to load medicines', 'error'); }

// Check All
function initCheckAllFunctionality() {
    document.addEventListener('change', function(e) {
        if (e.target.classList.contains('check-all-medicines')) {
            const container = document.getElementById(e.target.dataset.container);
            const checked = e.target.checked;
            container.querySelectorAll('.medicine-checkbox').forEach(cb => {
                cb.checked = checked;
                const fields = cb.closest('.medicine-item').querySelector('.medicine-fields');
                fields.querySelectorAll('input').forEach(input => {
                    if (input.name.match(/\[(dosage|quantity|instructions)\]/)) {
                        input.disabled = !checked;
                    }
                });
            });
            updateSubmitButton(e.target.closest('form').querySelector('.medicine-group-select').dataset.patientId);
        }
    });
}