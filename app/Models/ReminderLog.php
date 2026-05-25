<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReminderLog extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'reminder_logs';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'patient_id',
        'type',
        'days_before',
        'sent_at',
        'meta',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'sent_at' => 'datetime',
        'meta' => 'array', // JSON automatically decode/encode
    ];

    /**
     * Get the patient that owns this reminder log.
     */
    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    /**
     * Scope: Filter by reminder type.
     */
    public function scopeOfType($query, string $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope: Filter by days before expiry.
     */
    public function scopeDaysBefore($query, int $days)
    {
        return $query->where('days_before', $days);
    }

    /**
     * Scope: Filter by date range.
     */
    public function scopeBetweenDates($query, $startDate, $endDate)
    {
        return $query->whereBetween('sent_at', [$startDate, $endDate]);
    }

    /**
     * Check if reminder was already sent for given criteria.
     *
     * @param int $patientId
     * @param string $type
     * @param int $daysBefore
     * @param string $date (Y-m-d format)
     * @return bool
     */
    public static function alreadySent(int $patientId, string $type, int $daysBefore, string $date): bool
    {
        return self::where('patient_id', $patientId)
            ->where('type', $type)
            ->where('days_before', $daysBefore)
            ->whereDate('sent_at', $date)
            ->exists();
    }

    /**
     * Get human-readable type label.
     */
    public function getTypeLabelAttribute(): string
    {
        return match($this->type) {
            'medicine_expiry' => 'Medicine Expiry',
            'appointment_reminder' => 'Appointment Reminder',
            'report_ready' => 'Report Ready',
            default => ucfirst(str_replace('_', ' ', $this->type)),
        };
    }

    /**
     * Get meta data as array (helper).
     */
    public function getMetaArrayAttribute(): array
    {
        return is_array($this->meta) ? $this->meta : json_decode($this->meta, true) ?? [];
    }
}