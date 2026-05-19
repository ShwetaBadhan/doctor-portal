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
        Schema::create('shipments', function (Blueprint $table) {
            $table->id();
            
            // Reference to invoice/order
            $table->foreignId('invoice_id')->nullable()->constrained('invoices')->nullOnDelete();
            $table->foreignId('patient_id')->nullable()->constrained('patients')->nullOnDelete();
            
            // Shipment details
            $table->string('tracking_number')->unique()->nullable();
            $table->string('courier_name')->nullable(); // e.g., "Blue Dart", "DTDC"
            $table->string('recipient_name');
            $table->string('recipient_phone');
            $table->text('recipient_address');
            $table->string('recipient_city');
            $table->string('recipient_state');
            $table->string('recipient_pincode');
            
            // Items shipped (JSON for flexibility)
            $table->json('items'); // [{name, quantity, ...}, ...]
            
            // Status tracking
            $table->enum('status', ['pending', 'packed', 'dispatched', 'delivered', 'cancelled'])->default('pending');
            $table->timestamp('packed_at')->nullable();
            $table->timestamp('dispatched_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->text('status_notes')->nullable();
            
            // Metadata
            $table->string('created_by')->nullable(); // User who created
            $table->timestamps();
        });
        
       
    }

    public function down(): void
    {
        Schema::dropIfExists('shipments');
    }
};
