<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Shipment extends Model
{
    protected $fillable = [
        'invoice_id', 'patient_id', 'tracking_number', 'courier_name',
        'recipient_name', 'recipient_phone', 'recipient_address',
        'recipient_city', 'recipient_state', 'recipient_pincode',
        'items', 'status', 'status_notes', 'created_by',
        'packed_at', 'dispatched_at', 'delivered_at'
    ];

    protected $casts = [
        'items' => 'array',
        'packed_at' => 'datetime',
        'dispatched_at' => 'datetime',
        'delivered_at' => 'datetime',
    ];

    // Status constants
    const STATUS_PENDING = 'pending';
    const STATUS_PACKED = 'packed';
    const STATUS_DISPATCHED = 'dispatched';
    const STATUS_DELIVERED = 'delivered';
    const STATUS_CANCELLED = 'cancelled';

    // Scopes for easy filtering
    public function scopePending($query) { return $query->where('status', self::STATUS_PENDING); }
    public function scopePacked($query) { return $query->where('status', self::STATUS_PACKED); }
    public function scopeDispatched($query) { return $query->where('status', self::STATUS_DISPATCHED); }
    public function scopeDelivered($query) { return $query->where('status', self::STATUS_DELIVERED); }

    // Relationships
    public function invoice(): BelongsTo { return $this->belongsTo(Invoice::class); }
    public function patient(): BelongsTo { return $this->belongsTo(Patient::class); }

    // Status helpers
    public function isPending() { return $this->status === self::STATUS_PENDING; }
    public function isPacked() { return $this->status === self::STATUS_PACKED; }
    public function isDispatched() { return $this->status === self::STATUS_DISPATCHED; }
    public function isDelivered() { return $this->status === self::STATUS_DELIVERED; }

    // Get status badge class
    public function getStatusBadgeClass(): string
    {
        return match($this->status) {
            self::STATUS_PENDING => 'bg-warning bg-opacity-10 text-warning border-warning',
            self::STATUS_PACKED => 'bg-info bg-opacity-10 text-info border-info',
            self::STATUS_DISPATCHED => 'bg-primary bg-opacity-10 text-primary border-primary',
            self::STATUS_DELIVERED => 'bg-success bg-opacity-10 text-success border-success',
            self::STATUS_CANCELLED => 'bg-secondary bg-opacity-10 text-secondary border-secondary',
            default => 'bg-light text-dark',
        };
    }

    // Get status label
    public function getStatusLabel(): string
    {
        return match($this->status) {
            self::STATUS_PENDING => 'Pending',
            self::STATUS_PACKED => 'Packed',
            self::STATUS_DISPATCHED => 'Dispatched',
            self::STATUS_DELIVERED => 'Delivered',
            self::STATUS_CANCELLED => 'Cancelled',
            default => ucfirst($this->status),
        };
    }
}