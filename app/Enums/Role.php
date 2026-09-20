<?php

namespace App\Enums;

enum Role: string
{
    case User = 'user';
    case Admin = 'admin';
    case SuperAdmin = 'super_admin';
    case Developer = 'developer';

    public function level(): int
    {
        return match ($this) {
            self::User => 1,
            self::Admin => 2,
            self::SuperAdmin => 3,
            self::Developer => 4,
        };
    }

    public function label(): string
    {
        return match ($this) {
            self::User => 'Warga',
            self::Admin => 'Pengelola',
            self::SuperAdmin => 'Ketua RT',
            self::Developer => 'Developer',
        };
    }

    public function atLeast(self $minimum): bool
    {
        return $this->level() >= $minimum->level();
    }
}
