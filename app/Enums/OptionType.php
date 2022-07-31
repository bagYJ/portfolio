<?php

declare(strict_types=1);

namespace App\Enums;

use App\Traits\Enum;

enum OptionType: string
{
    use Enum;

    case REQUIRED = '621100';
    case SELECT = '621200';
    case OVERLAP = '621300';
}
