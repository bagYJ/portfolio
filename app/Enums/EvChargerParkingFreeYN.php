<?php

declare(strict_types=1);

namespace App\Enums;

use ArchTech\Enums\Options;

enum EvChargerParkingFreeYN: string
{
    use Options;

    case Y = '무료';
    case N = '유료';
}
