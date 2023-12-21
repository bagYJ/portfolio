<?php

declare(strict_types=1);

namespace App\Enums;

use App\Traits\Enum;

enum SpcAlarmEventStatus: int
{
    use Enum;

    case IC = 607004; //주문접수
    case DR = 607050; //제조중
    case PW = 607070; //준비완료
    case PC = 607400; //전달완료
    case CC = 616991; //주문취소

    public static function alarmStep(string $alarmEventStatus): ?string
    {
        return match ($alarmEventStatus) {
            self::DR->name => 'shop_accept',
            self::PW->name => 'shop_complete',
            self::PC->name => 'delivery_complete',
            self::CC->name => 'cancel',
            default => null
        };
    }
}

