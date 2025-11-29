<?php

namespace App\Enums;

enum Role: string
{
    case ADMIN = 'admin';
    case GENERAL_USER = 'general_user';

    public function label(): string
    {
        return match($this) {
            self::ADMIN => 'Admin',
            self::GENERAL_USER => 'General User',
        };
    }
}
