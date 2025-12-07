<?php

declare(strict_types=1);

namespace App\Payroll\Domain\Model;

enum BonusType: string
{
    case FIXED = 'fixed';
    case PERCENT = 'percent';
}
