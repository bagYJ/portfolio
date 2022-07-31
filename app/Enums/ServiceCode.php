<?php

declare(strict_types=1);

namespace App\Enums;

use App\Traits\Enum;

enum ServiceCode: int
{
    use Enum;

    case PICKUP = 900100;
    case PARKING = 900200;
    case FOOD = 900300;
    case WEB = 900400;
    case ETC = 900500;
}
