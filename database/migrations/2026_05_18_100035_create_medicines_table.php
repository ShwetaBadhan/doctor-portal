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
        Schema::create('medicines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('medicine_group_id')->constrained('medicine_groups')->onDelete('cascade');
            $table->integer('sort_order')->default(0); // S.NO. for ordering
            $table->string('name'); // e.g., "G.T-5 (SD5)"
            $table->string('dosage')->nullable(); // e.g., "3*4"
            $table->string('quantity')->nullable(); // e.g., "30ML", "100ML"
            $table->text('instructions')->nullable(); // e.g., "AM ONLY", "APPLY ON WHOLE SKIN"
            $table->string('route')->nullable(); // e.g., "ORAL", "EXTERNAL", "GULBULES"
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medicines');
    }
};
