<?php

declare(strict_types=1);

namespace App\Enums;

use App\Traits\Enum;

enum SpcCancelType: int
{
    use Enum;

    case cancel_accept = 606300;
    case cancel_cs = 601999;
    case cancel_order = 606900;

}

