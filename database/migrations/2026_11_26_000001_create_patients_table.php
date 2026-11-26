<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->string('patient_code', 20)->unique();
            $table->string('full_name')->index();
            $table->date('date_of_birth')->nullable();
            $table->string('sex', 32)->nullable();
            $table->string('phone', 40)->nullable();
            $table->string('email')->nullable();
            $table->string('emergency_contact')->nullable();
            $table->text('address')->nullable();
            $table->string('allergies')->nullable();
            $table->string('status', 20)->default('Active')->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('patients');
    }
};
