<?php

declare(strict_types=1);

namespace App\Enums;

use App\Traits\Enum;

enum SearchBizKindDetail: string
{
    use Enum;

    case CAFE = '[203101, 203102]';
    case RESTAURANT = '[203201, 203202]';
    case OIL = '[203501, 203502]';
    case RETAIL = '[203801, 203802]';

    public static function getBizKindDetail(array $code): ?array
    {
        return collect(array_intersect(self::keys(), $code))->map(function ($collect) {
            return json_decode(self::case($collect)->value);
        })->flatten()->all();
    }

    public static function getBizKindDetailName(?string $value): ?string
    {
        return match ($value) {
            '203101', '203102' => self::CAFE->name,
            '203201', '203202' => self::RESTAURANT->name,
            '203501', '203502' => self::OIL->name,
            '203801', '203802' => self::RETAIL->name,
            default => null
        };
    }
}
