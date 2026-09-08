<?php

namespace App\Enums;

enum DigitalServiceStatus: string
{
    case Active = 'active';
    case Maintenance = 'maintenance';
    case Seasonal = 'seasonal';
    case Development = 'development';
    case Archived = 'archived';

    public function label(): string
    {
        return match ($this) {
            self::Active => 'Aktif',
            self::Maintenance => 'Maintenance',
            self::Seasonal => 'Musiman',
            self::Development => 'Pengembangan',
            self::Archived => 'Arsip',
        };
    }
}
