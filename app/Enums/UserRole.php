<?php

namespace App\Enums;

enum UserRole: string
{
    case User = 'user';
    case Admin = 'admin';
    case Super = 'super';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
