<?php

declare(strict_types=1);

namespace App\Enums;

use App\Traits\Enum;

enum SearchBizKindDetail: string
{
    use Enum;

    case CAFE = '[203101, 203102]';
    case SPC = '[203103]';
    case RESTAURANT = '[203201, 203202]';
    case CLOTH = '[203301]';
    case NECESSITY = '[203401]';
    case FLOWER = '[203402]';
    case OIL = '[203501, 203502]';
    case PARKING = '[201500, 203511, 203512]';
    case WASH = '[203601]';
    case REPAIR = '[203602]';
    case HANDWASH = '[203603]';
    case RETAIL = '[203801, 203802]';
    case OWIN = '[203999]';

    public static function getBizKindDetail(string $value): ?SearchBizKindDetail
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

    public static function getBizKindDetailName(?string $value): ?string
    {
        return match ($value) {
            '203101', '203102' => self::CAFE->name,
            '203103' => self::SPC->name,
            '203201', '203202' => self::RESTAURANT->name,
            '203501', '203502' => self::OIL->name,
            '203801', '203802' => self::RETAIL->name,
            '203601', '203603' => self::WASH->name,
            default => null
        };
    }
}
