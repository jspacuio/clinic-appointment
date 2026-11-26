<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->string('appointment_code', 20)->unique();
            $table->foreignId('patient_id')->constrained()->cascadeOnDelete();
            $table->string('doctor_name');
            $table->string('specialty')->default('General Medicine');
            $table->date('appointment_date')->index();
            $table->string('appointment_time', 20);
            $table->string('visit_type')->default('General Checkup');
            $table->text('reason')->nullable();
            $table->string('status', 30)->default('Confirmed')->index();
            $table->string('cancellation_reason')->nullable();
            $table->text('cancellation_notes')->nullable();
            $table->text('consultation_notes')->nullable();
            $table->string('booked_by')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
