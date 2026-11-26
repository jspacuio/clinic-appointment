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
        Schema::create('doctors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('full_name');
            $table->string('specialization');
            $table->string('license_number')->unique();
            $table->string('phone')->nullable();
            $table->string('email')->unique();
            $table->unsignedSmallInteger('consultation_duration')->default(30);
            $table->string('availability_summary')->nullable();
            $table->string('avatar_tone')->default('teal');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['is_active', 'specialization']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doctors');
    }
};
