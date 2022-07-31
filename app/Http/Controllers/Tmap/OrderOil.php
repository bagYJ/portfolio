<?php

declare(strict_types=1);

namespace App\Http\Controllers\Tmap;

use App\Enums\EnumYN;
use App\Exceptions\OwinException;
use App\Exceptions\TMapException;
use App\Jobs\ProcessArkServer;
use App\Queues\Socket\ArkServer;
use App\Services\CardService;
use App\Services\CouponService;
use App\Services\OilService;
use App\Services\OrderOilService;
use App\Services\OrderService;
use App\Services\ShopOilPriceService;
use App\Services\ShopService;
use App\Utils\Code;
use App\Utils\Validation;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderOil
{
    /**
     * 주문 요청정보
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function init(Request $request): JsonResponse
    {
        $request->validate([
            'cd_service' => 'required|string|min:6|max:6',
            'no_shop' => 'required|integer',
        ]);

        $noShop = intval($request->get('no_shop'));
        $cdBizKind = Code::conf('biz_kind.oil');

        $memberInfo = Auth::user();
        $noUser = $memberInfo['no_user'];
        $cdGasKind = $memberInfo->memberCarInfo->cd_gas_kind;

        $pointCard = $memberInfo->memberPointCard->filter(function ($value) {
            return $value['cd_point_cp'] = Code::conf('oil.gs_cd_point_sale_cp');
        })->first();

        $shop = ShopService::getShop($noShop);
        if ($shop->count() <= 0) {
            throw new TMapException('SC1000', 400);
        }

        $response = [
            'result' => "1",
            'nm_partner' => $shop['partner']['nm_partner'],
            'nm_shop' => $shop['nm_shop'],
            'ds_van_adr' => $shop->shopOil?->ds_van_adr,
            'ds_new_adr' => $shop->shopOil?->ds_new_adr,
        ];

        ## 주유 예약내역확인
        ## 주유 예약건있을경우 처리완료전 추가 예약 안됨
        $orderInfo = OrderService::getUserOrderInfo([
            ['a.no_user', '=', $noUser],
            ['a.no_shop', '=', $noShop],
            ['b.cd_biz_kind', '=', $cdBizKind],
            ['a.cd_pickup_status', '<', 602400],
            ['a.cd_order_status', '=', '601200'],
            ['a.cd_payment_status', '=', '603100'],
        ]);

        if ($orderInfo) {
            $response['order_result'] = [
                'nm_partner' => $orderInfo['nm_partner'],
                'no_shop' => $orderInfo['nm_shop'],
                'no_order' => $orderInfo['no_order'],
                'cd_pickup_status' => $orderInfo['cd_pickup_status'],
            ];
        }
        $response['order_result']['result'] = $orderInfo ? "1" : "0";
        //매장 오늘 영업시간
        $shopOptTime = ShopService::getInfoOptTime($noShop);
        if ($shopOptTime->count() <= 0) {
            throw new TMapException('SC1000', 400);
        }

        $arrivalTime = date("Hi");
        if ($shopOptTime && !($shopOptTime['ds_open_time'] < $arrivalTime && $shopOptTime['ds_close_time'] > $arrivalTime)) {
            throw new TMapException('P2054', 400);
        }

        $response['shop_opt_time'] = [
            'ds_open_time' => $shopOptTime['ds_open_time'] ?? null,
            'ds_close_time' => $shopOptTime['ds_close_time'] ?? null
        ];
        $response['cd_gas_kind'] = $cdGasKind;

        $oilInfo = ShopOilPriceService::getShopOilPrice($noShop);
        $response['list_gas_kind'] = $oilInfo['list_gas_kind'];
        $response['list_oil_info'] = $oilInfo['list_oil_info'];

        $ynGsPartner = 'N';
        if ($orderInfo && $orderInfo['partner']['no_partner'] === Code::conf('oil.gs_no_partner')) {
            $ynGsPartner = 'Y';
        }

        $listCdPg = [Code::conf('oil.gs_oil_cd_pg')];


        ## 7-1. 결제 카드리스트
        ## Billkdy + NICE  (Sort: NICE > 등록일 순)
        $cards = (new CardService())->cardList($noUser, $noShop, $listCdPg, true, true, true);
        $response['list_card'] = $cards->map(function ($collect) {
            $collect->cd_payment_method = '504100';
            return $collect;
        });

        if ($ynGsPartner === 'Y') {
            $coupons = CouponService::myOilCoupon($noUser);
            $response['list_coupon'] = [];
            if (count($coupons)) {
                foreach ($coupons as $val) { // 대역포함일경우 조건부쿠폰만 사용가능
                    $response['list_coupon'][] = [
                        'no_event' => $val['no_event'],
                        'nm_event' => $val['nm_event'],
                        'ds_discount' => $val['ds_discount'],
                        'at_discount' => (int)$val['at_discount'],
                    ];
                }
            }
        }

        ## 현장할인카드 정보
        // 포인트 카드가 없을 경우  브랜드별로 전달값 분리 [ GS - ""/  EX - NULL]
        $response['id_pointcard'] = $pointCard['id_pointcard'] ?? null; // EX 주유소 주문건
        if ($ynGsPartner === 'Y') {
            $response['id_pointcard'] = $pointCard['id_pointcard'] ?? ''; // GS 주유소 주문건
        }

        return response()->json($response);
    }

    /**
     * 결제요청
     * @param Request $request
     *
     * @return JsonResponse
     * @throws OwinException
     */
    public function payment(Request $request): JsonResponse
    {
        $request->validate([
            'cd_service' => 'required|string',
            'cd_service_pay' => 'required|string',
            'cd_payment' => 'required|string',
            'cd_payment_kind' => 'nullable|string',
            'cd_payment_method' => 'required|string',
            'no_shop' => 'required|integer',
            'cd_gas_kind' => 'required|string',
            'no_card' => 'nullable|integer',
            'list_no_event' => 'nullable|array',
            'at_gas_price' => 'required|integer',
            'at_liter_gas' => 'required|string',
            'yn_gas_order_liter' => 'required|string',
            'at_price' => 'required|integer',
            'at_cpn_disct' => 'nullable|integer',
            'at_disct' => 'nullable|integer',
            'at_owin_cash' => 'nullable|integer',
            'at_point_disct' => 'nullable|integer',
            'yn_gps_status' => 'nullable|string',
        ]);

        $noCard = intval($request->get('no_card'));
        if (!$noCard) {
            throw new TMapException('P1034', 300);
        }

        $shop = ShopService::getShop(intval($request->no_shop));
        if ($shop->ds_status != EnumYN::Y->name) {
            throw new TMapException('M1303', 400);
        }

        $orderService = new OrderOilService();
        $response = $orderService->reservation(
            Auth::user(),
            $shop,
            collect($request->all()),
            'tmap'
        );

        return response()->json([
            'result' => $response['result'] ? "1" : "0",
            'no_order' => $response['no_order'],
        ]);
    }

    public function detail(Request $request): JsonResponse
    {
        $request->validate([
            'no_order' => 'required|numeric',
            'cd_service' => 'required|string',
        ]);

        $noOrder = $request->get('no_order');

        ##6 RSM 로그인정보 반환
        $memberInfo = Auth::user();
        $noUser = $memberInfo['no_user'];
        $cdGasKind = $memberInfo->memberCarInfo->cd_gas_kind;

        $orderInfo = OrderService::getOrder($noOrder);
        if (!$orderInfo) {
            throw new TMapException('P2120', 300);
        }

        // 실제pg결제금액
        $orderPrice = OilService::getProductPriceLiter($noOrder, intval($orderInfo['orderPayment']['at_price_pg']), $cdGasKind);
        $orderPrice = str_pad(sprintf("%4.3f", (string)$orderPrice), 8, "0", STR_PAD_LEFT);


        $yn_payment_cancel = 'N';
        if ($orderInfo['partner']['cd_biz_kind'] == '201300') {
            $dt_reg = Carbon::createFromFormat('Y-m-d H:i:s', $orderInfo['dt_reg'])->format('Y-m-d H:i:s');
            $date_parse = date_parse($dt_reg);
            $limit_date = $date_parse['year'] . '-' . sprintf('%02d', $date_parse['month']) . '-0' . sprintf(
                    '%02d',
                    $date_parse['day']
                ) . ' 23:30:00';
            if (date('Y-m-d H:i:s') < $limit_date) {
                $yn_payment_cancel = 'Y';
            } else {
                if ($orderInfo['cd_pickup_status'] == '602100') { // 주문요청은 접수전이라 취소가능
                    $yn_payment_cancel = 'Y';
                } elseif ($orderInfo['cd_pickup_status'] == '602200' and $orderInfo['at_add_delay_min'] > 0) { // 접수할때 지연시킨경우
                    $dp = date_parse($orderInfo['dt_pickup_status']);
                    $pickup_status_stemp = mktime(
                            $dp['hour'],
                            $dp['minute'],
                            $dp['second'],
                            $dp['month'],
                            $dp['day'],
                            $dp['year']
                        ) + 300;
                    if ($pickup_status_stemp > time()) { // 변경시간 5분이 지나지 않은경우 취소가능
                        $yn_payment_cancel = 'Y';
                    }
                }
            }
        }

        $response = [
            'result' => "1",
            'no_partner' => $orderInfo['shop']['no_partner'],
            'nm_partner' => $orderInfo['partner']['nm_partner'],
            'nm_shop' => $orderInfo['partner']['nm_partner'] . ' ' . $orderInfo['shop']['nm_shop'],
            'cd_gas_kind' => $cdGasKind,
            'yn_payment_cancel' => $yn_payment_cancel,

            'yn_self' => $orderInfo['partner']['yn_self_owin'],
            'ds_address' => $orderInfo['shop']['ds_address'] ?? '',
            'at_lat' => $orderInfo['shop']['at_lat'],
            'at_lng' => $orderInfo['shop']['at_lng'],
        ];

        if ($orderInfo['cd_order_status'] === '601200' && $orderInfo['cd_pickup_status'] === '602200') {
            $response['yn_payment_cancel'] = 'Y';
        }

        $memberShopEnterLog = OrderService::getMemberShopEnterLog([
            'no_user' => $noUser,
            'no_order' => $noOrder,
            'yn_is_in' => 'N'
        ]);

        $currentMsg = "";

        $response['cd_order_process'] = $orderInfo['orderProcess']['cd_order_process'] ?? null;
        //한도조회 실패
        if ($response['cd_order_process'] === '616999') {
            $dsResRefundCode = $orderInfo['orderPayment']['ds_res_code_refund'] ?? null;

            if ($dsResRefundCode === '8326') {
                // 한도초과오류
                $currentMsg = "예약 시 선택하신 카드는 한도 초과로 사용할 수 없습니다.\n다른 카드를 등록/선택한 후 주유를 예약해 주세요.";
            } else {
                //한도초과 이외의 오류
                $currentMsg = "예약 시 선택하신 카드는 사용할 수 없는 카드 입니다.\n다른 카드를 등록/선택한 후 주유를 예약해 주세요";
            }
        } elseif ($orderInfo['cd_order_status'] === '601900' && $orderInfo['cd_pickup_status'] === '602400' && $orderInfo['cd_payment_status'] === '603900') {
            $response['cd_order_process'] = '616991';
        }
        if ($response['cd_order_process'] === '616920') {
            // 주유기상태확인 / 프리셋상태
            if ($memberShopEnterLog['nt_unit_id_status']) {
                // 주유기상태확인 - 노즐이 정상상태가 아닐경우
                if ($response['yn_self'] === 'Y') {
                    // 셀프 주유소
                    $currentMsg = "선택한 주유기는 현재 사용 중입니다.\n주유기 노즐이 주유기에 거치가 되어 있는 지\n확인 후 다시 시도해 주세요.";
                } else {
                    // 풀서비스 주유소
                    $currentMsg = "선택한 주유기는 현재 사용 중입니다.\n주유기 사용 가능할 때 다시 시도해 주세요.";
                }
            }
        }
        if ($response['cd_order_process'] === '616930') {
            // 정차판단불가
            if ($response['yn_self'] === 'Y') {
                // 셀프 주유소
                $currentMsg = "차량의 위치를 확인하지 못하였습니다.\n주유기에 부착된 오윈 번호로 주유를 진행하시겠습니까?";
            } else {
                // 풀서비스 주유소
                $currentMsg = "차량의 위치를 확인하지 못하였습니다.\n주유소 직원의 도움을 통해 차량을 이동하거나,\n번호 선택을 통해 주유기에 부착된 오원 번호로\n주유를 진행해 주세요.";
            }
        }

        if ($response['cd_order_process'] === '616910') {
            // 유종오류
            if ($response['yn_self'] === 'Y') {
                // 셀프 주유소
                $currentMsg = "선택한 주유기는  '" . Code::conf(
                        "gas_kind_product_name.{$cdGasKind}"
                    ) . "'가 없는 주유기입니다.\n다른 주유기를 이용해 주세요.";
            } else {
                // 풀서비스 주유소
                $currentMsg = "선택한 주유기는  '" . Code::conf(
                        "gas_kind_product_name.{$cdGasKind}"
                    ) . "'가 없는 주유기입니다.\n점원의 안내를 통해 다른 주유기로 이동해 주세요.";
            }
        }

        ### 진행 오류상태의 경우 >> 오류메세지 반환  [주문리셋 - 초기화]
        ## cd_order_process 진행상태 업데이트   - 616100
        ## 주유기사용중 / 정차판단불가 / 한도조회실패 / 유종오류
        if ($currentMsg) {
            OrderService::registOrderProcess([
                'no_user' => $noUser,
                'no_order' => $noOrder,
                'no_shop' => $orderInfo['no_shop'],
                'cd_order_process' => $response['cd_order_process'],
            ]);
        }

        $response['yn_preset'] = ($response['cd_order_process'] > 616100 && $response['cd_order_process'] < 616500) ? "Y" : "N";// 프리셋세팅이후 Y
        $response['current_msg'] = $currentMsg;

        ## ----------------------------------------------------------------------------------------------
        ## [E] 주유주문 처리상태 메세지추가 2021-01-20
        ## ----------------------------------------------------------------------------------------------
        // 주문 - 결제서비스 방식구분
        $response['cd_service_pay'] = $orderInfo['cd_service_pay'];

        ## 주문상태 정보
        $response['cd_order_status'] = $orderInfo['cd_order_status']; // 주문상태
        $response['cd_pickup_status'] = $orderInfo['cd_pickup_status']; // 픽업상태

        # 결재금액관련 내역 정보
        $response['cd_payment_status'] = $orderInfo['cd_payment_status']; // 결재상태
        $response['cd_pg_result'] = $orderInfo['orderPayment']['cd_pg_result']; // 결재상태 --
        $response['at_price'] = (int)$orderInfo['at_price']; // 결제금액_실캐쉬
        $response['at_disct'] = (int)$orderInfo['at_disct']; // 오윈상시할인금액
        $response['at_cpn_disct'] = (int)$orderInfo['at_cpn_disct']; // 쿠폰할인금액
        $response['at_point_disct'] = (int)$orderInfo['at_point_disct']; // 브랜드포인트할인금액 - 마일리지할인
        $response['at_cash_disct'] = (int)$orderInfo['at_cash_disct'] + $orderInfo['at_event_cash_disct']; //오윈캐시+보너스캐시
        $response['at_price_pg'] = (int)$orderInfo['at_price_pg']; // 실제pg결제금액
        $response['no_card'] = $orderInfo['no_card'];

        # 주유관련 정보
        // 간편주문건이 경우 리터표시 제외
        $response['at_liter_gas'] = $orderInfo['cd_service_pay'] == '901200' ? "" : $orderInfo['at_liter_gas'];
        $response['at_liter_real'] = $orderInfo['at_liter_real'];
        $response['yn_gas_order_liter'] = $orderInfo['yn_gas_order_liter'];

        # 결제금액 확인후 > 조건별 브랜드쿠폰발행 - 테스트시 히든
        OrderOilService::getReceiveCouponAfterPay($noUser);
        return response()->json($response);
    }

    public function oilDpList(Request $request): JsonResponse
    {
        $request->validate([
            'no_order' => 'required|numeric',
            'cd_service' => 'required|string',
        ]);
        $orderInfo = OrderService::getOrder($request->get('no_order'));
        if (!$orderInfo) {
            throw new TMapException('P2120', 400);
        }

        // 종료 주문건의 경우 에러코드
        if ($orderInfo['cd_pickup_status'] >= '602400' && $orderInfo['cd_payment_status'] === '603300') {
            //결제정상처리시에만 전달
            throw new TMapException('P2404', 400);
        }

        return response()->json([
            'result' => "1",
            'ds_unit_id_list' => OilService::getUnitInfo($orderInfo['no_shop'])->unique('ds_display_ark_id')->pluck('ds_display_ark_id')->all()
        ]);
    }

    public function oilDpnoRegist(Request $request): JsonResponse
    {
        $request->validate([
            'cd_service' => 'required|string',
            'no_order' => 'required|string',
            'ds_display_ark_id' => 'required|string',
            'no_oil_company' => 'required|string',
        ]);

        $noUser = Auth::id();
        $noOrder = $request->get('no_order');
        $displayArkId = str_pad($request->get('ds_display_ark_id'), 2, "0", STR_PAD_LEFT);

        // 주문정보조회
        $orderInfo = (new OrderService())->getOrderInfo([
            'no_user' => $noUser,
            'no_order' => $noOrder,
        ])->whenEmpty(function () {
            throw new TMapException('P2120', 400);
        })->first();

        //소켓 전송 여부
        $ynSocketSend = 'Y';

        // 주유소 브랜드번호로 - no_partner 매칭
        $noOilCompany = $orderInfo['no_partner'] == Code::conf('oil.ex_no_partner') ? "2" : "1";
        // [로그] APP내에서  주유소 QR 리더정보 등록 하기  (인증번호) [번호입력추가]
        $lastId = OilService::registQrReader([
            'no_oil_company' => $noOilCompany,
            'no_shop' => $orderInfo['no_shop'],
            'ds_display_ark_id' => $displayArkId,
            'cd_oil_confirm_type' => "506200"
        ]);

        $arkList = OilService::checkOilDpArk($orderInfo['no_shop'], $displayArkId)->pluck('ds_unit_id')->all();
        if (!count($arkList)) {
            throw new OwinException(Code::message('P2405'));
        }

        ## 주유진행상태
        $orderProcess = OrderService::getRecentOrderProcess($noOrder, $noUser);
        if (in_array($orderProcess['cd_order_process'], ['602320', '602350'])) {
            $ynSocketSend = false;
        }

        $arkList = OilService::checkOilDpArk($orderInfo['no_shop'], $displayArkId);
        if (!count($arkList)) {
            throw new TMapException('P2405', 400);
        }

        ## 주유진행상태
        $orderProcess = OrderService::getRecentOrderProcess($noOrder, $noUser);
        if (in_array($orderProcess['cd_order_process'], ['602320', '602350'])) {
            $ynSocketSend = false;
        }

        // 해당주문건이 있을경우 주문정보 를 QR리드 정보에 업데이트
        if ($lastId) {
            OilService::updateQrReader($lastId, $noUser, $noOrder, $orderInfo['shop']['shopOil']['ds_uni']);
        }

        // 주유로그 추가
        OilService::createMemberShopEnterLog([
            'no_user' => $noUser,
            'no_shop' => $orderInfo['no_shop'],
            'no_order' => $noOrder,
            'yn_is_in' => 'O',
            'ds_unit_id' => $displayArkId,
        ]);

        if ($ynSocketSend) {
            ProcessArkServer::dispatch(
                new ArkServer(
                    'ORDER',
                    'orderStatus',
                    "GD" . $orderInfo['no_shop'] . $displayArkId . $orderInfo['no_user'],
                    '42'
                )
            )->onConnection('database');
        }

        return response()->json([
            'result' => "1",
            'ds_unit_id_list' => $arkList,
        ]);
    }

    public function cancel(Request $request): JsonResponse
    {
        $request->validate([
            'cd_service' => 'required|string',
            'no_order' => 'required|string',
        ]);

        $cdService = $request->get('cd_service');
        $noOrder = $request->get('no_order');

        if (!Validation::code($cdService, '900')) {
            // 서비스구분
            throw new TMapException('C0900', 400);
        }

        $orderInfo = collect(OrderService::getOrder($noOrder))->whenEmpty(function () {
            throw new OwinException(Code::message('P2120'));
        })->whenNotEmpty(function ($order) {
            if ($order['cd_payment_status'] === '603900') {
                throw new OwinException(Code::message('P2401'));
            } elseif ($order['cd_pickup_status'] === '602400') {
                throw new OwinException(Code::message('P2404'));
            } elseif (empty($order['no_order']) || $order['cd_payment_status'] !== '603100') {
                throw new OwinException(Code::message('P2100'));
            }

            // 취소가능한 경우 - 구매당일 23시30분 까지
            if (now() > Carbon::createFromFormat('Y-m-d H:i:s', $order['dt_reg'])->format('Y-m-d 23:30:00')) {
                throw new TMapException('P2140', 400);
            }
        });

        $result = (new OrderOilService())->cancel($orderInfo, 'tmap');


        return response()->json([
            'result' => "1",
        ]);
    }
}
