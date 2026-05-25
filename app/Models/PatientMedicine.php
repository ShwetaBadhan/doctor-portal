<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PatientMedicine extends Model
{
    protected $table = 'patient_medicines';
    
    protected $fillable = [
        'patient_id',
        'medicine_group_id',
        'medicine_id',  // This links to the 'medicines' table
        'dosage',
        'quantity',
        'instructions',
        'route',
        'sort_order',
        'start_date',
        'end_date',
        'notes',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'start_date' => 'date',
        'end_date' => 'date',
        'sort_order' => 'integer',
    ];

    /**
     * Get the patient that owns this prescription
     */
    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    /**
     * Get the medicine group (if assigned via group)
     */
    public function medicineGroup(): BelongsTo
    {
        return $this->belongsTo(MedicineGroup::class);
    }

    /**
     * Get the medicine name/details ⭐ ADD THIS ⭐
     */
    public function medicine(): BelongsTo
    {
        return $this->belongsTo(Medicine::class, 'medicine_id');
    }

    /**
     * Get display name for this prescription
     */
    public function getMedicineDisplayNameAttribute(): string
    {
        return $this->medicine->name ?? 'Unknown Medicine';
    }
     /**
     * Scope: Only active assignments.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope: Filter by expiry date range.
     */
    public function scopeExpiringBetween($query, $startDate, $endDate)
    {
        return $query->whereBetween('end_date', [$startDate, $endDate])
                    ->whereNotNull('end_date');
    }
}