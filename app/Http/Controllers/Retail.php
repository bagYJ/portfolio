<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\OptionType;
use App\Enums\Pg;
use App\Exceptions\OwinException;
use App\Jobs\ProcessFcm;
use App\Queues\Fcm\Fcm;
use App\Response\Retail\ProductInfo;
use App\Services\MemberService;
use App\Services\OrderRetailService;
use App\Services\OrderService;
use App\Services\PartnerService;
use App\Services\Pg\PgService;
use App\Services\ProductService;
use App\Services\RetailAdminCheckLogService;
use App\Services\RetailProductService;
use App\Services\RetailService;
use App\Services\ReviewService;
use App\Services\SearchService;
use App\Services\ShopService;
use App\Services\VirtualNumberService;
use App\Utils\BizCall;
use App\Utils\Code;
use App\Utils\Common;
use App\Utils\Cu;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Throwable;

class Retail extends Controller
{
    /**
     * [1.상품 재고조회 요청]  ( Owin -> retail(CU) )
     * @param Request $request
     * @return JsonResponse
     * @throws GuzzleException
     * @throws OwinException
     */
    public function productCheck(Request $request): JsonResponse
    {
        $request->validate([
            'no_shop' => 'required|integer',
            'no_product' => 'required|array',
            'no_product.*' => 'integer'
        ]);

        $shop = ShopService::getShop($request->no_shop);
        if (empty($shop->store_cd)) {
            throw new OwinException(Code::message('M1303'));
        }

        $transDt = date('YmdHis');
        $productList = RetailService::getRetailNoBarcode($request->no_product);
        $response = Cu::send(
            Code::conf('cu.api_uri') . Code::conf('cu.path.product_check'),
            [
                'partner_code' => $shop->no_partner,
                'shop_code' => $shop->store_cd,
                'product_list' => RetailProductService::getRetailProductIds($productList),
                'trans_dt' => $transDt,
                'sign' => Cu::generateSign([$shop->no_partner, $shop->store_cd, $transDt])
            ],
        );
//        $code = CodeService::getGroupCode('621');

        return response()->json([
            'result' => $response['result'],
            'product_list' => $productList->map(function ($product) use ($response) {
                return [
                    'no_product' => $product->no_product,
                    'cnt_product' => data_get($response['product_list'], $product->no_barcode),
                    'option' => $product->productOptionGroups?->map(function ($group) use ($response) {
                        return $group->productOptionProducts->map(function ($option) use ($response) {
                            return [
                                'no_option' => $option->no_option,
//                                'required' => $code->where('no_code', $group->cd_option_type)->first()->nm_code,
                                'cnt_product' => data_get($response['product_list'], $option->no_barcode_opt)
                            ];
                        });
                    })->flatten(1)
                ];
            })
        ]);
    }

    /**
     * [4.주문 접수 / 거절]   (  retail(CU) -> Owin )
     * @return JsonResponse
     */
    public function orderConfirm(Request $request): string
    {
        try {
            $cuRequest = makeCuRequest();
            $request->merge([
                'partner_code' => $cuRequest->partner_code,
                'shop_code' => $cuRequest->shop_code,
                'no_order' => $cuRequest->no_order,
                'yn_cancel' => $cuRequest->yn_cancel,
                'cd_reject_reason' => $cuRequest->cd_reject_reason ?? null,
                'trans_dt' => $cuRequest->trans_dt,
                'sign' => $cuRequest->sign,
            ])->validate([
                'partner_code' => 'required|string',
                'shop_code' => 'required|string',
                'no_order' => 'required|string',
                'yn_cancel' => 'required|in:Y,N',
                'cd_reject_reason' => 'nullable|string',
                'trans_dt' => 'required|string',
                'sign' => 'required|string',
            ]);

            $changeSign = Cu::generateSign([$request->partner_code, $request->shop_code, $request->no_order, $request->trans_dt]);
            Cu::hashAccuracyCheck($request->sign, $changeSign, $request->all());

            $noPartner = $request->partner_code === Code::conf('cu.partner_code') ? Code::conf('cu.partner_no') : '';
            $shop = RetailService::getRetailShop($noPartner, $request->shop_code, $request->all());

            if ($request->yn_cancel == 'Y') {
                $request->no_order = Cu::changeNoOrder('CU', $request->no_order);
                $request->merge([
                    'no_partner' => $noPartner,
                    'no_shop' => $shop->no_shop,
                    'cd_reject_reason' => '601950'
                ]);

                return base64_decode(json_encode((array)$this->orderCancel($request)));
            }
            $orderInfo = RetailService::getOrderInfo(Cu::changeNoOrder('CU', $request->no_order), $request->all());

            //// [ERROR] 기취소된 주문건
            if ($orderInfo['cd_order_status'] >= '601900') {
                RetailService::insertRetailExternalResultLog($request->all(), [
                    'result' => false,
                    'result_code' => '9970'
                ]);
                throw new OwinException(Code::message('9970'));
            }

            //주문 접수일 경우 cd_pickup_status -> 602200  으로 업데이트

            RetailService::updateOrderInfo([
                'no_user' => $orderInfo->no_user,
                'no_shop' => $orderInfo->no_shop,
                'no_order' => $orderInfo->no_order,
                'cd_pickup_status' => '602200',
                'cd_alarm_event_type' => '607050',
                'confirm_date' => now()
            ]);

            $nmShop = sprintf('%s %s', $shop->partner->nm_partner, $shop->nm_shop);

            $timeText = Config('meta.time')[$orderInfo->dt_pickup->format('a')] . " " . $orderInfo->dt_pickup->format('h:i');

            $fcmData = array("ordering" => 'Y', "nm_shop" => $nmShop, "pickup_time" => $timeText);
            ProcessFcm::dispatch(
                new Fcm("RETAIL", $orderInfo->no_shop, $orderInfo->no_order, $fcmData, true, 'user', $orderInfo->no_user, "shop_accept")
            )->onConnection('database');

            $response = [
                'result' => true,
                'partner_code' => $request->partner_code,
                'shop_code' => $request->shop_code,
                'result_code' => '0000',
                'result_msg' => Code::message('0000')
            ];
            RetailService::insertRetailExternalResultLog($request->all(), $response);

            return base64_encode(json_encode($response));
        } catch (Throwable $t) {
            return base64_encode(json_encode([
                'result' => false,
                'partner_code' => $request->partner_code,
                'shop_code' => $request->shop_code,
                'result_code' => '9990',
                'result_msg' => $t->getMessage()
            ]));
        }
    }

