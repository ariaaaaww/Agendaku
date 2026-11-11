<?php

namespace App\Enums;

enum UserRole: string
{
    case ADMIN = 'admin';
    case STUDENT = 'student';
    case TEACHER = 'teacher';
    case COUNCIL = 'council';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
