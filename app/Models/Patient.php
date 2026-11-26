<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Patient extends Model
{
    protected $fillable = [
        'patient_code',
        'full_name',
        'date_of_birth',
        'sex',
        'phone',
        'email',
        'emergency_contact',
        'address',
        'allergies',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'date_of_birth' => 'date',
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
    {
        return $this->date_of_birth?->age;
    }

    public function getAvatarToneAttribute(): string
    {
        $tones = ['purple', 'blue', 'amber', 'teal', 'pink'];

        return $tones[($this->id ?? 0) % count($tones)];
    }

    public static function generateCode(): string
    {
        $nextNumber = ((int) static::max('id')) + 1001;

        return 'PC-' . $nextNumber;
    }
}
