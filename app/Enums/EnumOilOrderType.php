<?php

declare(strict_types=1);

namespace App\Enums;

use App\Traits\Enum;

enum EnumOilOrderType
{
    use Enum;

    case LITER;
    case PRICE;
}
