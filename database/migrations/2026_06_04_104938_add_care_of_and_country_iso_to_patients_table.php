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
        // ✅ Check if 'care_of_relation' exists before adding
        if (!Schema::hasColumn('patients', 'care_of_relation')) {
            Schema::table('patients', function (Blueprint $table) {
                $table->string('care_of_relation')->nullable();
            });
        }

        // ✅ Check if 'care_of_name' exists before adding
        if (!Schema::hasColumn('patients', 'care_of_name')) {
            Schema::table('patients', function (Blueprint $table) {
                $table->string('care_of_name')->nullable();
            });
        }

        // ✅ Check if 'phone_country_iso' exists before adding
        if (!Schema::hasColumn('patients', 'phone_country_iso')) {
            Schema::table('patients', function (Blueprint $table) {
                $table->string('phone_country_iso', 2)->default('IN');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            // Safely drop columns only if they exist
            $columnsToDrop = [];
            if (Schema::hasColumn('patients', 'care_of_relation')) $columnsToDrop[] = 'care_of_relation';
            if (Schema::hasColumn('patients', 'care_of_name')) $columnsToDrop[] = 'care_of_name';
            if (Schema::hasColumn('patients', 'phone_country_iso')) $columnsToDrop[] = 'phone_country_iso';

            if (!empty($columnsToDrop)) {
                $table->dropColumn($columnsToDrop);
            }
        });
    }
};