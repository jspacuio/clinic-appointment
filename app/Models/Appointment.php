<?php

namespace App\Models;

<<<<<<< Updated upstream
=======
use App\Enums\AppointmentStatus;
use Carbon\CarbonImmutable;
use Database\Factories\AppointmentFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
>>>>>>> Stashed changes
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Appointment extends Model
{
<<<<<<< Updated upstream
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

=======
    /** @use HasFactory<AppointmentFactory> */
    use HasFactory;

    protected $fillable = [
        'appointment_code',
        'patient_id',
        'doctor_id',
        'booked_by_user_id',
        'appointment_date',
        'start_time',
        'duration_minutes',
        'visit_type',
        'reason',
        'status',
        'clinical_notes',
        'notes_draft',
        'cancellation_reason',
        'cancellation_notes',
        'doctor_confirmed_at',
        'patient_confirmed_at',
        'confirmed_at',
        'doctor_started_at',
        'patient_started_at',
        'started_at',
        'doctor_completed_at',
        'patient_completed_at',
        'completed_at',
        'cancelled_at',
    ];

    /**
     * @return array<string, string>
     */
>>>>>>> Stashed changes
    protected function casts(): array
    {
        return [
            'appointment_date' => 'date',
<<<<<<< Updated upstream
=======
            'duration_minutes' => 'integer',
            'status' => AppointmentStatus::class,
            'notes_draft' => 'boolean',
            'doctor_confirmed_at' => 'datetime',
            'patient_confirmed_at' => 'datetime',
            'confirmed_at' => 'datetime',
            'doctor_started_at' => 'datetime',
            'patient_started_at' => 'datetime',
            'started_at' => 'datetime',
            'doctor_completed_at' => 'datetime',
            'patient_completed_at' => 'datetime',
            'completed_at' => 'datetime',
            'cancelled_at' => 'datetime',
>>>>>>> Stashed changes
        ];
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

<<<<<<< Updated upstream
    public function getBadgeClassAttribute(): string
    {
        return strtolower(str_replace(' ', '-', $this->status));
    }

    public static function generateCode(): string
    {
        $nextNumber = ((int) static::max('id')) + 2001;

        return 'APT-' . $nextNumber;
=======
    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class);
    }

    public function bookedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'booked_by_user_id');
    }

    public static function generateNextCode(): string
    {
        $nextNumber = ((int) static::max('id')) + 2054;
        $code = 'APT-'.$nextNumber;

        while (static::where('appointment_code', $code)->exists()) {
            $nextNumber++;
            $code = 'APT-'.$nextNumber;
        }

        return $code;
    }

    public function startTimeFormatted(): string
    {
        return CarbonImmutable::parse($this->start_time)->format('g:i A');
    }

    public function endTimeFormatted(): string
    {
        return CarbonImmutable::parse($this->start_time)
            ->addMinutes($this->duration_minutes)
            ->format('g:i A');
>>>>>>> Stashed changes
    }
}
