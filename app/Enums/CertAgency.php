<?php

declare(strict_types=1);

namespace App\Enums;

use App\Traits\Enum;

enum CertAgency
{
    use Enum;

    case SKT;
    case KTF;
    case LGT;
    case SKM;
    case KTM;
    case LGM;
}
