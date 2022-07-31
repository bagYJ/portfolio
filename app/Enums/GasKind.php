<?php

declare(strict_types=1);

namespace App\Enums;

use App\Traits\Enum;

enum GasKind: int
{
    use Enum;

    case GASOLINE = 204100; //휘발유
    case DIESEL = 204200; //경유
    case LPG = 204300; //LPG
    case PREMIUM_GASOLINE = 204400; //고급 휘발유
    case ELECTRONIC = 204500; //전기
    case KEROSENE = 204600; //실내등유
}
