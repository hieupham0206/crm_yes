<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class ContractRoomType extends Enum
{
    public const ONE_BED = 1;
    public const TWO_BED = 2;
    public const THREE_BED = 3;
    public const BEDROOM = 4;

    public static function getDescription($value): string
    {
        if ($value === self::ONE_BED) {
            return __('1 giường');
        }

        if ($value === self::TWO_BED) {
            return __('2 giường');
        }

        if ($value === self::THREE_BED) {
            return __('3 giường');
        }

        if ($value === self::BEDROOM) {
            return __('Phòng ngủ');
        }

        return parent::getDescription($value);
    }
}