    /**
     * [5.주문 자동취소 전 알림]  ( Owin (batch) -> retail(CU) )
     * @param Request $request
     * @return JsonResponse
     * @throws GuzzleException
     */
    public function cancelCheck(Request $request): JsonResponse
    {
        $request->validate([
            'no_partner' => 'required|integer',
            'no_shop' => 'required|integer',
            'no_order' => 'required|string'
        ]);

        $storeCd = RetailService::getRetailStoreCd($request->no_partner, $request->no_shop, $request->all());
        $orderInfo = RetailService::getOrderInfo($request->no_order, $request->all());
        $body = [
            'partner_code' => Code::conf('cu.partner_code'),
            'shop_code' => $storeCd,
            'no_order' => Cu::changeNoOrder('OWIN', $orderInfo->no_order),
            'nm_order' => $orderInfo->nm_order,
            'nm_nick' => $orderInfo->nm_nick,
            'dt_order' => $orderInfo->dt_reg->format('YmdHis'),
            'dt_pickup' => $orderInfo->dt_pickup->format('YmdHis'),
            'dt_pickup_type' => $orderInfo['ds_pickup_type'],
            'trans_dt' => now()->format('YmdHis'),
        ];
        $body['sign'] = Cu::generateSign([$body['partner_code'], $body['shop_code'], $body['no_order'], $body['trans_dt']]);

        $callUrl = Code::conf('cu.api_uri') . '/retail/cancel_check';
        $apiResponse = Cu::send($callUrl, $body);
        OrderRetailService::registMemberShopRetailLog([
            'no_user' => $orderInfo->no_user,
            'no_shop' => $orderInfo->no_shop,
            'no_order' => $orderInfo->no_order,
            'cd_alarm_event_type' => '607910',
        ]);

        $noPartner = $request->no_partner === Code::conf('cu.partner_code') ? Code::conf('cu.partner_no') : '';
        $shop = RetailService::getRetailShop($noPartner, $storeCd, $request->all());
        $nmShop = sprintf('%s %s', $shop->partner->nm_partner, $shop->nm_shop);
        $fcmData = array("ordering" => 'N', "nm_shop" => $nmShop);
        ProcessFcm::dispatch(
            new Fcm("RETAIL", $shop->no_shop, $orderInfo->no_order, $fcmData, true, 'user', $orderInfo->no_user, "cancel")
        )->onConnection('database');
        return response()->json([
            'result' => match ($apiResponse['result_code']) {
                '0000' => true,
                default => false
            },
        ]);
    }

    /**
     * [6.주문취소]  ( Owin -> retail(CU) )
     * @param Request $request
     * @return JsonResponse
     */
    public function orderCancel(Request $request): JsonResponse
    {
        $request->validate([
            'no_partner' => 'required|integer',
            'no_shop' => 'required|integer',
            'no_order' => 'required|string',
            'cd_reject_reason' => 'required|string',
        ]);

        $shop = ShopService::shop((int)$request->no_shop)->first();
        if (!$shop?->store_cd) {
            RetailService::insertRetailExternalResultLog($request->all(), [
                'result' => false,
                'result_code' => 'M1303',
            ]);
            throw new OwinException(Code::message('M1303'));
        }

        $orderInfo = RetailService::getOrderInfo($request->no_order, $request->all());
        $user = MemberService::getMember([
            'no_user' => $orderInfo->no_user
        ])->first();

        $response = (new OrderService())->refund(
            $user,
            $shop,
            (new PgService(Pg::from($orderInfo->cd_pg)->name))->setPg(),
            $orderInfo->no_order,
            '601950',
            $request->cd_reject_reason
        );

        $nmShop = sprintf('%s %s', $shop->partner->nm_partner, $shop->nm_shop);
        $fcmData = array("ordering" => 'N', "nm_shop" => $nmShop);
        ProcessFcm::dispatch(
            new Fcm("RETAIL", $orderInfo->no_shop, $orderInfo->no_order, $fcmData, true, 'user', $orderInfo->no_user, "cancel_etc")
        )->onConnection('database');

        return response()->json([
            'result' => match ($response['res_cd']) {
                '0000' => true,
                default => false
            },
            'message' => $response['res_msg'],
            'partner_code' => Code::conf('cu.partner_code'),
            'shop_code' => $shop->store_cd,
            'result_code' => $response['res_cd'],
            'result_msg' => $response['res_msg']
        ]);
    }

