<?php
declare(strict_types=1);

namespace App\Enums;

use App\Traits\Enum;

enum AppType: int
{
    use Enum;

    case OWIN = 110000;
    case AVN = 110300;
    case TMAP_AUTO = 110400;
    const GTCS = 110600;
}
