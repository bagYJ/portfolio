<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\BookingTypeCode;
use App\Enums\EnumOilOrderType;
use App\Enums\EnumYN;
use App\Enums\GasKind;
use App\Enums\PaymentMethodCode;
use App\Enums\ServicePayCode;
use App\Exceptions\OwinException;
use App\Jobs\ProcessArkServer;
use App\Queues\Socket\ArkServer;
use App\Services\CodeService;
use App\Services\CouponService;
use App\Services\GsPointService;
use App\Services\MemberService;
use App\Services\OilService;
use App\Services\OrderOilService;
use App\Services\OrderService;
use App\Services\ShopOilPriceService;
use App\Services\ShopService;
use App\Utils\Code;
use App\Utils\Common;
use App\Utils\Oil;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Throwable;

//oil
class OrderOil extends Controller
{
    /**
     * 주문 요청정보
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function intro(Request $request): JsonResponse
    {
        $request->validate([
            'no_shop' => 'required|integer',
        ]);

        $member = Auth::user();
        $noShop = intval($request->get('no_shop'));

        $shopInfo = ShopService::getShop($noShop);
        $cdBizKind = Code::conf('biz_kind.oil');

        ## 주유 예약내역확인
        ## 주유 예약건있을경우 처리완료전 추가 예약 안됨
        $orderInfo = OrderService::getUserOrderInfo([
            ['a.no_user', '=', $member['no_user']],
            ['a.no_shop', '=', $noShop],
            ['b.cd_biz_kind', '=', $cdBizKind],
            ['a.cd_pickup_status', '<', 602400],
            ['a.cd_order_status', '=', '601200'],
            ['a.cd_payment_status', '=', '603100'],
        ]);

        $unUseCards = array_unique(ShopService::getShopUnUseCards($noShop)->pluck('cd_card_corp')->all());
        $response = [
            'no_order' => $orderInfo?->no_order,
            'cars' => $member->memberCarInfoAll->map(function ($collect) {
                $collect->cd_gas_kind = GasKind::from(intval($collect->cd_gas_kind))->name;
                return $collect;
            })->sortByDesc('yn_main_car')->values(),
            'oil_prices' => ShopOilPriceService::shopOilPrice($noShop),
            'cards' => $member->memberCard->filter(function ($query) use ($unUseCards) {
                return !in_array($query['cd_card_corp'], $unUseCards) && $query['cd_pg'] === '500100';
            })->map(function ($collect) {
                return [
                    'no_seq' => $collect->no_seq,
                    'cd_card_corp' => $collect->cd_card_corp, //const 로 변경 필요
                    'card_corp' => CodeService::getCode($collect->cd_card_corp)->nm_code,
                    'no_card' => $collect->no_card,
                    'no_card_user' => $collect->no_card_user,
                    'nm_card' => $collect->nm_card,
                    'yn_main_card' => $collect->yn_main_card,
                    'yn_credit' => $collect->yn_credit,
                ];
            })->sortByDesc('yn_main_card')->values(),
            'coupons' => (new CouponService())->myOilCoupons($member['no_user'], 'Y', getAppType()->value)->map(function ($collect) {
                return [
                    'no' => $collect->no,
                    'ds_cpn_no_internal' => $collect->ds_cpn_no_internal,
                    'ds_cpn_no' => $collect->ds_cpn_no,
                    'no_user' => $collect->no_user,
                    'no_partner' => $collect->no_partner,
                    'use_coupon_yn' => $collect->use_coupon_yn,
                    'ds_cpn_nm' => $collect->ds_cpn_nm,
                    'use_disc_type' => $collect->use_disc_type,
                    'at_disct_money' => $collect->at_disct_money,
                    'at_limit_money' => $collect->at_limit_money,
                    'cd_payment_card' => $collect->cd_payment_card,
                    'at_condi_liter' => $collect->at_condi_liter,
                    'cd_mcp_status' => $collect->cd_mcp_status,
                    'cd_cpe_status' => $collect->cd_cpe_status,
                    'no_event' => $collect->no_event,
                    'gs_sale_card' => $collect->gsSaleCard,
                ];
            })->values(),
            'member_deal' => (new MemberService())->getMemberDealInfo($member['no_user']),
            'point_card' => match ($shopInfo['no_partner'] === Code::conf('oil.gs_no_partner')) {
                true => $member->memberPointCard->map(function ($collect) {
                    $data = [
                        'id_pointcard' => $collect->id_pointcard,
                        'dt_reg' => $collect->dt_reg,
                        'yn_sale_card' => $collect->yn_sale_card,
                        'yn_delete' => $collect->yn_delete,
                        'no_deal' => $collect->no_deal,
                        'gs_sale_card' => $collect->gsSaleCard,
                    ];
                    if ($collect->promotionDeal) {
                        $data['promotion_deal'] = [
                            'no_deal' => $collect->promotionDeal->no_deal,
                            'nm_deal' => $collect->promotionDeal->nm_deal,
                            'at_pin_total' => $collect->promotionDeal->at_pin_total,
                            'at_disct_price' => $collect->promotionDeal->at_disct_price,
                            'at_taget_liter' => $collect->promotionDeal->at_taget_liter,
                            'at_disct_limit' => $collect->promotionDeal->at_disct_limit,
                            'cd_deal_type' => $collect->promotionDeal->cd_deal_type,
                            'dt_deal_use_st' => $collect->promotionDeal->dt_deal_use_st,
                            'dt_deal_use_end' => $collect->promotionDeal->dt_deal_use_end,
                            'ds_gs_sale_code' => $collect->promotionDeal->ds_gs_sale_code,
                            'ds_bandwidth_st' => $collect->promotionDeal->ds_bandwidth_st,
                            'ds_bandwidth_end' => $collect->promotionDeal->ds_bandwidth_end,
                        ];
                    }
                    return $data;
                })->first(),
                default => null,
            },
        ];

        $response['gs_sale_mount'] = 0;
        if ($response['point_card']) {
            if ($response['member_deal'] && $response['member_deal']['dt_deal_use_end'] > date('Y-m-d')) {
                $response['gs_sale_mount'] = data_get($response, 'point_card.promotion_deal.at_disct_price');
            }

            $gsInfo = [
                'gs_sale_mount' => 0,
                'gs_sale_amt_advice' => 0,
            ];

            // 르노회원
            $pointCardId = $response['point_card']['id_pointcard'];
            if (Code::conf('oil.gs_sale_card_min_rsm') <= $pointCardId && Code::conf(
                'oil.gs_sale_card_max_rsm'
            ) >= $pointCardId) {
                $gsInfo['gs_sale_mount'] = Code::conf('oil.gs_sale_rsm_car_id');
                $gsInfo['gs_sale_amt_advice'] = Code::conf('oil.gs_sale_rsm_car_id');
            } elseif (Code::conf('oil.gs_sale_card_min') <= $pointCardId && Code::conf(
                'oil.gs_sale_card_max'
            ) >= $pointCardId) {
                $gsInfo['gs_sale_mount'] = Code::conf('oil.gs_sale_basic_car_id');
            } else {
                $gsInfo['gs_sale_mount'] = Code::conf('oil.gs_sale_basic_no_car_id');
                $gsInfo['gs_sale_amt_advice'] = Code::conf('oil.gs_sale_basic_car_id');
            }

            if ($response['point_card']['yn_sale_card'] === 'Y') {
                // GS 현장할인카드 잔여한도 소진여부가 'N'인 경우
                // GS 카드정보 조회 및 잔여한도에 따라 현장할인 월 잔여한도 소진여부 업데이트
                //if ($gs_sale_card_info['yn_can_save'] == 'N')
                //{
                // 현장할인 포인트 카드 정보 조회 전문 통신
                $gsSaleCard = GsPointService::getPointCardInfo(Auth::user(), $pointCardId);
                if ($gsSaleCard) {
                    $gsInfo['at_can_save_amt'] = intval($gsSaleCard['at_can_save_amt']); // 현장할인 월 잔여한도
                    $gsInfo['at_can_save_total'] = intval($gsSaleCard['at_can_save_total']) ; // 현장할인 월한도
                    $gsInfo['at_save_amt'] = intval($gsSaleCard['at_save_amt']); //현장할인 월 누적할인금액
                }
            }
            $response['point_card']['gs_info'] = $gsInfo;

            if (strpos($pointCardId, Code::conf('oil.gs_bin_card_no'))) {
                $response['coupons'] = $response['coupons']->filter(function ($query) {
                    return in_array($query['use_disc_type'], ['0', '00']);
                });
            }
        }

        $response['cash_info'] = [
            'at_cash_use' => $member->at_use_cash ?? 0,
            'at_use_point' => $member->at_use_point ?? 0,
        ];

        return response()->json($response);
    }

    /**
     * 결제 요청
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function payment(Request $request): JsonResponse
    {
        $request->validate([
            'no_shop' => 'required|integer',
            'cd_service_pay' => ['required', Rule::in(ServicePayCode::keys())],
            'at_price' => 'required|numeric|min:0', // 결제 금액
            'cd_gas_kind' => ['required', Rule::in(GasKind::keys())],
            'no_card' => 'required',
            'car_number' => 'required',
            'order_type' => ['required', Rule::in(EnumOilOrderType::keys())],
            'at_gas_price' => 'required|numeric|min:0', // 리터당 금액

            'at_liter_gas' => 'nullable|numeric|min:0', // 주유되는 리터량
            'cd_payment_method' => ['nullable', Rule::in(PaymentMethodCode::keys())],
            'at_disct' => 'nullable|numeric|min:0',
            'at_cpn_disct' => 'nullable|numeric|min:0',
            'at_owin_cash' => 'nullable|numeric|min:0',
            'at_point_disct' => 'nullable|numeric|min:0',

            'discount_info' => 'nullable',
            'yn_gps_status' => ['nullable', Rule::in(EnumYN::keys())],
            'cd_booking_type' => ['nullable', Rule::in(BookingTypeCode::keys())],
        ]);

        Auth::user()->memberCarInfo->where(
            'ds_car_number',
            $request->car_number
        )->get()->whenEmpty(function () {
            throw new OwinException(Code::message('M1510'));
        });

        $shop = ShopService::getShop($request->no_shop);
        if ($shop->ds_status != EnumYN::Y->name) {
            throw new OwinException(Code::message('M1304'));
        }

        $user = Auth::user();
        if ($user->cd_mem_level == '104500') {
            $user->cd_third_party = '110100';
        }

        $orderService = new OrderOilService();
        $response = $orderService->reservation(
            $user,
            $shop,
            collect($request->all()),
        );
        if ($response['result'] === true) {
            ProcessArkServer::dispatch(
                new ArkServer(
                    'ORDER',
                    'orderStatus',
                    $request->no_shop . '00000000' . $response['no_order'],
                    '11'
                )
            )->onConnection('database');
        }
        return response()->json([
            'result' => $response['result'],
            'no_order' => $response['no_order'],
            'nm_order' => $response['nm_order']
        ]);
    }

    /**
     * 주유소 등록된 스티커번호 전달 [번호입력 스티커정보]
     * @param Request $request
     *
     * @return JsonResponse
     * @throws OwinException
     */
    public function oilDpList(Request $request, $noOrder): JsonResponse
    {
        $orderInfo = OrderService::getOrder($noOrder);
        if (!$orderInfo) {
            throw new OwinException(Code::message('P2120'));
        }

        // 종료 주문건의 경우 에러코드
        if ($orderInfo['cd_pickup_status'] >= '602400' && $orderInfo['cd_payment_status'] === '603300') {
            //결제정상처리시에만 전달
            throw new OwinException(Code::message('P2404'));
        }

        return response()->json([
            'result' => true,
            'list' => OilService::getUnitInfo($orderInfo['no_shop'])->unique('ds_display_ark_id')->pluck('ds_display_ark_id')->all()
        ]);
    }

