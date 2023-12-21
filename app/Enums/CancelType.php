<?php
declare(strict_types=1);

namespace App\Enums;

use App\Traits\Enum;

enum CancelType: string
{
    use Enum;

    case MEMBER_CANCEL = '620100';
    case SHOP_CANCEL = '620200';
    case AUTO_CANCEL = '620300';
    case MANAGER_CANCEL = '620400';
}