    /**
     * [7.준비완료]   (  retail(CU) -> Owin )
     * @param Request $request
     * @return JsonResponse
     */
    public function orderReady(Request $request): string
    {
        try {
            $cuRequest = makeCuRequest();
            $request->merge([
                'partner_code' => $cuRequest->partner_code,
                'shop_code' => $cuRequest->shop_code,
                'no_order' => $cuRequest->no_order,
                'trans_dt' => $cuRequest->trans_dt,
                'sign' => $cuRequest->sign,
            ])->validate([
                'partner_code' => 'required|string',
                'shop_code' => 'required|string',
                'no_order' => 'required|string',
                'trans_dt' => 'required|string',
                'sign' => 'required|string',
            ]);

            $changeSign = Cu::generateSign([$request->partner_code, $request->shop_code, $request->no_order, $request->trans_dt]);
            Cu::hashAccuracyCheck($request->sign, $changeSign, $request->all());

            $noPartner = $request->partner_code === Code::conf('cu.partner_code') ? Code::conf('cu.partner_no') : '';
            $shop = RetailService::getRetailShop($noPartner, $request->shop_code, $request->all());
            $orderInfo = RetailService::getOrderInfo(Cu::changeNoOrder('CU', $request->no_order), $request->all());

            //// [ERROR] 기취소된 주문건
            if ($orderInfo->cd_order_status >= '601900') {
                RetailService::insertRetailExternalResultLog($request->all(), [
                    'result' => false,
                    'result_code' => '9970'
                ]);
                throw new OwinException(Code::message('9970'));
            }

            if (!OrderRetailService::getOrderCompleteLogCnt($orderInfo->no_order)) {
                RetailService::updateOrderInfo([
                    'no_user' => $orderInfo->no_user,
                    'no_shop' => $orderInfo->no_shop,
                    'no_order' => $orderInfo->no_order,
                    'cd_pickup_status' => '602300',
                    'cd_alarm_event_type' => '607070',
                    'ready_date' => now()
                ]);

                $nmShop = sprintf('%s %s', $shop->partner->nm_partner, $shop->nm_shop);
                $fcmData = array("ordering" => 'Y', "nm_shop" => $nmShop);
                ProcessFcm::dispatch(
                    new Fcm("RETAIL", $orderInfo->no_shop, $orderInfo->no_order, $fcmData, true, 'user', $orderInfo->no_user, "shop_complete")
                )->onConnection('database');
            } else {
                RetailAdminCheckLogService::insertRetailAdminChkLog([
                    'no_order' => $orderInfo->no_order,
                    'no_user' => $orderInfo->no_user,
                    'no_shop' => $orderInfo->no_shop,
                    'log_type' => '901',
                    'content' => '픽업완료 이후 CU매장에서 준비완료 처리'
                ]);
            }

            $response = [
                'result' => true,
                'result_code' => '0000',
                'partner_code' => $request->partner_code,
                'shop_code' => $request->shop_code,
                'result_msg' => Code::message('0000')
            ];
            RetailService::insertRetailExternalResultLog($request->all(), $response);

            return base64_encode(json_encode($response));
        } catch (Throwable $t) {
            return base64_encode(json_encode([
                'result' => false,
                'partner_code' => $request->partner_code,
                'shop_code' => $request->shop_code,
                'result_code' => '9990',
                'result_msg' => $t->getMessage()
            ]));
        }
    }


