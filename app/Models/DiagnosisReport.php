<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DiagnosisReport extends Model
{
    protected $fillable = [
        'patient_id',
        'report_date',
        'pdf_path',
        'report_data',
    ];

    protected $casts = [
        'report_date' => 'date',
        'report_data' => 'array',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
}