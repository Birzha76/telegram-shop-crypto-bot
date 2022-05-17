<?php

namespace App\Enums;

class CheckStatus
{
    const UnderConsideration = 'Under consideration';
    const Considered = 'Considered';

    public static function label(?string $value): string
    {
        return match ($value) {
            self::UnderConsideration => 'Under consideration',
            self::Considered => 'Considered',
            default => $value ?? '-',
        };
    }
}
