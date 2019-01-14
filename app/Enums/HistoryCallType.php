<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class HistoryCallType extends Enum
{
    public const MANUAL = 1;
    public const HISTORY = 2;
    public const CALLBACK = 3;
    public const APPOINTMENT = 4;

    public static function getDescription($value): string
    {
        if ($value === self::MANUAL) {
            return __('Auto call');
        }

        if ($value === self::HISTORY) {
            return __('History call');
        }

        if ($value === self::CALLBACK) {
            return __('Callback call');
        }

        if ($value === self::APPOINTMENT) {
            return __('Appointment call');
        }

        return parent::getDescription($value);
    }
}
