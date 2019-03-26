<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class LeadState extends Enum
{
    public const NEW_CUSTOMER = 1;
    public const DEAD_NUMBER = 2;
    public const WRONG_NUMBER = 3;
    public const OTHER_CITY = 4;
    public const NO_ANSWER = 5;
    public const NO_INTERESTED = 6;
    public const CALL_LATER = 7;
    public const APPOINTMENT = 8;
    public const NOT_DEAL_YET = 9;
    public const MEMBER = 10;
    public const OUTCALL = 11;

    public static function getDescription($value): string
    {
        if ($value === self::NEW_CUSTOMER) {
            return __('New customer');
        }

        if ($value === self::DEAD_NUMBER) {
            return __('Dead number');
        }

        if ($value === self::WRONG_NUMBER) {
            return __('Wrong number');
        }

        if ($value === self::OTHER_CITY) {
            return __('Other city');
        }

        if ($value === self::NO_ANSWER) {
            return __('No answer');
        }

        if ($value === self::NO_INTERESTED) {
            return __('No interested');
        }

        if ($value === self::CALL_LATER) {
            return __('Follow list');
        }

        if ($value === self::APPOINTMENT) {
            return __('Appointment');
        }

        if ($value === self::NOT_DEAL_YET) {
            return __('Not deal yet');
        }

        if ($value === self::MEMBER) {
            return __('Member');
        }

        if ($value === self::OUTCALL) {
            return __('Outcall');
        }

        return parent::getDescription($value);
    }
}
