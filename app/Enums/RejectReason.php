<?php
declare(strict_types=1);

namespace App\Enums;

use App\Traits\Enum;

enum RejectReason: string
{
    use Enum;

    case OUT_OF_STOCK = '606100';
    case STORE_CONGESTION = '606200';
    case AUTO_CANCEL = '606300';
    case STORE_CLOSED = '606500';
    case PRODUCT_MISMATCH = '606600';
    case UNPROCESSABLE = '606610';
    case UNABLE_TO_COOK = '606620';
    case UNABLE_TO_ORDER = '606630';
    case ETC = '606900';
}
