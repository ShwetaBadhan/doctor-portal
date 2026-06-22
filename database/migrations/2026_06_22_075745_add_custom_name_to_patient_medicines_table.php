<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('patient_medicines', function (Blueprint $table) {
        $table->string('custom_name')->nullable()->after('medicine_id');
    });
}

public function down()
{
    Schema::table('patient_medicines', function (Blueprint $table) {
        $table->dropColumn('custom_name');
    });
}
};
