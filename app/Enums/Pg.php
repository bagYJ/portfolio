<?php
declare(strict_types=1);

namespace App\Enums;

use App\Traits\Enum;

enum Pg: int
{
    use Enum;

    case nicepay = 500700;
    case fdk = 500100;
    case uplus = 500200;
    case kcp = 500600;
    case subscription_kcp = 500601;
    case incarpayment_kcp = 500602;

    public static function subscriptionPayment(): array
    {
        return [
            self::kcp->value,
            self::subscription_kcp->value
        ];
    }

    public static function incarpaymentPayment(): array
    {
        return [
            self::kcp->value,
            self::incarpayment_kcp->value
        ];
    }
}
