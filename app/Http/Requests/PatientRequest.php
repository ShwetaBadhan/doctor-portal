<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class PatientRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'dob' => 'required|date',
            'gender' => 'required|in:male,female,other',
            'blood_group' => 'nullable|string|max:10',
            'primary_doctor' => 'nullable|string|max:255',
            'status' => 'required|in:available,unavailable',
            
            // Address
            'address_1' => 'required|string',
            'address_2' => 'nullable|string',
            'country' => 'required|string',
            'state' => 'required|string',
            'city' => 'required|string',
            'pincode' => 'required|string|max:20',
            
            // Vital Signs
            'vat' => 'nullable|string',
            'pit' => 'nullable|string',
            'kuff' => 'nullable|string',
            'bp' => 'nullable|string',
            'temp' => 'nullable|string',
            'pulse' => 'nullable|string',
            'weight' => 'nullable|string',
            'tongue' => 'nullable|string',
            'nails' => 'nullable|string',
            'cerebral_fluid' => 'nullable|in:shrink,expand,normal',
            
             
     // Symptoms Arrays
            'existing_symptoms' => 'nullable|array',
            'existing_symptoms.*' => 'string|max:255',
            
            'non_existing_symptoms' => 'nullable|array',
            'non_existing_symptoms.*' => 'string|max:255',
            
            // ✅ C.P (Cerebral Palsy) - ADD THESE
            'cp' => 'nullable|in:yes,no',
            'cp_movement' => 'nullable|array',
            'cp_movement.*' => 'in:upper_limb,lower_limb',
            
            // ✅ Medical Notes - ADD THIS
            'medical_notes' => 'nullable|string|max:1000',
            
            // Treatment
            'medicine' => 'nullable|string',
            'therapy_history' => 'nullable|string',
            'remarks' => 'nullable|string',

  'test_reports' => 'nullable|array',
        'test_reports.*' => 'file|mimes:pdf,jpg,jpeg,png,doc,docx|max:5120', // Max 5MB
            
        ];
    }
}
