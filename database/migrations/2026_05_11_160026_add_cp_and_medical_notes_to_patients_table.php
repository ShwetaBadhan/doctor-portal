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
        Schema::table('patients', function (Blueprint $table) {
            // Add if not exists
            if (!Schema::hasColumn('patients', 'cp')) {
                $table->string('cp')->nullable()->after('cerebral_fluid');
            }
            if (!Schema::hasColumn('patients', 'cp_movement')) {
                $table->json('cp_movement')->nullable()->after('cp');
            }
            if (!Schema::hasColumn('patients', 'medical_notes')) {
                $table->text('medical_notes')->nullable()->after('cp_movement');
            }
        });
    }

    public function down(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->dropColumn(['cp', 'cp_movement', 'medical_notes']);
        });
    }
};
