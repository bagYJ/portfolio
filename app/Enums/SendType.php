<?php
declare(strict_types=1);

namespace App\Enums;

use App\Traits\Enum;

enum SendType: string
{
    use Enum;

    case PU = '622200';
    case DV = '622100';
}
