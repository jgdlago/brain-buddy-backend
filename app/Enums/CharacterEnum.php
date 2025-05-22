<?php

namespace App\Enums;

enum CharacterEnum: string
{
    case TITO = 'tito';
    case NINA = 'nina';

    /**
     * @return string
     */
    public function label(): string
    {
        return match ($this) {
            self::TITO => 'Tito',
            self::NINA => 'Nina',
        };
    }
}
