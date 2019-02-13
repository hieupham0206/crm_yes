<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class PaymentMethod extends Enum
{
    public const CASH = 1;
    public const AMORTIZATION = 2;
    public const CARD = 3;
    public const TRANSFER = 4;
    public const FIXED = 5;

    public static function getDescription($value): string
    {
        if ((int) $value === self::CASH) {
            return 'Tiền mặt';
        }

        if ((int) $value === self::AMORTIZATION) {
            return 'Trả góp ngân hàng';
        }

        if ((int) $value === self::CARD) {
            return 'Cà thẻ';
        }

        if ((int) $value === self::TRANSFER) {
            return 'Chuyển khoản';
        }

        if ((int) $value === self::FIXED) {
            return 'Phí cố định';
        }

        return parent::getDescription($value);
    }
}