    /**
     * [9.매장도착알림]    ( Owin -> retail(CU) )
     * @param string $noOrder
     * @return JsonResponse
     * @throws GuzzleException
     */
    public function arrivalAlarm(string $noOrder): JsonResponse
    {
        $parameter = [
            'no_order' => $noOrder,
        ];

        $orderInfo = RetailService::getOrderInfo($noOrder, $parameter);
        $storeCd = RetailService::getRetailStoreCd($orderInfo->no_partner, $orderInfo->no_shop, $parameter);
        $body = [
            'partner_code' => Code::conf('cu.partner_code'),
            'shop_code' => $storeCd,
            'no_order' => Cu::changeNoOrder('OWIN', $noOrder),
            'nm_order' => $orderInfo->nm_order,
            'yn_complete' => 'Y',
            'at_distance' => 0,
            'dt_order' => $orderInfo->dt_reg->format('YmdHis'),
            'dt_pickup' => $orderInfo->dt_pickup->format('YmdHis'),
            'ds_pickup_type' => $orderInfo->ds_pickup_type,
            'nm_nick' => $orderInfo->member->nm_nick,
            'ds_phone' => $orderInfo->ds_safe_number,
            'ds_car_number' => $orderInfo->ds_car_number,
            'trans_dt' => now()->format('YmdHis'),
        ];
        $body['sign'] = Cu::generateSign(
            [$body['partner_code'], $body['shop_code'], $body['no_order'], $body['trans_dt']]
        );

        $callUrl = Code::conf('cu.api_uri') . '/retail/arrival_alarm';
        $apiResponse = Cu::send($callUrl, $body);

        if ($apiResponse['result_code'] == '0000') {
            RetailService::updateOrderInfo([
                'no_user' => $orderInfo->no_user,
                'no_shop' => $orderInfo->no_shop,
                'no_order' => $orderInfo->no_order,
                'cd_alarm_event_type' => '607350',
            ]);
        }

        return response()->json([
            'result' => match ($apiResponse['result_code']) {
                '0000' => true,
                default => false
            },
        ]);
    }

    /**
     * [10.도착알림 처리 (확인)]  (  retail(CU) -> Owin )
     * @param Request $request
     * @return JsonResponse
     *
     */
    public function arrivalConfirm(Request $request): string
    {
        try {
            $cuRequest = makeCuRequest();
            $request->merge([
                'partner_code' => $cuRequest->partner_code,
                'shop_code' => $cuRequest->shop_code,
                'no_order' => $cuRequest->no_order,
                'yn_confrim' => $cuRequest->yn_confrim,
                'trans_dt' => $cuRequest->trans_dt,
                'sign' => $cuRequest->sign,
            ])->validate([
                'partner_code' => 'required|string',
                'shop_code' => 'required|string',
                'no_order' => 'required|string',
                'yn_confrim' => 'required|in:Y',
                'trans_dt' => 'required|string',
                'sign' => 'required|string',
            ]);

            $changeSign = Cu::generateSign([$request->partner_code, $request->shop_code, $request->no_order, $request->trans_dt]);
            Cu::hashAccuracyCheck($request->sign, $changeSign, $request->all());

            $orderInfo = RetailService::getOrderInfo(Cu::changeNoOrder('CU', $request->no_order), $request->all());

            RetailService::updateOrderInfo([
                'no_user' => $orderInfo->no_user,
                'no_shop' => $orderInfo->no_shop,
                'no_order' => $orderInfo->no_order,
                'cd_alarm_event_type' => '607360',
            ]);

            $response = [
                'result' => true,
                'result_code' => '0000',
                'partner_code' => $request->partner_code,
                'shop_code' => $request->shop_code,
                'result_msg' => Code::message('0000')
            ];

            RetailService::insertRetailExternalResultLog($request->all(), $response);

            return base64_encode(json_encode($response));
        } catch (Throwable $t) {
            return base64_encode(json_encode([
                'result' => true,
                'result_code' => '9990',
                'partner_code' => $request->partner_code,
                'shop_code' => $request->shop_code,
                'result_msg' => Code::message('9990')
            ]));
        }
    }

    /**
     * [11.전달완료 요청알림]   ( Owin -> retail(CU) )
     * @param Request $request
     * @return JsonResponse
     * @throws GuzzleException
     */
    public function deliveryAlarm(Request $request): JsonResponse
    {
        $request->validate([
            'no_partner' => 'required|integer',
            'no_shop' => 'required|integer',
            'no_order' => 'required|string'
        ]);

        $storeCd = RetailService::getRetailStoreCd($request->no_partner, $request->no_shop, $request->all());
        $orderInfo = RetailService::getOrderInfo($request->no_order, $request->all());
        $body = [
            'partner_code' => Code::conf('cu.partner_code'),
            'shop_code' => $storeCd,
            'no_order' => Cu::changeNoOrder('OWIN', $orderInfo->no_order),
            'dt_pickup' => $orderInfo->dt_pickup->format('YmdHis'),
            'trans_dt' => now()->format('YmdHis'),
        ];
        $body['sign'] = Cu::generateSign(
            [$body['partner_code'], $body['shop_code'], $body['no_order'], $body['trans_dt']]
        );
        $callUrl = Code::conf('cu.api_uri') . '/retail/delivery_alarm';
        $apiResponse = Cu::send($callUrl, $body);

        if ($apiResponse['result_code'] == '0000') {
            RetailService::updateOrderInfo([
                'no_user' => $orderInfo->no_user,
                'no_shop' => $orderInfo->no_shop,
                'no_order' => $orderInfo->no_order,
                'cd_alarm_event_type' => '607420',
            ]);
        }

        return response()->json([
            'result' => match ($apiResponse['result_code']) {
                '0000' => true,
                default => false
            }
        ]);
    }

