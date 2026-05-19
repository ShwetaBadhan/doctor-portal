<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
     public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique(); // e.g., INV-2026-001
            $table->date('invoice_date');
            
            // Patient link (optional - can also store name manually)
            $table->foreignId('patient_id')->nullable()->constrained('patients')->nullOnDelete();
            
            // Billing details (stored in case patient changes later)
            $table->string('patient_name');
            $table->string('patient_mobile')->nullable();
            $table->text('patient_address')->nullable();
            
            // Company details
            $table->string('company_name')->default('E-Bio-Cares');
            $table->string('company_address')->nullable();
            $table->string('gstin')->nullable();
            $table->string('pan')->nullable();
            $table->string('contact')->nullable();
            
            // Items (stored as JSON for flexibility)
            $table->json('items'); // [{name, hsn, qty, rate, tax_percent, amount}, ...]
            
            // Tax calculations
            $table->decimal('taxable_amount', 10, 2);
            $table->decimal('igst_amount', 10, 2)->default(0);
            $table->decimal('cgst_amount', 10, 2)->default(0);
            $table->decimal('sgst_amount', 10, 2)->default(0);
            $table->decimal('total_amount', 10, 2);
            
            // Notes & terms
            $table->text('terms')->nullable();
            $table->text('notes')->nullable();
            
            $table->boolean('is_paid')->default(false);
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
