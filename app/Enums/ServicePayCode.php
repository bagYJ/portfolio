<?php

declare(strict_types=1);

namespace App\Enums;

use App\Traits\Enum;

enum ServicePayCode: int
{
    use Enum;

    case NORMAL = 901100;
    case RUSH = 901200;
    case WEARABLE = 901300;
    case SHINHAN = 901400;
    case ONECLICK = 901500;
    case AVN = 901600;
}
