<?php

declare(strict_types=1);

namespace App\Http\Controllers\Tmap;

use App\Exceptions\OwinException;
use App\Services\OrderService;
use App\Services\TmapService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Order
{
    /**
     * 재시동시 당일주문건 처리내역 전달
     * @param Request $request
     * @return JsonResponse
     * @throws OwinException
     */
    public function gets(Request $request)
    {
        $request->validate([
            'cd_service' => 'required|string|min:6|max:6',
        ]);
        //todo cd_service 사용 여부 확인
        $cdService = $request->get('cd_service');
        $memberInfo = (new TmapService())->getMemberInfo(Auth::id());
        if ($memberInfo['result'] == '0') {
            //throw new OwinException(Code::message('A1001'));
            return response()->json($memberInfo);
        }

        ## --------------------------------------------------------------------
        ## RSM 토큰인증
        ## --------------------------------------------------------------------

        // 회윈의 진행중인 주문건 리스트	- cd_pickup_status / 602400
        // 결제오류 / 결제취소 제외			- cd_payment_status / 603200, 603900
        return response()->json(OrderService::getAllOrderList($memberInfo['no_user']));
    }
}
