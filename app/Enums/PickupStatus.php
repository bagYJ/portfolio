<?php
declare(strict_types=1);

namespace App\Enums;

use App\Traits\Enum;

enum PickupStatus: string
{
    use Enum;

    case TRY_ORDER = '602100';
    case ACCEPT_ORDER = '602200';
    case CONFIRM_RESERVATION = '602210';
    case READY_PICKUP = '602300';
    case CHECK_LUBRICATOR = '602310';
    case COMPLETE_PRESET = '602320';
    case START_REFUELING = '602350';
    case PROCESSED = '602400';
    case OUT_REFUELING = '602510';
    case CANCEL_REFUELING = '602520';
    case UNPROCESS_PICKUP = '602900';
    case CANCEL_WASH_SHOP = '602980';
    case CANCEL_AUTO_REFUELIN = '602990';
}
