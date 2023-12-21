<?php

declare(strict_types=1);

namespace App\Enums;

use App\Traits\Enum;

enum Pickup: int
{
    use Enum;

    case CAR = 709100;
    case SHOP = 709200;
    case RESERVE = 709300;
}
