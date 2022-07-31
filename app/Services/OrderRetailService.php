<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\MemberShopRetailLog;

class OrderRetailService extends Service
{

    /**
     * [주문결제] 리테일 주문 이벤트로그 저장
     * @param array $data
     * @return mixed
     */
    public static function registMemberShopRetailLog(array $data): void
    {
        MemberShopRetailLog::create($data);
    }

    /**
     * 픽업 완료 체크 로그
     * @param string $noOrder
     * @return int
     */
    public static function getOrderCompleteLogCnt(string $noOrder)
    {
        return MemberShopRetailLog::where([
            'no_order' => $noOrder,
            'cd_alarm_event_type' => '607400'
        ])->count();
    }
}