    /**
     * [12.전달완료 요청알림 처리 (완료/닫기) ]  (  retail(CU) -> Owin )
     * @param Request $request
     * @return JsonResponse
     */
    public function deliveryConfirm(Request $request): string
    {
        try {
            $cuRequest = makeCuRequest();
            $request->merge([
                'partner_code' => $cuRequest->partner_code,
                'shop_code' => $cuRequest->shop_code,
                'no_order' => $cuRequest->no_order,
                'yn_delivery' => $cuRequest->yn_delivery,
                'trans_dt' => $cuRequest->trans_dt,
                'sign' => $cuRequest->sign,
            ])->validate([
                'partner_code' => 'required|string',
                'shop_code' => 'required|string',
                'no_order' => 'required|string',
                'yn_delivery' => 'required|in:Y,N',
                'trans_dt' => 'required|string',
                'sign' => 'required|string',
            ]);

            $changeSign = Cu::generateSign([$request->partner_code, $request->shop_code, $request->no_order, $request->trans_dt]);
            Cu::hashAccuracyCheck($request->sign, $changeSign, $request->all());

            $noPartner = $request->partner_code === Code::conf('cu.partner_code') ? Code::conf('cu.partner_no') : '';
            $shop = RetailService::getRetailShop($noPartner, $request->shop_code, $request->all());
            $orderInfo = RetailService::getOrderInfo(Cu::changeNoOrder('CU', $request->no_order), $request->all());

            //// [ERROR] 기취소된 주문건
            if ($orderInfo->cd_order_status >= '601900') {
                RetailService::insertRetailExternalResultLog($request->all(), [
                    'result' => false,
                    'result_code' => '9970'
                ]);
                throw new OwinException(Code::message('9970'));
            }

            // 2020-12-18 픽업완료 이후 준비완료 등의 액션이 또 들어오는 경우가 있어 임시로 막음
            // 2020-12-25 N으로 들어오는 경우가 있어 Y만 업데이트 되게
            $completeCount = OrderRetailService::getOrderCompleteLogCnt($orderInfo->no_order);
            $cdAlarmEventType = '607400';
            if ($request->yn_delivery === 'Y') {
                OrderRetailService::registMemberShopRetailLog([
                    'no_user' => $orderInfo->no_user,
                    'no_shop' => $orderInfo->no_shop,
                    'no_order' => $orderInfo->no_order,
                    'cd_alarm_event_type' => $cdAlarmEventType
                ]);
                if ($orderInfo['cd_pickup_status'] !== '602400') {
                    $cdPickupStatus = '602400';
                    RetailService::updateOrderStatus($orderInfo->no_order, [
                        'cd_pickup_status' => $cdPickupStatus,
                        'cd_alarm_event_type' => $cdAlarmEventType,
                    ]);

                    BizCall::setVn([
                        'virtualNumber' => $orderInfo->ds_safe_number,
                        'realNumber' => ' '
                    ]);
                    (new VirtualNumberService())->updateVnsLog([
                        'dt_use_end' => now()
                    ], [
                        'virtual_number' => $orderInfo->ds_safe_number,
                        'no_order' => $orderInfo->no_order
                    ]);

                    $nmShop = sprintf('%s %s', $shop->partner->nm_partner, $shop->nm_shop);
                    $fcmData = array("ordering" => 'N', "nm_shop" => $nmShop);
                    ProcessFcm::dispatch(
                        new Fcm("RETAIL", $orderInfo->no_shop, $orderInfo->no_order, $fcmData, true, 'user', $orderInfo->no_user, "delivery_complete")
                    )->onConnection('database');
                }
            }

            if ($orderInfo->cd_pickup_status === '602400' && $completeCount) {
                RetailAdminCheckLogService::insertRetailAdminChkLog([
                    'no_order' => $orderInfo->no_order,
                    'no_user' => $orderInfo->no_user,
                    'no_shop' => $orderInfo->no_shop,
                    'log_type' => '902',
                    'content' => '픽업완료 이후 CU매장에서 픽업완료 처리'
                ]);
            }

            $response = [
                'result' => true,
                'result_code' => '0000',
                'partner_code' => $request->partner_code,
                'shop_code' => $request->shop_code,
                'result_msg' => Code::message('0000')
            ];

            RetailService::insertRetailExternalResultLog($request->all(), $response);

            return base64_encode(json_encode($response));
        } catch (Throwable $t) {
            return base64_encode(json_encode([
                'result' => false,
                'result_code' => '9990',
                'partner_code' => $request->partner_code,
                'shop_code' => $request->shop_code,
                'result_msg' => Code::message('9990')
            ]));
        }
    }

