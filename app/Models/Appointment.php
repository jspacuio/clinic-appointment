<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Appointment extends Model
{
    protected $fillable = [
        'appointment_code',
        'patient_id',
        'doctor_name',
        'specialty',
        'appointment_date',
        'appointment_time',
        'visit_type',
        'reason',
        'status',
        'cancellation_reason',
        'cancellation_notes',
        'consultation_notes',
        'booked_by',
    ];

    protected function casts(): array
    {
        return [
            'appointment_date' => 'date',
        ];
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function getBadgeClassAttribute(): string
    {
        return strtolower(str_replace(' ', '-', $this->status));
    }

    public static function generateCode(): string
    {
        $nextNumber = ((int) static::max('id')) + 2001;

        return 'APT-' . $nextNumber;
    }
}
