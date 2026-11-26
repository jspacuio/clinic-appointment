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
        Schema::table('appointments', function (Blueprint $table) {
            $table->timestamp('doctor_confirmed_at')->nullable()->after('cancellation_notes');
            $table->timestamp('patient_confirmed_at')->nullable()->after('doctor_confirmed_at');
            $table->timestamp('doctor_started_at')->nullable()->after('confirmed_at');
            $table->timestamp('patient_started_at')->nullable()->after('doctor_started_at');
            $table->timestamp('doctor_completed_at')->nullable()->after('started_at');
            $table->timestamp('patient_completed_at')->nullable()->after('doctor_completed_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropColumn([
                'doctor_confirmed_at',
                'patient_confirmed_at',
                'doctor_started_at',
                'patient_started_at',
                'doctor_completed_at',
                'patient_completed_at',
            ]);
        });
    }
};
