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
    case PARKING = '[201500]';
    case VALET = '[201510]';
    case WASH = '[201600]';
    case REPAIR = '[201610]';
    case TOLLING = '[201700]';
    case RETAIL = '[201800]';
    case OWIN_TEST = '[201998]';
    case OWIN = '[201999]';

    case FNBNR = '[201100, 201200, 201800]';
    case CAFE = '[201100]';
    case FOOD = '[201200]';

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

    public static function getDetailBizKind(string $value): ?SearchBizKind
    {
        $bizKind = self::getBizKind($value);
        return match ($bizKind) {
            SearchBizKind::FNB => match ($value) {
                '201100' => SearchBizKind::CAFE,
                '201200' => SearchBizKind::FOOD,
                default => SearchBizKind::FNB,
            },
            default => $bizKind,
        };
    }
}