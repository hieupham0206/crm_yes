<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class ContractLimit extends Enum
{
    public const TYPE1 = 1;
    public const TYPE2 = 2;
    public const TYPE3 = 3;

    public static function getDescription($value): string
    {
        if ($value === self::TYPE1) {
            return __('2 lớn, 2 nhỏ <6');
        }

        if ($value === self::TYPE2) {
            return __('4 lớn, 2 nhỏ <6');
        }

        if ($value === self::TYPE3) {
            return __('6 lớn, 2 nhỏ <6');
        }

        return parent::getDescription($value);
    }
}
