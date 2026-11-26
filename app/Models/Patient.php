<?php

namespace App\Models;

<<<<<<< Updated upstream
use Illuminate\Database\Eloquent\Model;
=======
use Database\Factories\PatientFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
>>>>>>> Stashed changes
use Illuminate\Database\Eloquent\Relations\HasMany;

class Patient extends Model
{
<<<<<<< Updated upstream
    protected $fillable = [
=======
    /** @use HasFactory<PatientFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
>>>>>>> Stashed changes
        'patient_code',
        'full_name',
        'date_of_birth',
        'sex',
        'phone',
        'email',
        'emergency_contact',
        'address',
<<<<<<< Updated upstream
        'allergies',
        'status',
    ];

=======
        'allergy_note',
        'allergy_recorded_at',
        'avatar_tone',
        'is_active',
    ];

    /**
     * @return array<string, string>
     */
>>>>>>> Stashed changes
    protected function casts(): array
    {
        return [
            'date_of_birth' => 'date',
<<<<<<< Updated upstream
        ];
    }

    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class)->latest('appointment_date');
    }

    public function getInitialsAttribute(): string
    {
        $initials = collect(explode(' ', trim($this->full_name)))
            ->filter()
            ->map(fn (string $part) => strtoupper(substr($part, 0, 1)))
            ->take(2)
            ->implode('');

        return $initials !== '' ? $initials : 'PT';
    }

    public function getAgeAttribute(): ?int
=======
            'allergy_recorded_at' => 'date',
            'is_active' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class);
    }

    public static function generateNextCode(): string
    {
        $nextNumber = ((int) static::max('id')) + 1043;
        $code = 'PC-'.$nextNumber;

        while (static::where('patient_code', $code)->exists()) {
            $nextNumber++;
            $code = 'PC-'.$nextNumber;
        }

        return $code;
    }

    public function age(): ?int
>>>>>>> Stashed changes
    {
        return $this->date_of_birth?->age;
    }

<<<<<<< Updated upstream
    public function getAvatarToneAttribute(): string
    {
        $tones = ['purple', 'blue', 'amber', 'teal', 'pink'];

        return $tones[($this->id ?? 0) % count($tones)];
    }

    public static function generateCode(): string
    {
        $nextNumber = ((int) static::max('id')) + 1001;

        return 'PC-' . $nextNumber;
=======
    public function initials(): string
    {
        $words = preg_split('/\s+/', trim($this->full_name)) ?: [];

        return collect($words)
            ->filter()
            ->map(fn (string $word): string => mb_strtoupper(mb_substr($word, 0, 1)))
            ->take(2)
            ->implode('') ?: 'PT';
>>>>>>> Stashed changes
    }
}
