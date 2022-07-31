<?php

declare(strict_types=1);

namespace App\Enums;

use App\Traits\Enum;

enum Withdrawal: int
{
    use Enum;

    case PRIVACY_CONCERNS = 1;
    case SERVICE_INCONVENIENCE = 2;
    case UNUSED = 3;
    case ETC = 4;
}