    /**
     * 주문 상세
     * @param string $noOrder
     *
     * @return JsonResponse
     */
    public function detail(string $noOrder): JsonResponse
    {
        ##6 RSM 로그인정보 반환
        $memberInfo = Auth::user();
        $noUser = $memberInfo['no_user'];
        $cdGasKind = $memberInfo->memberCarInfo->cd_gas_kind;

        $orderService = new OrderService();
        $orderInfo = $orderService->getOrderInfo([
            'no_user' => Auth::id(),
            'no_order' => $noOrder
        ])->first();

        $response = [];

        $memberShopEnterLog = OrderService::getMemberShopEnterLog([
            'no_user' => $noUser,
            'no_order' => $noOrder,
            'yn_is_in' => 'N'
        ]);

        $currentMsg = "";

        $cdOrderProcess = $orderInfo['orderProcess']['cd_order_process'] ?? null;

        //한도조회 실패
        if ($cdOrderProcess === '616999') {
            $dsResRefundCode = $orderInfo['orderPayment']['ds_res_code_refund'] ?? null;
            if ($dsResRefundCode === '8326') {
                // 한도초과오류
                $currentMsg = "예약 시 선택하신 카드는 한도 초과로 사용할 수 없습니다.\n다른 카드를 등록/선택한 후 주유를 예약해 주세요.";
            } else {
                //한도초과 이외의 오류 8300
                $currentMsg = "예약 시 선택하신 카드는 사용할 수 없는 카드 입니다.\n다른 카드를 등록/선택한 후 주유를 예약해 주세요";
            }
        } elseif ($orderInfo['cd_order_status'] === '601900' && $orderInfo['cd_pickup_status'] === '602400' && $orderInfo['cd_payment_status'] === '603900') {
            $cdOrderProcess = '616991';
        }
        if ($cdOrderProcess === '616920') {
            // 주유기상태확인 / 프리셋상태
            if (isset($memberShopEnterLog['nt_unit_id_status'])) {
                // 주유기상태확인 - 노즐이 정상상태가 아닐경우
                if ($orderInfo['yn_self'] === 'Y') {
                    // 셀프 주유소
                    $currentMsg = "선택한 주유기는 현재 사용 중입니다.\n주유기 노즐이 주유기에 거치가 되어 있는 지\n확인 후 다시 시도해 주세요.";
                } else {
                    // 풀서비스 주유소
                    $currentMsg = "선택한 주유기는 현재 사용 중입니다.\n주유기 사용 가능할 때 다시 시도해 주세요.";
                }
            }
        }
        if ($cdOrderProcess === '616930') {
            // 정차판단불가
            if ($orderInfo['yn_self'] === 'Y') {
                // 셀프 주유소
                $currentMsg = "차량의 위치를 확인하지 못하였습니다.\n주유기에 부착된 오윈 번호로 주유를 진행하시겠습니까?";
            } else {
                // 풀서비스 주유소
                $currentMsg = "차량의 위치를 확인하지 못하였습니다.\n주유소 직원의 도움을 통해 차량을 이동하거나,\n번호 선택을 통해 주유기에 부착된 오원 번호로\n주유를 진행해 주세요.";
            }
        }

        if ($cdOrderProcess === '616910') {
            // 유종오류
            if ($orderInfo['yn_self'] === 'Y') {
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
            OrderService::registOrder([
                'no_user' => $noUser,
                'no_order' => $noOrder,
                'no_shop' => $orderInfo['no_shop'],
                'cd_order_process' => $cdOrderProcess,
            ]);
        }

        # 결제금액 확인후 > 조건별 브랜드쿠폰발행 - 테스트시 히든
        OrderOilService::getReceiveCouponAfterPay($noUser);

        return response()->json([
            'result' => true,
            'nm_shop' => $orderInfo->nm_shop,
            'no_order' => $orderInfo->no_order,
            'no_order_user' => substr($orderInfo->no_order, -7),
            'nm_order' => $orderInfo->nm_order,
            'dt_reg' => $orderInfo->dt_reg->format('Y-m-d H:i:s'),
            'at_commission_rate' => $orderInfo->at_commission_rate,
            'at_send_price' => $orderInfo->at_send_price,
            'at_disct' => $orderInfo->at_disct,
            'at_cpn_disct' => $orderInfo->at_cpn_disct,
            'cd_gas_kind' => $orderInfo->cd_gas_kind,
            'at_gas_price' => $orderInfo->at_gas_price,
            'at_liter_real' => $orderInfo->at_liter_real,
            'yn_gas_order_liter' => $orderInfo->yn_gas_order_liter,
            'at_price' => $orderInfo->at_price,
            'at_price_pg' => $orderInfo->at_price_pg,
            'cd_status' => $orderInfo->cd_status,
            'nm_status' => $orderInfo->nm_status,
            'current_msg' => $currentMsg,
            'yn_preset' => ($cdOrderProcess > 616100 && $cdOrderProcess < 616500) ? "Y" : "N",
            'list_product' => $orderInfo->list_product
        ]);
    }

    /**
     * 결제 취소
     * @param string $noOrder
     *
     * @return JsonResponse
     */
    public function cancel(string $noOrder): JsonResponse
    {
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
                throw new OwinException(Code::message('P2140'));
            }
        });

        $result = (new OrderOilService())->cancel($orderInfo);

        if ($result) {
            ProcessArkServer::dispatch(new ArkServer('ORDER', 'orderStatus', $orderInfo['no_shop'] . '1', '14'))
                ->onConnection('database');
        }

        return response()->json([
            'result' => $result
        ]);
    }

    /**
     * 주문확인 - [앱실행시] // oil_order_check
     * 앱실행시 현재시간기준 30분이내에 주유소 INTIME 체크후 주문정보있을경우 주문정보 전달
     * 주문정보 없을경우 no_order = 0
     * 로그 없음
     *
     * @return JsonResponse
     */
    public function check(string $noOrder): JsonResponse
    {
        $noUser = Auth::id();
        $shopEnterLog = OrderService::getMemberShopEnterLog([
            'no_order' => $noOrder,
            'no_user' => $noUser,
        ]);

        // 도착 내역이 없으면 리턴.
        if (!$shopEnterLog) {
            return response()->json(null, 404);
        }

        // 최근 기록이 카인이 아니라면 리턴.
        if ($shopEnterLog['yn_is_in'] !== 'Y') {
            return response()->json(null, 404);
        }

        // 회원의 주유소 상태.(in상태체크 )
        if (strtotime(date('Y-m-d H:i:s')) - strtotime($shopEnterLog['dt_reg']) > Code::conf('oil.arrival_check_time') * 60) {
            // 최근 도착한 시간이 초과 되었으면 리턴.
            return response()->json(null);
        }

        $shopInfo = ShopService::getShop($shopEnterLog['no_shop']);

        $orderInfo = (new OrderService())->getOrderInfo([
            ['no_user', '=', $noUser],
            ['no_order', '=', $noOrder]
        ])->sortByDesc('order_list.dt_reg')->first();

        $workingTime = true;
        $preCheckTime = false;
        if ($shopInfo['shopHolidayExists'] || $shopInfo['shopOptTimeExists']) {
            $workingTime = false;
        }

        $chkDate = date('H') * 60 + date('i');
        $unOrderTime = Code::conf('oil.unorder_hour') * 60 + Code::conf('oil.unorder_minute') - 1;
        if ($chkDate > $unOrderTime && $chkDate < 1439) {
            $preCheckTime = true;
            $target = 'A';
        } else {
            $target = $workingTime ? 'O' : 'A';
        }

        return response()->json([
            'ds_adver' => $shopEnterLog['ds_adver'],
            'target' => $target,
            'sno' => $shopInfo['no_shop'],
            'ono' => $orderInfo['no_order'] ?? '0' . '|' . $shopEnterLog['ds_adver'],
            'fno' => $shopInfo['shopDetail']['yn_self'],
            'smno' => $shopInfo['partner']['nm_partner'] . ' ' . $shopInfo['nm_shop'],
            'image' => Common::getImagePath($shopInfo['shopDetail']['ds_image1']),
            'message' => Oil::arrivalMsg($shopInfo, $orderInfo, $workingTime, $preCheckTime),
            'chk_no_shop' => $shopInfo['no_shop'],
        ]);
    }

    /**
     * 주유현재 진행상황 전달 [번호입력개발]
     * @param Request $request
     *
     * @return JsonResponse
     * @throws OwinException
     */
    public function presetCheck(Request $request): JsonResponse
    {
        $request->validate([
            'no_order' => 'required'
        ]);
        $memberInfo = Auth::user();
        $noOrder = $request->get('no_order');
        $orderInfo = OrderService::getOrder($request->get('no_order'));
        if (!$orderInfo) {
            throw new OwinException(Code::message('P2120'));
        }

        $orderProcess = OrderService::getRecentOrderProcess($noOrder, $memberInfo['no_user']);
        $memberShopEnterLog = OrderService::getMemberShopEnterLog([
            'no_user' => $memberInfo['no_user'],
            'no_order' => $noOrder,
            'yn_is_in' => 'M'
        ]);

        $message = "";
        if ($orderProcess['cd_order_process'] === '616920') { // 주유기상태확인 / 프리셋상태
            // 주유기상태확인 - 노즐이 정상상태가 아닐경우
            if ($memberShopEnterLog['nt_unit_id_status'] !== 0) {
                if ($orderInfo['shop']['shopDetail']['yn_self'] === 'Y') { //self
                    $message = "선택한 주유기는 현재 사용 중입니다.\n주유기 노즐이 주유기에 거치가 되어 있는 지\n확인 후 다시 시도해 주세요.";
                } else { //full service
                    $message = "선택한 주유기는 현재 사용 중입니다.\n주유기 사용 가능할 때 다시 시도해 주세요.";
                }
            }
        } elseif ($orderProcess['cd_order_process'] === '616999') { //한도조회 실패
            $dsResRefund = $orderInfo['orderPayment']['ds_res_refund'];
            if ($dsResRefund === '8326') {
                $message = "예약 시 선택하신 카드는 한도 초과로 사용할 수 없습니다.\n다른 카드를 등록/선택한 후 주유를 예약해 주세요.";
            } else {
                $message = "예약 시 선택하신 카드는 사용할 수 없는 카드 입니다.\n다른 카드를 등록/선택한 후 주유를 예약해 주세요";
            }
        } elseif ($orderProcess['cd_order_process'] === '616930') { //장치 판단 불가
            if ($orderInfo['shop']['shopDetail']['yn_self'] === 'Y') { //self
                $message = "차량의 위치를 확인하지 못하였습니다.\n주유기에 부착된 오윈 번호로 주유를 진행하시겠습니까?";
            } else { //full service
                $message = "차량의 위치를 확인하지 못하였습니다.\n주유소 직원의 도움을 통해 차량을 이동하거나,\n번호 선택을 통해 주유기에 부착된 오원 번호로\n주유를 진행해 주세요.";
            }
        } elseif ($orderProcess['cd_order_process'] === '616910') { // 유종오류
            if ($orderInfo['shop']['shopDetail']['yn_self'] === 'Y') { //self
                $message = "선택한 주유기는  '" . Code::conf(
                    "gas_kind_product_name.{$orderInfo['cd_gas_kind']}"
                ) . "'가 없는 주유기입니다.\n다른 주유기를 이용해 주세요.";
            } else { //full service
                $message = "선택한 주유기는  '" . Code::conf(
                    "gas_kind_product_name.{$orderInfo['cd_gas_kind']}"
                ) . "'가 없는 주유기입니다.\n점원의 안내를 통해 다른 주유기로 이동해 주세요.";
            }
        }

        $updateProcess = 'N';
        if ($message && $orderProcess['cd_order_process'] !== '616999') {
            //주유기사용중으로 주유진행상태 초기화  - 616100
            $updateProcess = 'Y';
            OrderService::registOrderProcess([
                'no_order' => $noOrder,
                'no_user' => $memberInfo['no_user'],
                'no_shop' => $orderInfo['no_shop'],
                'cd_order_process' => '616100',
                'dt_order_process' => Carbon::now(),
            ]);
        }

        // 종료 주문건의 경우 에러코드
        if ($orderInfo['cd_pickup_status'] >= '602400' && $orderInfo['cd_payment_status'] === '603300') {
            //결제정상처리시에만 전달
            throw new OwinException(Code::message('P2404'));
        }

        //todo return data 확인 필요
        return response()->json([
            'yn_update' => $updateProcess,
            'nizzle_status' => $memberShopEnterLog['nt_unit_id_status'] ?? null,
            'cd_order_process' => $orderProcess['cd_order_process'],
            'yn_self' => $orderInfo['shop']['shopDetail']['yn_self'],
            'yn_preset' => $orderProcess['cd_order_process'] > 616100 && $orderProcess['cd_order_process'] < 616500 ? 'Y' : 'N',
            'message' => $message,
        ]);
    }


    /**
     * 주유소 유종 정보 반환
     * @param int $noShop
     *
     * @return JsonResponse
     */
    public function oilGasList(int $noShop): JsonResponse
    {
        $response = ShopOilPriceService::shopOilPrice($noShop);

        return response()->json([
            'result' => $response->count() > 0,
            'oil_prices' => $response
        ]);
    }

    /**
     * @throws Throwable
     * @throws OwinException
     */
    public function qrRegist(Request $request) {
        $request->validate([
            'no_order' => 'required|string',
            'ds_display_ark_id' => 'required|string',
        ]);

        $displayArkId = $request->get('ds_display_ark_id');
        $noOrder = $request->get('no_order');
        $noUser = Auth::id();
        $ynSocketSend = true;
        // 주문정보조회
        $orderInfo = (new OrderService())->getOrderInfo([
            'no_user' => Auth::id(),
            'no_order' => $noOrder,
        ])->whenEmpty(function () {
            throw new OwinException(Code::message('P2120'));
        })->first();

        $noOilCompany = $orderInfo['no_partner'] == Code::conf('oil.ex_no_partner') ? "2" : "1";
        $lastId = OilService::registQrReader([
            'no_oil_company' => $noOilCompany,
            'no_shop' => $orderInfo['no_shop'],
            'ds_display_ark_id' => $displayArkId,
            'cd_oil_confirm_type' => "506200"
        ]);

        $arkList = OilService::checkOilDpArk($orderInfo['no_shop'], $displayArkId);
        if (!count($arkList)) {
            throw new OwinException(Code::message('P2405'));
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
            'result' => true
        ]);
    }

    public function orderProcessOil(string $noOrder) {
        $noUser = Auth::id();
        // 주문정보조회
        $orderInfo = (new OrderService())->getOrderInfo([
            'no_user' => $noUser,
            'no_order' => $noOrder,
        ])->whenEmpty(function () {
            throw new OwinException(Code::message('P2120'));
        })->first();
        ## 주유진행상태
        $orderProcess = OrderService::getRecentOrderProcess($noOrder, $noUser);
        if (!$orderProcess) {
            throw new OwinException(Code::message('P2811'));
        }
        $response = match ($orderProcess['cd_order_process']) {
            '616200' => match ($orderInfo['shop']['yn_self'] == 'Y') {
                false => [
                    'result' => true,
                    'message' => "예약한 금액이 주유기에 설정되었습니다.\n잠시 후 주유원이 주유를 진행할 예정입니다.",
                    'detail' => "주유 노즐을 들었다 내려놓거나,\n60초간 주유를 시작하지 않으면 주문이 자동 취소됩니다.",
                ],
                default => [
                    'result' => true,
                    'message' => "예약한 금액이 주유기에 설정되었습니다.\n주유기 화면 안내에 따라 주유를 해주세요.",
                    'detail' => "주유 노즐을 들었다 내려놓거나,\n60초간 주유를 시작하지 않으면 주문이 자동 취소됩니다.",
                ]
            },
            '616300' => match ($orderInfo['shop']['yn_self'] == 'Y') {
                false => [
                    'result' => true,
                    'message' => "주유 진행 중입니다.\n잠시만 기다려 주세요.",
                    'detail' => "주유가 완료되면 주유된\n최종 금액이 결제됩니다.",
                ],
                default => [
                    'result' => true,
                    'message' => "주유 완료 후 노즐을 주유기에\n거치해 주세요.",
                    'detail' => "노즐을 정상적으로 거치하지 않을 경우\n최대 예약한 금액까지 결제 될 수 있습니다.",
                ]
            },
            '616400' => [
                'result' => true,
                'message' => "주유가 완료되어\n결제가 진행 중입니다.",
                'detail' => "최종 주유된 금액으로 결제가 진행 중입니다.\n잠시만 기다려주세요.",
            ],
            '616500' => [
                'result' => true,
                'message' => "결제가 완료되었습니다."
            ],
            '616950' => [
                'result' => false,
                'message' => "주유가 취소되었습니다."
            ],
            '616990' => [
                'result' => false,
                'message' => "주유를 진행하지 않아 주유기 설정을 취소하였습니다"
            ],
            '616991' => match ($orderInfo['cd_payment_status']) {
                '603900' => [
                    'result' => false,
                    'message' => '예약 취소 되었습니다.'
                ],
                default => [
                    'result' => true,
                    'message' => "",
                ],
            },
            '616999' => [
                'result' => false,
                'message' => "결제가 정상적으로\n이루어 지지 않았습니다.\n직원을 통해 직접 결제를 진행해 주세요."
            ],
            default => [
                'result' => true,
                'message' => "",
            ],
        };

        $enterLog = OrderService::getMemberShopEnterLog([
            'no_user' => $noUser,
            'no_order' => $noOrder,
            'yn_is_in' => 'M'
        ]);

        // 주유기상태확인 - 노즐이 정상상태가 아닐경우
        if ($enterLog && $enterLog['nt_unit_id_status'] != 0 && $orderProcess['cd_order_process'] == '616100') {
            $response = match ($orderInfo['shop']['yn_self'] == 'Y') {
                false => [
                    'result' => false,
                    'message' => "현재 정차하신 주유기의 주유기가 사용 중으로\n확인되었습니다.\n주유기에 연결된 노즐의 손잡이가 주유기에 거치가 잘 되어 있는 지\n확인 후 오윈 번호를 입력해 주세요. "
                ],
                default => [
                    'result' => false,
                    'message' => "현재 정차하신 주유기의 주유기가 사용 중으로\n확인되었습니다.\n주유기가 사용가능 할 때 오윈 번호를 입력해 주세요."
                ]
            };
        }

        // 결제실패
        if($orderInfo['cd_payment_status'] == '603200' && $orderInfo['cd_pickup_status'] == '602400')
        {
            $response = [
                'result' => false,
                'message' => "결제가 정상적으로\n이루어지지 않았습니다.\n직원을 통해 직접 결제를 진행해 주세요."
            ];
        }

        $response['nm_status'] = CodeService::getCode($orderProcess['cd_order_process'])->nm_code;
        $response['cd_order_process'] = $orderProcess['cd_order_process'];
        $response['order'] = [
            'no_shop' => $orderInfo->no_shop,
            'nm_shop' => $orderInfo->nm_shop,
            'no_order' => $orderInfo->no_order,
            'no_order_user' => substr($orderInfo->no_order, -7),
            'nm_order' => $orderInfo->nm_order,
            'dt_reg' => $orderInfo->dt_reg->format('Y-m-d H:i:s'),
            'at_commission_rate' => $orderInfo->at_commission_rate,
            'at_send_price' => $orderInfo->at_send_price,
            'at_disct' => $orderInfo->at_disct,
            'at_cpn_disct' => $orderInfo->at_cpn_disct,
            'cd_gas_kind' => $orderInfo->cd_gas_kind,
            'at_gas_price' => $orderInfo->at_gas_price,
            'at_liter_real' => $orderInfo->at_liter_real,
            'yn_gas_order_liter' => $orderInfo->yn_gas_order_liter,
            'at_price' => $orderInfo->at_price,
            'at_price_pg' => $orderInfo->at_price_pg,
            'cd_status' => $orderInfo->cd_status,
            'list_product' => $orderInfo->list_product
        ];

        if ($orderInfo['wash_in_shop']) {
            $response['wash_in_shop'] = [
                'no_shop' => $orderInfo['wash_in_shop']['no_shop'],
                'nm_shop' => $orderInfo['wash_in_shop']['partner']['nm_partner'] . ' ' . $orderInfo['nm_shop'],
            ];
        }
        return response()->json($response);
    }
}
