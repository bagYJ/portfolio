<?php
declare(strict_types=1);

namespace App\Enums;

use App\Traits\Enum;

enum OrderStatus: string
{
    use Enum;

    case REQUEST_ORDER = '601100';
    case COMPLETE_PAYMENT = '601200';
    case MEMBER_CANCEL = '601900';
    case SHOP_CANCEL = '601950';
    case MANAGER_CANCEL = '601999';
}
