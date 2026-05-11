<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Appointment extends Model {
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'appointment_id', 'patient_id', 'appointment_type', 'appointment_date',
        'appointment_time', 'reason', 'status'
    ];

    protected $casts = [
        'appointment_date' => 'date',
        'appointment_time' => 'datetime:H:i',
    ];

    public function patient() {
        return $this->belongsTo(Patient::class);
    }

    // Auto-generate Appointment ID
    protected static function boot() {
        parent::boot();
        static::creating(function ($model) {
            $count = self::withTrashed()->count() + 1;
            $model->appointment_id = 'AP' . str_pad($count, 6, '0', STR_PAD_LEFT);
        });
    }
}