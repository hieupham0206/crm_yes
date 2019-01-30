<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class CommissionRoleSpecification extends Enum
{
    public const BY_TELE = 1;
    public const PRIVATE = 2;
    public const AMBASSADOR = 3;

    public static function getDescription($value): string
    {
        if ($value === self::BY_TELE) {
            return 'Khách từ Tele';
        }

        if ($value === self::PRIVATE) {
            return 'Khách tự mời';
        }

        if ($value === self::AMBASSADOR) {
            return 'Khách giới thiệu';
        }

        return parent::getDescription($value);
    }
}
