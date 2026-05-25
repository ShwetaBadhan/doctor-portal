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
        Schema::table('appointments', function (Blueprint $table) {
        // Vital Signs
        $table->string('vat')->nullable()->after('status');
        $table->string('pit')->nullable()->after('vat');
        $table->string('kuff')->nullable()->after('pit');
        $table->string('bp')->nullable()->after('kuff');
        $table->string('temp')->nullable()->after('bp');
        $table->string('pulse')->nullable()->after('temp');
        $table->string('weight')->nullable()->after('pulse');
        $table->string('tongue')->nullable()->after('weight');
        $table->string('nails')->nullable()->after('tongue');
        $table->string('cerebral_fluid')->nullable()->after('nails');
        
        // Additional Notes
        $table->text('vital_notes')->nullable()->after('cerebral_fluid');
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
        $table->dropColumn([
            'vat', 'pit', 'kuff', 'bp', 'temp', 'pulse', 
            'weight', 'tongue', 'nails', 'cerebral_fluid', 'vital_notes'
        ]);
    });
    }
};
