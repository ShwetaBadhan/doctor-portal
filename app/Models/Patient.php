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

   /**
 * Generate unique patient ID: FirstLetter + LastLetter + CityLetter + RandomNumber
 * Example: Shweta Badhan Jalandhar → SBJ482916
 */
public static function generatePatientId($firstName, $lastName, $city)
{
    // Get first letters (uppercase, remove non-alpha characters)
    $first = strtoupper(substr(preg_replace('/[^a-zA-Z]/', '', $firstName), 0, 1));
    $last = strtoupper(substr(preg_replace('/[^a-zA-Z]/', '', $lastName), 0, 1));
    $cityLetter = strtoupper(substr(preg_replace('/[^a-zA-Z]/', '', $city), 0, 1));
    
    // Handle empty values with fallback 'X'
    $first = $first ?: 'X';
    $last = $last ?: 'X';
    $cityLetter = $cityLetter ?: 'X';
    
    // Generate 6-digit random number
    $random = str_pad(random_int(100000, 999999), 6, '0', STR_PAD_LEFT);
    
    // Combine parts
    $patientId = $first . $last . $cityLetter . $random;
    
    // Ensure uniqueness (check database and append counter if needed)
    $originalId = $patientId;
    $counter = 1;
    while (self::where('patient_id', $patientId)->exists()) {
        $patientId = $originalId . str_pad($counter, 2, '0', STR_PAD_LEFT);
        $counter++;
    }
    
    return $patientId;
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
    public function appointments()
{
    return $this->hasMany(Appointment::class);
}
/**
 * Get all reminder logs for this patient.
 */
public function reminderLogs()
{
    return $this->hasMany(ReminderLog::class, 'patient_id');
}

/**
 * Get only medicine expiry reminders.
 */
public function medicineExpiryReminders()
{
    return $this->reminderLogs()->ofType('medicine_expiry');
}
}
