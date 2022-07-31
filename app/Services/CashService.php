<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Member;
use App\Models\MemberCash;
use App\Models\MemberEventCash;
use Illuminate\Support\Facades\DB;

class CashService extends Service
{
    /**
     * 캐시 환불 등록
     * @param array $data
     * @return mixed
     */
    public static function revokeCash(array $data)
    {
        return MemberCash::create($data);
    }

    /**
     * 캐시 환불 처리
     * @param string $noUser
     * @param float $cash
     * @param float $eventCash
     * @return void
     */
    public static function refundMemberCash(string $noUser, float $cash = 0, float $eventCash = 0)
    {
        Member::where('no_user', $noUser)->update([
            'at_cash' => DB::raw("at_cash + {$cash}"),
            'at_event_cash' => DB::raw("at_event_cash + {$eventCash}"),
        ]);
    }

    public static function revokeEventCash($data)
    {
        return MemberEventCash::create($data);
    }
}
