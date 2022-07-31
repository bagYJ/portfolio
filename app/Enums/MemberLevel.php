<?php

declare(strict_types=1);

namespace App\Enums;

use App\Traits\Enum;

enum MemberLevel: int
{
    use Enum;

    case OWIN = 104100;
    case AVN = 104600;
    case TMAP_AUTO = 104700;
}

