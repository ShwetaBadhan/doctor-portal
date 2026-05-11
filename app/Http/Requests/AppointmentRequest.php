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
            'appointment_date' => 'required|date|after_or_equal:today',
            'appointment_time' => 'required|date_format:H:i',
            'reason' => 'required|string|max:1000',
            'status' => 'required|in:schedule,confirmed,checked_in,checked_out,cancelled',
        ];
    }

    public function messages(): array {
        return [
            'appointment_date.after_or_equal' => 'Appointment date must be today or in the future.',
            'appointment_time.date_format' => 'Time must be in HH:MM format.',
        ];
    }
}
