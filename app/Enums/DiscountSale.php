<?php

declare(strict_types=1);

namespace App\Enums;

use App\Traits\Enum;

enum DiscountSale: string
{
    use Enum;

    case DISCOUNT = '132100';
    case ONE_PLUS_ONE = '132200';
    case TWO_PLUS_ONE = '132300';
    case GIFT = '132400';
    case SET = '132500';
    case TWO_PLUS_TWO = '132600';
}
