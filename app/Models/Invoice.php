<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Invoice extends Model
{
    protected $fillable = [
        'invoice_number', 'invoice_date', 'patient_id',
        'patient_name', 'patient_mobile', 'patient_address',
        'company_name', 'company_address', 'gstin', 'pan', 'contact',
        'items', 'taxable_amount', 'igst_amount', 'cgst_amount', 
        'sgst_amount', 'total_amount', 'terms', 'notes', 'is_paid'
    ];

    protected $casts = [
        'items' => 'array',
        'invoice_date' => 'date',
        'taxable_amount' => 'decimal:2',
        'igst_amount' => 'decimal:2',
        'cgst_amount' => 'decimal:2',
        'sgst_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'is_paid' => 'boolean',
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    // Generate next invoice number
    public static function generateInvoiceNumber(): string
    {
        $year = date('Y');
        $last = static::whereYear('created_at', $year)
            ->orderBy('id', 'desc')
            ->value('invoice_number');
        
        $next = $last ? (intval(substr($last, -4)) + 1) : 1;
        return 'INV-' . $year . '-' . str_pad($next, 4, '0', STR_PAD_LEFT);
    }
}