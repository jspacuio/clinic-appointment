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
            $table->string('patient_code')->unique();
            $table->string('full_name');
            $table->date('date_of_birth')->nullable();
            $table->string('sex')->default('Prefer not to say');
            $table->string('phone');
            $table->string('email')->nullable();
            $table->string('emergency_contact')->nullable();
            $table->text('address')->nullable();
            $table->string('allergy_note')->nullable();
            $table->date('allergy_recorded_at')->nullable();
            $table->string('avatar_tone')->default('purple');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['is_active', 'full_name']);
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
