<?php

namespace App\Enums;

enum ExpenseType: string
{
    case ELECTRICITY = 'electricity';
    case RENT = 'rent';
    case MAINTENANCE = 'maintenance';
    case SALARY = 'salary';
    case OTHER = 'other';

    public function label(): string
    {
        return match($this) {
            self::ELECTRICITY => 'كهرباء',
            self::RENT => 'إيجار',
            self::MAINTENANCE => 'صيانة',
            self::SALARY => 'رواتب',
            self::OTHER => 'أخرى',
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
