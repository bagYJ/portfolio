<?php

declare(strict_types=1);

namespace App\Enums;

use App\Traits\Enum;

enum BeaconLevel: string
{
    use Enum;

    case BEACON = '오토비콘회원';
    case DEF = '일반회원';
}
