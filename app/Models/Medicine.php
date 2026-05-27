<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Medicine extends Model
{
    protected $fillable = [
        'medicine_group_id',
        'sort_order',
        'name',
        'dosage',
        'quantity',
        'instructions',
        'route',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

     public function group(): BelongsTo
    {
        return $this->belongsTo(MedicineGroup::class, 'medicine_group_id');
    }

    public function getFullDisplayAttribute(): string
    {
        $parts = [$this->name];
        if ($this->dosage) $parts[] = $this->dosage;
        if ($this->quantity) $parts[] = $this->quantity;
        if ($this->instructions) $parts[] = "({$this->instructions})";
        return implode(' -> ', $parts);
    }
public function patientMedicines()
{
    return $this->hasMany(PatientMedicine::class);
}
}