    /**
     * 리테일 매장 기본정보
     * @param Request $request
     * @return JsonResponse
     * @throws OwinException
     */
    public function info(Request $request): JsonResponse
    {
        $request->validate([
            'no_shop' => 'required|integer',
        ]);
        $noUser = Auth::id(); // 테스트 계정 처리는 어떻게 해야할까용?
        $noShop = $request->get('no_shop');

        //shop 조회
        $response['shop_info'] = ShopService::getShop($noShop);

        //shop 조회수 증가
        ShopService::updateCtView($noShop);

        $response['shop_holiday'] = ShopService::getShopHoliday($noShop);
        $response['yn_open'] = $response['shop_holiday']['yn_open'];

        $posError = SearchService::getPosError($noShop);
        if ($posError) {
            $response['yn_open'] = 'N';
        }

        if ($response['shop_info']['ds_status'] === 'N') {
            $response['yn_open'] = 'N';
        }

        if ($response['shop_info']['cd_pause_type']) {
            $response['yn_open'] = 'E';
        }

        $response['ds_btn_notice'] = match ($response['yn_open']) {
            "Y" => "주유하기",
            "N" => "운영종료",
            "T" => "임시휴일",
            default => "점검중",
        };

        return response()->json($response);
    }


    /**
     * 오윈주문 - 브랜드 카테고리 리스트
     * 브랜드에 등록된 메인카테고리 정보만 전달 - 서브카테고리 제외
     * @param Request $request
     * @return JsonResponse
     * @throws OwinException
     */
    public function category(Request $request): JsonResponse
    {
        $request->validate([
            'no_shop' => 'nullable|string',
            'no_partner' => 'nullable|string',
            'offset' => 'nullable|integer|min:0',
        ]);
        $response = [];

        $noShop = $request->get('no_shop');
        $noPartner = $request->get('no_partner');

        if (!$noPartner && !$noShop) {
            throw new OwinException(Code::message('409'));
        }

        $shopInfo = $noShop ? ShopService::getShop($noShop) : null;
        if (!$noPartner && $noShop) {
            $noPartner = $shopInfo && $shopInfo['no_partner'] ? $shopInfo['no_partner'] : $noPartner;
        }

        $partnerInfo = PartnerService::get($noPartner);
        if (!$shopInfo && !$partnerInfo) {
            throw new OwinException(Code::message('M1303'));
        }

        $categories = RetailProductService::getRetailCategory($noPartner)->get()->toArray();
        $response['categories'] = [];
        if (count($categories)) {
            foreach ($categories as $category) {
                if ($category['retail_sub_categories']) {
                    foreach ($category['retail_sub_categories'] as $subCategory) {
                        $subCategory['nm_category'] = $category['nm_category'] . ' ' . $subCategory['nm_sub_category'];
                        $response['categories'][] = $subCategory;
                    }
                } else {
                    unset($category['retail_sub_categories']);
                    $response['categories'][] = $category;
                }
            }
        }


        $response['package_product'] = RetailProductService::getRetailProduct(
            $noPartner,
            null,
            null,
            null,
            null,
            null,
            true
        );

        return response()->json($response);
    }

    /**
     * 매장정보 - 매장상세정보
     * @param Request $request
     * @return JsonResponse
     * @throws OwinException
     */
    public function infoDetail(Request $request): JsonResponse
    {
        $request->validate([
            'no_shop' => 'required|integer',
        ]);
        $response = [];

        $noShop = $request->get('no_shop');
        $shopInfo = ShopService::getShop($noShop);
        if (!$shopInfo) {
            throw new OwinException(Code::message('M1303'));
        }

        //매장 영업시간
        $response['shop_opt_time'] = ShopService::getInfoOptTimeAll($noShop);

        //매장 휴무
        $response['shop_holiday'] = ShopService::getShopHoliday($noShop);

        $response['yn_open'] = $response['shop_holiday']['yn_open'];

        $response['review_total'] = ReviewService::getReviewTotal($noShop);


        return response()->json($response);
    }

    /**
     * todo info()와 동일 정보임 삭제 예정
     * 리테일 매장 픽업존 정보
     * @param Request $request
     * @return JsonResponse
     * @throws OwinException
     */
    public function pickupInfo(Request $request): JsonResponse
    {
        $request->validate([
            'no_shop' => 'required|integer',
        ]);
        $noShop = $request->get('no_shop');

        $shopInfo = ShopService::getShop($noShop);
        if (!$shopInfo) {
            throw new OwinException(Code::message('M1303'));
        }
        return response()->json($shopInfo);
    }

    /**
     * 리뷰 - 리뷰리스트
     * @param Request $request
     * @return JsonResponse
     */
    public function review(Request $request): JsonResponse
    {
        $request->validate([
            'no_shop' => 'required|integer',
            'no_user' => 'nullable|integer',
            'offset' => 'nullable|integer'
        ]);
        $noShop = $request->get('no_shop');
        $noUser = $request->get('no_user');

        $size = $request->get('size') ?: Code::conf('default_size');
        $offset = $request->get('offset') ?: 0;

        $response = [];
        if ($noUser) {
            $response['yn_week_order'] = OrderService::checkReviewWriteAuth($noUser, $noShop) ? 'Y' : 'N';
        }

        $response['review_info'] = ReviewService::getReviewTotal($noShop);

        $response['reviews'] = ReviewService::getReviews($noShop, $offset, $size);

        return response()->json($response);
    }

