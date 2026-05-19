<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('patient_medicines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();
            
            // Link to existing medicine_groups table
            $table->foreignId('medicine_group_id')->nullable()->constrained('medicine_groups')->nullOnDelete();
            
            // FIXED: Link to existing 'medicines' table instead of non-existent 'medicine_names'
            $table->foreignId('medicine_id')->nullable()->constrained('medicines')->nullOnDelete();
            
            // Prescription details (overrides)
            $table->string('dosage')->nullable();
            $table->string('quantity')->nullable();
            $table->text('instructions')->nullable();
            $table->string('route')->nullable();
            $table->integer('sort_order')->default(0);
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->text('notes')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->index(['patient_id', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('patient_medicines');
    }
};