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
    Schema::create('diagnosis_reports', function (Blueprint $table) {
        $table->id();
        $table->foreignId('patient_id')->constrained()->onDelete('cascade');
        $table->date('report_date');
        $table->string('pdf_path')->nullable(); // PDF file ka path
        $table->json('report_data')->nullable(); // Snapshot data (medicines, symptoms, vitals)
        $table->timestamps();
    });
}

public function down()
{
    Schema::dropIfExists('diagnosis_reports');
}
};
