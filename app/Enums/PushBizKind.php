<?php
declare(strict_types=1);

namespace App\Enums;

use App\Traits\Enum;

enum PushBizKind
{
    use Enum;

    case FNB;
    case WASH;
    case PARKING;
    case RETAIL;
    case PERSONAL;
    case NOTICE;
    case EVENT;
}
