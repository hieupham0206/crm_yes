<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class PaymentMethod extends Enum
{
    public const CASH = 1;
    public const AMORTIZATION = 2;
    public const CARD = 3;
    public const TRANSFER = 4;

    public static function getDescription($value): string
    {
        if ($value === self::CASH) {
            return 'Tiền mặt';
        }

        if ($value === self::AMORTIZATION) {
            return 'Trả góp ngân hàng';
        }

        if ($value === self::CARD) {
            return 'Cà thẻ';
        }

        if ($value === self::TRANSFER) {
            return 'Chuyển khoản';
        }

        return parent::getDescription($value);
    }
}
