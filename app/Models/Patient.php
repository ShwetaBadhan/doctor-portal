<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Patient extends Model
{
     use HasFactory, SoftDeletes;

     protected $fillable = [
        'patient_id', 'first_name', 'last_name', 'phone', 'email', 'dob', 
        'gender', 'blood_group', 'age', 'primary_doctor', 'status',
        'address_1', 'address_2', 'country', 'state', 'city', 'pincode',
        'vat', 'pit', 'kuff', 'bp', 'temp', 'pulse', 'weight', 'tongue', 'nails',
        'cerebral_fluid', 
        'existing_symptoms', 'non_existing_symptoms',
        // ✅ ADD THESE
        'cp', 'cp_movement', 'medical_notes',
        // Treatment
        'medicine', 'therapy_history', 'remarks',
        'profile_image'
    ];

    protected $casts = [
        'dob' => 'date',
        'existing_symptoms' => 'array',
        'non_existing_symptoms' => 'array',
        // ✅ CAST CP_MOVEMENT AS ARRAY
        'cp_movement' => 'array',
    ];

    // Generate unique patient ID
    public static function generatePatientId()
    {
        $prefix = 'PID';
        $lastPatient = self::latest('id')->first();
        $nextId = $lastPatient ? intval(substr($lastPatient->patient_id, -4)) + 1 : 1;
        return $prefix . str_pad($nextId, 4, '0', STR_PAD_LEFT);
    }

    // Calculate age from DOB
    public static function calculateAge($dob)
    {
        return \Carbon\Carbon::parse($dob)->age;
    }
}
