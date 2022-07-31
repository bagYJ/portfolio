<?php

declare(strict_types=1);

namespace App\Enums;

use App\Traits\Enum;

enum PaymentMethodCode: int
{
    use Enum;

    case BILL = 504100; //BillKey결제
    case OTC = 504200; //OTC결제
}
