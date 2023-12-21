<?php
declare(strict_types=1);

namespace App\Enums;

use App\Traits\Enum;

enum AlarmEventType: string
{
    use Enum;

    case WAITING_ACCEPT = '607000';
    case ACCEPT_SPC = '607004';
    case SEND_ORDER = '607009';
    case ACCEPT_ORDER = '607050';
    case COMPLETE_READY = '607070';
    case CHANGE_PICKUP_TIME = '607080';
    case ALARM_FIRST = '607100';
    case ARRIVAL_150 = '607150';
    case ALARM_SECOND = '607200';
    case ARRIVAL_STORE = '607300';
    case READY_PRODUCT = '607340';
    case CALL_CLERK = '607350';
    case SEND_CALL_CLERK = '607359';
    case CHECK_CLERK = '607360';
    case CANCEL_CLERK = '607370';
    case CANCEL_USER = '607380';
    case CANCEL_STORE = '607390';
    case SEND_CANCEL = '607399';
    case COMPLETE_PICKUP = '607400';
    case COMPLETE_OPERATION_PICKUP = '607410';
    case COMPLETE_SEND = '607420';
    case COMPLETE_SEND_NOT = '607430';
    case DELAY_5 = '607505';
    case CANCEL_AUTO = '607900';
    case CANCEL_SPC = '607902';
    case ALARM_CANCEL_AUTO = '607910';
    case NO_SHOW = '607950';
    case CANCEL_UNVISITED = '607990';
    case API_CANCEL_AUTO = '607991';
    case ADMIN_CANCEL = '607999';
}
