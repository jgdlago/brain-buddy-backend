<?php

namespace App\Enums;

enum PerformanceFlagEnum: string
{
    case VERY_LOW = 'very_low';
    case LOW = 'low';
    case MEDIUM = 'medium';
    case HIGH = 'high';
    case VERY_HIGH = 'very_high';

    public function label(): string
    {
        return match ($this) {
            self::VERY_LOW => 'Muito Baixo',
            self::LOW => 'Baixo',
            self::MEDIUM => 'Médio',
            self::HIGH => 'Alto',
            self::VERY_HIGH => 'Muito Alto',
        };
    }
}
