<?php

declare(strict_types=1);

namespace App\Enums;

use App\Traits\Enum;

enum CertNation: int
{
    use Enum;

    case K = 0;
    case F = 1;
}
