<?php

declare(strict_types=1);

namespace App\Enums;

use App\Traits\Enum;

enum Sex: int
{
    use Enum;

    case M = 0;
    case F = 1;
}
