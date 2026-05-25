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
    Schema::create('reminder_logs', function (Blueprint $table) {
        $table->id();
        $table->foreignId('patient_id')->constrained()->onDelete('cascade');
        $table->string('type'); // 'medicine_expiry'
        $table->tinyInteger('days_before'); // 5, 2, 1
        $table->timestamp('sent_at');
        $table->json('meta')->nullable(); // extra info
        $table->timestamps();
        
        $table->index(['patient_id', 'type', 'days_before', 'sent_at']);
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reminder_logs');
    }
};
