<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class ContractState extends Enum
{
    public const IN_PROCESS = 1;
    public const INSTALLMENT = 2;
    public const FULL = 3;
    public const CANCEL = 4;
    public const REFUND = 5;
    public const PROBLEM = 6;
    public const CREDIT_CARD = 7;
    public const DONE = 8;
}
