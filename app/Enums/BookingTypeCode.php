<?php

declare(strict_types=1);

namespace App\Enums;

use App\Traits\Enum;

enum BookingTypeCode: int
{
    use Enum;

    case CARID = 505100; // CarID주문
    case QR = 505200; //QR주문
    case NUMBER_INPUT = 505300; //번호입력
}
