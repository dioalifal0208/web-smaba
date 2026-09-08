<?php

namespace App\Enums;

enum DigitalServiceAccessType: string
{
    case Public = 'public';
    case Internal = 'internal';
    case LimitedPublic = 'limited_public';
    case TeachersOnly = 'teachers_only';
    case StudentsOnly = 'students_only';
    case AdminsOnly = 'admins_only';

    public function label(): string
    {
        return match ($this) {
            self::Public => 'Publik',
            self::Internal => 'Internal',
            self::LimitedPublic => 'Publik Terbatas',
            self::TeachersOnly => 'Khusus Guru',
            self::StudentsOnly => 'Khusus Siswa',
            self::AdminsOnly => 'Khusus Admin',
        };
    }
}
