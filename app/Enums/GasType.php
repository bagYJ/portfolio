<?php
declare(strict_types=1);

namespace App\Enums;

use App\Traits\Enum;
use App\Utils\Code;

enum GasType: string
{
    use Enum;

    case GASOLINE = '204100';
    case DIESEL = '204200';
    case LPG = '204300';
    case PREMIUM_GASOLINE = '204400';

    public static function gasKind(?string $value): ?string
    {
        return Code::gasType($value);
    }
}
