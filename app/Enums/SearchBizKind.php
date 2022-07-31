<?php

declare(strict_types=1);

namespace App\Enums;

use App\Traits\Enum;

enum SearchBizKind: string
{
    use Enum;

    case FNB = '[201100, 201200]';
    case OIL = '[201300]';
    case NECESSITY = '[201400]';
    case RETAIL = '[201800]';
    case WASH = '[201600]';
    case PARKING = '[201500]';
    case VALET = '[201510]';
    case REPAIR = '[201610]';
    case TOLLING = '[201700]';
    case OWIN_TEST = '[201998]';
    case OWIN = '[201999]';

    public static function getBizKind(string $value): ?SearchBizKind
    {
        $code = null;
        foreach (self::cases() as $codes) {
            if (in_array($value, json_decode($codes->value))) {
                $code = $codes;
                break;
            }
        }

        return $code;
    }
}
