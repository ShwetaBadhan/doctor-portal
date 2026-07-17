<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Patient extends Model
{
    use HasFactory, SoftDeletes;

    // app/Models/Patient.php

// app/Models/Patient.php

protected $fillable = [
    'patient_id',
    'first_name',
    'last_name',
    'phone',
    'email',
    'dob',
    'gender',
    'blood_group',
    'age',
    'primary_doctor',
    'status',
    'address_1',
    'address_2',
    'country',
    'state',
    'city',
    'pincode',
    'existing_symptoms',
    'non_existing_symptoms',
    'additional_symptoms', // ✅ ADD THIS LINE
    'cp',
    'cp_movement',
    'medical_notes',
    'medicine',
    'therapy_history',
    'remarks',
    'profile_image',
    'test_reports',
    'care_of_relation',
    'care_of_name',
    'phone_country_iso',
];

protected $casts = [
    'dob' => 'date',
    'existing_symptoms' => 'array',
    'non_existing_symptoms' => 'array',
    'additional_symptoms' => 'array', // ✅ ADD THIS LINE (Laravel will handle JSON automatically)
    'cp_movement' => 'array',
    'test_reports' => 'array',
];
    /**
     * ✅ NEW: Generate unique patient ID with all initials + country code + serial number
     * Example: Shweta Badhan, Care of: Ramesh, Jalandhar, Punjab, India → SBRJPIN4501
     */
  public static function generatePatientId($firstName, $lastName, $careOfName, $city, $state, $countryIso = 'IN')
{
    $first = strtoupper(substr(preg_replace('/[^a-zA-Z]/', '', $firstName), 0, 1)) ?: 'X';
    $last  = strtoupper(substr(preg_replace('/[^a-zA-Z]/', '', $lastName), 0, 1)) ?: 'X';
    $careOf = strtoupper(substr(preg_replace('/[^a-zA-Z]/', '', $careOfName), 0, 1)) ?: 'X';
    $cityLetter = strtoupper(substr(preg_replace('/[^a-zA-Z]/', '', $city), 0, 1)) ?: 'X';
    $stateLetter = strtoupper(substr(preg_replace('/[^a-zA-Z]/', '', $state), 0, 1)) ?: 'X';
    $countryCode = strtoupper(substr($countryIso, 0, 2)) ?: 'XX';

    // ✅ sscanf - no regex for number extraction
    $maxNumber = self::whereNotNull('patient_id')
        ->get()
        ->map(function ($p) {
            $result = sscanf($p->patient_id, "%[A-Za-z]%d", $letters, $number);
            return $result === 2 ? (int) $number : 0;
        })
        ->max() ?: 4499;

    return $first . $last . $careOf . $cityLetter . $stateLetter . $countryCode . ($maxNumber + 1);
}
    public static function calculateAge($dob)
    {
        return \Carbon\Carbon::parse($dob)->age;
    }

    public function patientMedicines(): HasMany
    {
        return $this->hasMany(PatientMedicine::class)
            ->where('is_active', true)
            ->orderBy('sort_order');
    }

    public function medicinesWithDetails(): HasMany
    {
        return $this->patientMedicines()
            ->with(['medicineGroup', 'groupMedicine.medicineName', 'medicineName']);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function reminderLogs()
    {
        return $this->hasMany(ReminderLog::class, 'patient_id');
    }

    public function medicineExpiryReminders()
    {
        return $this->reminderLogs()->ofType('medicine_expiry');
    }
}
