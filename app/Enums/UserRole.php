<?php

namespace App\Enums;

enum UserRole: string
{
    case Admin = 'admin';
    case Doctor = 'doctor';
    case Receptionist = 'receptionist';
    case Patient = 'patient';

    public function label(): string
    {
        return match ($this) {
            self::Admin => 'Administrator',
            self::Doctor => 'Doctor',
            self::Receptionist => 'Receptionist',
            self::Patient => 'Patient',
        };
    }

    public function isAdminPortal(): bool
    {
        return in_array($this, [self::Admin, self::Receptionist], true);
    }
}
