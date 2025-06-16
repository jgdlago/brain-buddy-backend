<?php

namespace App\Enums;

use App\Traits\ListEnums;

enum EducationLevelEnum: int
{
    use ListEnums;

    case FIRST   = 1;
    case SECOND  = 2;
    case THIRD   = 3;
    case FOURTH  = 4;
    case FIFTH   = 5;
    case SIXTH   = 6;
    case SEVENTH = 7;

    /**
     * @return string
     */
    public function label(): string
    {
        return match($this) {
            self::FIRST   => '1º ano - Ensino Fundamental',
            self::SECOND  => '2º ano - Ensino Fundamental',
            self::THIRD   => '3º ano - Ensino Fundamental',
            self::FOURTH  => '4º ano - Ensino Fundamental',
            self::FIFTH   => '5º ano - Ensino Fundamental',
            self::SIXTH   => '6º ano - Ensino Fundamental',
            self::SEVENTH => '7º ano - Ensino Fundamental',
        };
    }
}
