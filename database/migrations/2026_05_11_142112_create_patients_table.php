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
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->string('patient_id')->unique();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('phone');
            $table->string('email')->nullable();
            $table->date('dob');
            $table->enum('gender', ['male', 'female', 'other']);
            $table->string('blood_group')->nullable();
            $table->integer('age');
            $table->string('primary_doctor')->nullable();
            $table->enum('status', ['available', 'unavailable'])->default('available');
            
            // Address
            $table->text('address_1');
            $table->text('address_2')->nullable();
            $table->string('country');
            $table->string('state');
            $table->string('city');
            $table->string('pincode');
            
            // Vital Signs
            $table->string('vat')->nullable();
            $table->string('pit')->nullable();
            $table->string('kuff')->nullable();
            $table->string('bp')->nullable();
            $table->string('temp')->nullable();
            $table->string('pulse')->nullable();
            $table->string('weight')->nullable();
            $table->string('tongue')->nullable();
            $table->string('nails')->nullable();
            $table->enum('cerebral_fluid', ['shrink', 'expand', 'normal'])->default('normal');
            
            // Symptoms (stored as JSON)
            $table->json('existing_symptoms')->nullable();
            $table->json('non_existing_symptoms')->nullable();
            
            // Diagnosis (stored as JSON)
            $table->json('diagnosis')->nullable();
            
            // Medicine/Therapy
            $table->text('medicine')->nullable();
            $table->text('remarks')->nullable();
            $table->text('therapy_history')->nullable();
            
            // Profile
            $table->string('profile_image')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patients');
    }
};
