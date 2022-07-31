<?php

declare(strict_types=1);

namespace App\Enums;

use App\Traits\Enum;

enum ParkingStatus: string
{
    use Enum;

    case WAIT = '사용전';
    case USED = '사용완료';
    case CANCELED = '예약취소';
    case EXPIRED = '주차권만료';
}
