<?php

declare(strict_types=1);

namespace App\Enums;

use App\Traits\Enum;

enum Pg: int
{
    use Enum;

    case nicepay = 500700;
    case fdk = 500100;
    case uplus = 500200;
    case kcp = 500600;
}
