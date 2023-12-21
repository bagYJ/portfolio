<?php

declare(strict_types=1);

namespace App\Enums;

use App\Traits\Enum;

enum EnumYN
{
    use Enum;

    case Y;
    case N;
}
