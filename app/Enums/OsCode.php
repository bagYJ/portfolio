<?php

declare(strict_types=1);

namespace App\Enums;

use App\Traits\Enum;

enum OsCode: int
{
    use Enum;

    case IPHONE = 103101;
    case ANDROID = 103102;
    case AVN = 103103;
}
