<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
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
        'profile_image','test_reports'
        
    ];

    protected $casts = [
        'dob' => 'date',
        'existing_symptoms' => 'array',
        'non_existing_symptoms' => 'array',
        // ✅ CAST CP_MOVEMENT AS ARRAY
        'cp_movement' => 'array',
         'test_reports' => 'array',
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
     /**
     * Get all medicines assigned to this patient
     */
    public function patientMedicines(): HasMany
    {
        return $this->hasMany(PatientMedicine::class)
            ->where('is_active', true)
            ->orderBy('sort_order');
    }

    /**
     * Get medicines with group/medicine name eager loaded
     */
    public function medicinesWithDetails(): HasMany
    {
        return $this->patientMedicines()
            ->with(['medicineGroup', 'groupMedicine.medicineName', 'medicineName']);
    }
}
