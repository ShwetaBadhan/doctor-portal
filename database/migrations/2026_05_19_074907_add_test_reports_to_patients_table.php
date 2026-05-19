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
        // Store multiple files as JSON array
        $table->json('test_reports')->nullable();
        // Or single file: $table->string('test_report')->nullable();
    });
}

public function down(): void
{
    Schema::table('patients', function (Blueprint $table) {
        $table->dropColumn('test_reports');
    });
}
};
