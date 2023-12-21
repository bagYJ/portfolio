<?php

declare(strict_types=1);

namespace App\Enums;

use App\Traits\Enum;

enum SpcOrderType: string
{
    use Enum;

    case DRIVETHRU = '622100';
    case PICKUP = '622200';
}
