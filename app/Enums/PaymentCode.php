<?php

declare(strict_types=1);

namespace App\Enums;

use App\Traits\Enum;

enum PaymentCode: int
{
    use Enum;

    case NORMAL = 501100; //FD - 일반결제
    case CARD_REGIST = 501200; //FD - 카드등록결제
    case KAKAO = 501300; //카카오페이
    case CHARGE = 501400; //충전결제
    case PRE_PAY = 501500; //FD - 가승인결제
    case EVENT = 501600; //이벤트결제
}
