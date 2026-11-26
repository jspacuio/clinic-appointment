<?php

namespace App\Models;

use Database\Factories\DoctorFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Doctor extends Model
{
    /** @use HasFactory<DoctorFactory> */
    use HasFactory;

    /**
     * @var list<string>
     */
    public const SPECIALIZATIONS = [
        'General Medicine',
        'Eye Care',
        'Pediatrics',
        'Dermatology',
        'Cardiology',
        'Dental Care',
        'Orthopedics',
        'ENT',
    ];

    protected $fillable = [
        'user_id',
        'full_name',
        'specialization',
        'license_number',
        'phone',
        'email',
        'consultation_duration',
        'availability_summary',
        'avatar_tone',
        'is_active',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'consultation_duration' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function schedules(): HasMany
    {
        return $this->hasMany(DoctorSchedule::class);
    }

    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class);
    }

    public function initials(): string
    {
        $words = preg_split('/\s+/', trim(str_replace('Dr. ', '', $this->full_name))) ?: [];

        return collect($words)
            ->filter()
            ->map(fn (string $word): string => mb_strtoupper(mb_substr($word, 0, 1)))
            ->take(2)
            ->implode('') ?: 'DR';
    }
}
