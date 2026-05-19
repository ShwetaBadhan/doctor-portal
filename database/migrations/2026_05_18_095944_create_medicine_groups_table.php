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
         Schema::create('medicine_groups', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // e.g., "CA BLOOD", "AUTISM-1"
            $table->string('code')->nullable(); // e.g., "GD-4", "CKD"
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medicine_groups');
    }
};