    /**
     * 상품 리스트 - 카테고리별 상품 리스트 조회
     * @param Request $request
     * @return JsonResponse
     * @throws OwinException
     * @throws GuzzleException
     */
    public function productList(Request $request): JsonResponse
    {
        $request->validate([
            'no_shop' => 'required|integer',
            'no_category' => 'required|integer',
            'no_sub_category' => 'nullable|integer',
        ]);
        $noShop = (int)$request->get('no_shop');
        $noCategory = $request->get('no_category');
        $noSubCategory = $request->get('no_sub_category');

        $size = $request->get('size');
        $ctPage = $request->get('ct_page');

        $response = [];

        $shopInfo = $noShop ? ShopService::getShop($noShop) : null;
        $noPartner = $shopInfo['no_partner'];

        $partnerInfo = PartnerService::get($noPartner);
        if (!$shopInfo && !$partnerInfo) {
            throw new OwinException(Code::message('M1303'));
        }

        ## 상품리스트
        // 카테고리 상품 - 서브카테고리있을 경우 서브카테고리 1번 상품리스트
        // 고유번호 없을 경우 ($ctPage IS NULL) 전체상품
        // 고유번호 있을 경우 - 고유번호기준 limit ( 0인경우 처음부터 limit)
        $response['products'] = RetailProductService::getRetailProduct(
            $noPartner,
            $noShop,
            $noCategory,
            $noSubCategory,
            $size,
            $ctPage
        );
        if (count($response['products'])) {
//            $productIds = array_merge($productIds, $response['products']->pluck('no_barcode')->all());
            $productIds = RetailProductService::getRetailProductIds($response['products']);

            ## ===============================================================================
            ## [4] 실시간 상품 재고조회 추가  [ CU 상품 재고조회 ]
            //$response['list_product_stock']	    = $list_product_stock; // 재고조회 상품 리스트 ( 일반상품 + 옵션상품) TEST
            ## ===============================================================================
            if ($noPartner === Code::conf('cu.partner_no')) {
                $cacheKey = sprintf('retail_%s_%s', $noShop, $noCategory);
                if (Cache::has($cacheKey)) {
                    $realProductStock = Cache::get($cacheKey);
                } else {
                    $partnerCode = Code::conf('cu.partner_code');
                    $transDt = date('YmdHis');
                    $body = [
                        'partner_code' => $partnerCode,
                        'shop_code' => $shopInfo->store_cd,
                        'product_list' => $productIds,
                        'trans_dt' => $transDt,
                        'sign' => Cu::generateSign([$partnerCode, $shopInfo->store_cd, $transDt])
                    ];

                    $callUrl = Code::conf('cu.api_uri') . "/retail/product_check";
                    $apiResponse = Cu::send($callUrl, $body);
                    $realProductStock = isset($apiResponse['product_list']) && count(
                        (array)$apiResponse['product_list']
                    ) ? (array)$apiResponse['product_list'] : null;
                    Cache::store('file')->put($cacheKey, $realProductStock, 60);
                }
                // 옵션그룹 : 타입 리스트 - Array
                $setProductOptTypeArr = [];
                foreach ($response['products'] as $product) {
                    $stock = RetailService::getOptionMinStock($product->productOptionGroups, $realProductStock);

                    ## [1] 상품금액정보
                    $product['cnt_product'] = match ($product->productOptionGroups?->where(
                        'cd_option_type',
                        OptionType::REQUIRED->value
                    )->count()) {
                        0 => data_get($realProductStock, $product->no_barcode),
                        default => $stock['require']
                    };
//                    $product['min_cnt'] = $minCount;

                    ## [2] 부분 품절
                    // 옵션상품중 품절수량이 있거나 전체상품보다 품절상품  수가 적을 경우 : 부분품절
                    $product['yn_soldout'] = match ($product->productOptionGroups?->where('cd_option_type', OptionType::REQUIRED->value)->count()) {
                        0 => data_get($realProductStock, $product->no_barcode) > 0 ? 'N' : 'Y',
                        default => $stock['require'] > 0 ? 'N' : 'Y'
                    };
                    $product['yn_part_soldout'] = match ($product->productOptionGroups?->where('cd_option_type', OptionType::SELECT->value)->count()) {
                        0 => 'N',
                        default => $stock['select'] > 0 ? 'N' : 'Y'
                    };

                    ## 상품 이미지
                    // 상품 이미지파일이 없을 경우 기본이미지전달
                    if ($product['ds_image_path'] && file_exists($product['ds_image_path'])) {
                        $product['ds_image_path'] = Common::getImagePath($product['ds_image_path']);
                    } else {
                        $product['ds_image'] = null;
                    }
                }
            }
        }

        return response()->json([
            'result' => true,
            'products' => $response['products']->map(function ($product) {
                return [
                    'no_product' => $product['no_product'],
                    'no_category' => $product['no_category'],
                    'no_sub_category' => $product['no_sub_category'],
                    'nm_product' => $product['nm_product'],
                    'at_price_before' => $product['at_price_before'],
                    'at_price' => $product['at_price'],
                    'ds_image_path' => $product['ds_image_path'],
                    'cnt_product' => $product['cnt_product'],
                    'yn_soldout' => $product['yn_soldout'],
                    'at_ratio' => Common::getSaleRatio($product['at_price_before'], $product['at_price']),
                    'yn_part_soldout' => $product['yn_part_soldout']
                ];
            })->sortBy('yn_soldout')->values()
        ]);
    }

