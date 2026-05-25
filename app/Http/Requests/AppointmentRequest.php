<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;


class AppointmentRequest extends FormRequest {
    public function authorize(): bool { return true; }

    public function rules(): array {
        $isUpdate = $this->route('appointment') ? true : false;
        return [
        'patient_id' => 'required|exists:patients,id',
        'appointment_type' => 'required|in:in_person,online',
        'appointment_date' => 'required|date',
        'appointment_time' => 'required',
        'reason' => 'required|string|max:500',
        'status' => 'required|in:schedule,confirmed,checked_in,checked_out,cancelled',
        
        // ✅ Vitals Validation (Optional)
        'vat' => 'nullable|string|max:50',
        'pit' => 'nullable|string|max:50',
        'kuff' => 'nullable|string|max:50',
        'bp' => 'nullable|string|max:20',
        'temp' => 'nullable|string|max:20',
        'pulse' => 'nullable|string|max:20',
        'weight' => 'nullable|string|max:20',
        'tongue' => 'nullable|string|max:100',
        'nails' => 'nullable|string|max:100',
        'cerebral_fluid' => 'nullable|in:normal,shrink,expand',
        'vital_notes' => 'nullable|string|max:500',
    ];
    }

    public function messages(): array {
        return [
            'appointment_date.after_or_equal' => 'Appointment date must be today or in the future.',
            'appointment_time.date_format' => 'Time must be in HH:MM format.',
        ];
    }
}
