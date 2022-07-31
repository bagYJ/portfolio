<?php

declare(strict_types=1);

namespace App\Enums;

use App\Traits\Enum;

enum PaymentKindCode: int
{
    use Enum;

    case CARD = 502100; //신용카드
    case PHONE = 502200; //휴대폰
}