    /**
     * 상품명 검색
     * @param Request $request
     * @return JsonResponse
     */
    public function searchProduct(Request $request): JsonResponse
    {
        $request->validate([
            'no_shop' => 'required|integer',
            'search_word' => 'required|string'
        ]);
        $noShop = $request->get('no_shop');
        $noUser = $request->get('no_user');
        $searchWord = $request->get('search_word');

        $shopInfo = ShopService::getShop($noShop);
        if (!$shopInfo) {
            throw new OwinException(Code::message('M1303'));
        }

        ProductService::createSearchLog($noShop, $searchWord, $noUser);
        $searchWord = substr(strip_tags(trim($searchWord)), 0, 200);

        if (!$searchWord) {
            throw new OwinException(Code::message('P2044'));
        }

        $rows = RetailProductService::getSearchProduct($shopInfo['no_partner'], $searchWord);
        if (count($rows)) {
            return response()->json([
                'count' => count($rows),
                'rows' => $rows,
            ]);
        } else {
            throw new OwinException(Code::message('404'));
        }
    }

    /**
     * 상품 상세정보
     * @param Request $request
     * @return JsonResponse
     */
    public function productInfo(Request $request): JsonResponse
    {
        $request->validate([
            'no_shop' => 'required|integer',
            'no_product' => 'required|integer'
        ]);
        $noShop = (int)$request->no_shop;
        $noProduct = (int)$request->no_product;

        $shopInfo = ShopService::getShop($noShop);
        if (!$shopInfo) {
            throw new OwinException(Code::message('M1303'));
        }

        $retailProduct = RetailProductService::getRetailProductInfo($shopInfo['no_partner'], $noShop, $noProduct);
        if (!$retailProduct) {
            throw new OwinException(Code::message('P2045'));
        }

        $productIds = RetailProductService::getRetailProductIds(collect([$retailProduct]));

        $realProductStock = [];
        if ($shopInfo['no_partner'] === Code::conf('cu.partner_no')) {
            $partnerCode = Code::conf('cu.partner_code');
            $transDt = date('YmdHis');
            $body = [
                'partner_code' => $partnerCode,
                'shop_code' => $shopInfo->store_cd,
                'product_list' => $productIds,
                'trans_dt' => $transDt,
                'sign' => Cu::generateSign([$partnerCode, $shopInfo->store_cd, $transDt])
            ];

            $callUrl = Code::conf('cu.api_uri') . "/retail/product_check";
            $apiResponse = Cu::send($callUrl, $body);
            $realProductStock = (array)data_get($apiResponse, 'product_list');
        }

        return response()->json([
            'result' => true,
            ...(array)(new ProductInfo($retailProduct, $realProductStock))->setProductInfo()
        ]);
    }

    public function shopStatusChange(Request $request): string
    {
        try {
            $cuRequest = makeCuRequest();
            $request->merge([
                'partner_code' => $cuRequest->partner_code,
                'shop_code' => $cuRequest->shop_code,
                'yn_status_open' => $cuRequest->yn_status_open,
                'trans_dt' => $cuRequest->trans_dt,
                'sign' => $cuRequest->sign,
            ])->validate([
                'partner_code' => 'required|string',
                'shop_code' => 'required|string',
                'yn_status_open' => 'required|in:Y,N',
                'trans_dt' => 'required|string',
                'sign' => 'required|string',
            ]);

            $changeSign = Cu::generateSign([$request->partner_code, $request->shop_code, $request->yn_status_open, $request->trans_dt]);
            Cu::hashAccuracyCheck($request->sign, $changeSign, $request->all());

            $noPartner = $request->partner_code === Code::conf('cu.partner_code') ? Code::conf('cu.partner_no') : '';
            $shop = RetailService::getRetailShop($noPartner, $request->shop_code, $request->all());

            ShopService::updateShop($shop, [
                'ds_status' => $request->yn_status_open,
                'external_dt_status' => now()
            ]);

            return base64_encode(json_encode([
                'result' => false,
                'partner_code' => $request->partner_code,
                'shop_code' => $request->shop_code,
                'result_code' => '0000',
                'result_msg' => Code::message('0000')
            ]));
        } catch (Throwable $t) {
            return base64_encode(json_encode([
                'result' => false,
                'partner_code' => $request->partner_code,
                'shop_code' => $request->shop_code,
                'result_code' => '9990',
                'result_msg' => $t->getMessage()
            ]));
        }
    }
}
