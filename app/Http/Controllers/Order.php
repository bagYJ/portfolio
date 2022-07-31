<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\EnumYN;
use App\Enums\Pickup;
use App\Enums\SearchBizKind;
use App\Enums\ServiceCode;
use App\Enums\ServicePayCode;
use App\Exceptions\OwinException;
use App\Jobs\ProcessArkServer;
use App\Jobs\ProcessFcm;
use App\Queues\Fcm\Fcm;
use App\Queues\Socket\ArkServer;
use App\Services\CodeService;
use App\Services\CouponService;
use App\Services\DirectOrderService;
use App\Services\OrderService;
use App\Services\ReviewService;
use App\Services\ShopService;
use App\Utils\Code;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class Order extends Controller
{
    public function detail(string $bizKind, string $noOrder): JsonResponse
    {
        $orderInfo = match ($bizKind) {
            SearchBizKind::PARKING->name => OrderService::getParkingOrderInfo([
                'no_user' => Auth::id(),
                'no_order' => $noOrder
            ])->first(),
            default => OrderService::getOrderInfo([
                'no_user' => Auth::id(),
                'no_order' => $noOrder
            ])->first()
        };

        return response()->json([
            'result' => true,
            'nm_shop' => $orderInfo->nm_shop ?? $orderInfo->parkingSite?->nm_shop ?? $orderInfo->autoParking?->nm_shop,
            'no_order' => $orderInfo->no_order,
            'no_order_user' => substr($orderInfo->no_order, -7),
            'biz_kind' => $bizKind,
            'nm_order' => $orderInfo->nm_order,
            'dt_reg' => $orderInfo->dt_reg->format('Y-m-d H:i:s'),
            'at_commission_rate' => $orderInfo->at_commission_rate,
            'at_send_price' => $orderInfo?->at_send_price,
            'at_disct' => $orderInfo->at_disct,
            'at_cpn_disct' => $orderInfo->at_cpn_disct,
            'cd_gas_kind' => $orderInfo?->cd_gas_kind,
            'at_gas_price' => $orderInfo?->at_gas_price,
            'at_price' => $orderInfo->at_price,
            'at_price_pg' => $orderInfo->at_price_pg,
            'cd_status' => $orderInfo->cd_status,
            'nm_status' => $orderInfo->nm_status,
            'list_product' => $orderInfo->list_product,
            'is_direct_order' => in_array($orderInfo->cd_status, ['800400', '800410']) && DirectOrderService::hasDirectOrder([
                'no_user' => Auth::id(),
                'no_order' => $noOrder
            ]),
            'no_shop' => $orderInfo?->no_shop,
            'no_site' => $orderInfo?->no_site,
            'cd_card_corp' => $orderInfo->card?->cd_card_corp,
            'card_corp' => CodeService::getCode($orderInfo->card?->cd_card_corp)?->nm_code,
            'no_card_user' => $orderInfo->card?->no_card_user,
            'ds_car_number' => $orderInfo->ds_car_number,
            'dt_entry_time' => $orderInfo->dt_entry_time?->format('Y-m-d H:i:s'),
            'dt_exit_time' => $orderInfo->dt_exit_time?->format('Y-m-d H:i:s'),
            'ds_res_order_no' => $orderInfo->ds_res_order_no ?? $orderInfo->orderPayment?->ds_res_order_no,
            'dt_res' => $orderInfo->dt_res?->format('Y-m-d H:i:s') ?? $orderInfo->orderPayment?->dt_res?->format('Y-m-d H:i:s'),
            'pg_bill_result' => CodeService::getCode($orderInfo->cd_pg_bill_result ?? $orderInfo->orderPayment?->cd_pg_bill_result)?->nm_code,
            'ds_res_msg' => $orderInfo->ds_res_msg ?? $orderInfo->orderPaymnet?->ds_res_msg,
            'is_review' => $bizKind == SearchBizKind::FNB->name && $orderInfo->cd_status == '800410' && ReviewService::getReview([
                'no_user' => Auth::id(),
                'no_order' => $orderInfo->no_order
            ])->count() <= 0
        ]);
    }

    public function gpsAlarm(Request $request): JsonResponse
    {
        $request->validate([
            'no_shop' => 'required|integer',
            'no_order' => 'required|string',
            'cd_alarm_event_type' => 'required',
            'at_lat' => 'nullable',
            'at_lng' => 'nullable',
            'at_distance' => 'nullable',
            'at_yn_gps_statuslng' => 'nullable',
        ]);

        $orderService = new OrderService();

        $orderlist = $orderService->getOrderInfo([
            'no_user' => Auth::id(),
            'no_order' => $request->input('no_order')
        ])->whenEmpty(function () {
            throw new OwinException(Code::message('P2120'));
        })->first();

        $parameter = [
            'at_distance' => $request->input('at_distance'),
            'cd_alarm_event_type' => $request->input('cd_alarm_event_type')
        ];
        if (in_array($request->input('cd_alarm_event_type'), ['607300', '607350'])) {
            $parameter['cd_call_shop'] = match ($request->input('cd_alarm_event_type')) {
                '607300' => '611200',
                '607350' => '611300',
                default => null
            };
        }
        $whereNot = match (in_array($request->input('cd_alarm_event_type'), ['607100', '607200'])) {
            true => ['cd_alarm_event_type' => ['!=', '607300']],
            default => []
        };

        $orderService->updateOrderList($parameter, [
            'no_order' => $request->input('no_order')
        ], $whereNot);

        if (in_array($request->input('cd_alarm_event_type'), ['607100', '607200'])) {
            ProcessArkServer::dispatch(
                new ArkServer(
                    'ORDER',
                    'orderStatus',
                    $request->input('no_shop') . '0',
                    '14'
                )
            )->onConnection('database');
        }

        if ($request->input('cd_alarm_event_type') == '607350') {
            ProcessArkServer::dispatch(
                new ArkServer(
                    'ORDER',
                    'orderStatus',
                    $request->input('no_shop') . $request->input('no_order'),
                    '20'
                )
            )->onConnection('database');

            ProcessFcm::dispatch(
                new Fcm(
                    'arrived',
                    (int)$request->input('no_shop'),
                    $request->input('no_order'),
                    [
                        'no_order' => $request->input('no_order'),
                        'no_order_user' => makeNoOrderUser(
                            $request->input('no_order')
                        ),
                        'ds_maker' => Auth::user()->memberCarInfo?->carList?->ds_maker,
                        'ds_kind' => Auth::user()->memberCarInfo?->carList?->ds_kind,
                        'ds_car_number' => $orderlist->ds_car_number,
                        'isCurrent' => true,
                        'channel_id' => 'arrived',
                    ]
                )
            )->onConnection('database');
        }

        return response()->json([
            'result' => true
        ]);
    }

    public function historyCnt(): JsonResponse
    {
        $orderService = new OrderService();

        $orderCount = $orderService->getOrderCount([
            'order_list.cd_service' => '900100',
            'order_list.no_user' => Auth::id()
        ], [
            'order_list.cd_pickup_status' => ['<', '602400'],
            'order_list.cd_third_party' => ['!=', '110200']
        ]);

        return response()->json([
            'result' => true,
            'order_cnt' => $orderCount
        ]);
    }

    public function init(Request $request): JsonResponse
    {
        $request->validate([
            'cd_service' => ['required', Rule::in(ServiceCode::keys())],
            'no_shop' => 'required|numeric:8',
            'at_price_total' => 'required|numeric',
            'list_product' => 'required|array'
        ]);
        if (empty(Auth::user()->memberCarInfo->seq)) {
            throw new OwinException(Code::message('PA141'));
        }

        $shop = ShopService::getShop($request->no_shop);
        if ($shop->ds_status != EnumYN::Y->name) {
            throw new OwinException(Code::message('M1304'));
        }

        $couponService = new CouponService();
        $shopService = new ShopService();

        return response()->json([
            'result' => true,
            'at_make_ready_time' => $shop->at_make_ready_time,
            'at_commission_rate' => match ($shop->cd_commission_type == '205300') {
                true => $shop->at_commission_rate,
                default => 0
            },
            'at_send_price' => $shop->at_send_price,
            'is_car_pickup' => match (SearchBizKind::getBizKind($shop->partner->cd_biz_kind)) {
                SearchBizKind::FNB => $shop->shopDetail->yn_car_pickup == 'Y',
                default => true
            },
            'is_shop_pickup' => $shop->shopDetail->yn_shop_pickup == 'Y', // 추후 기능 오픈
            'is_booking_pickup' => $shop->shopDetail->yn_booking_pickup == 'Y', // 추후 기능 오픈
            'ds_car_number' => Auth::user()->memberCarInfo->ds_car_number,
            'shop_opt_info' => $shopService->getOperate($shop->no_shop),
            'shop_holiday_info' => $shopService->getHoliday($shop->no_shop),
            'coupon_info' => match (SearchBizKind::getBizKind($shop->partner->cd_biz_kind)) {
                SearchBizKind::FNB => $couponService->getFnbUsableCoupon(
                    Auth::id(),
                    $shop->no_shop,
                    $request->at_price_total,
                    collect($request->list_product)
                ),
                SearchBizKind::RETAIL => $couponService->getRetailUsableCoupon(
                    Auth::id(),
                    $shop->no_partner,
                    $request->at_price_total
                ),
                default => null
            }
        ]);
    }

    public function payment(Request $request): JsonResponse
    {
        $request->validate([
            'cd_service' => ['required', Rule::in(ServiceCode::keys())],
            'cd_service_pay' => ['required', Rule::in(ServicePayCode::keys())],
            'pickup_type' => ['required', Rule::in(Pickup::keys())],
            'no_shop' => 'required|numeric:8',
            'at_price_total' => 'required|numeric',
            'at_price_calc' => 'required|numeric',
            'at_cpn_disct' => 'required|numeric',
            'at_commission_rate' => 'numeric',
            'no_card' => 'required|numeric',
            'car_number' => 'required',
            'list_product' => 'required|array',
            'arrived_time' => 'required|date_format:"Y-m-d H:i:s',
            'discount_info' => 'nullable|array'
        ]);
        Auth::user()->memberCarInfo->where('ds_car_number', $request->car_number)->get()->whenEmpty(function () {
            throw new OwinException(Code::message('M1510'));
        });

        $shop = ShopService::getShop($request->no_shop);
        if ($shop->ds_status != EnumYN::Y->name) {
            throw new OwinException(Code::message('M1304'));
        }

        $orderService = new OrderService();
        $response = $orderService->payment(Auth::user(), $shop, collect($request->post()));
        if ($response['result'] === true) {
            ProcessArkServer::dispatch(
                new ArkServer(
                    'ORDER',
                    'orderStatus',
                    $request->no_shop . '00000000' . $response['no_order'],
                    '11'
                )
            )
                ->onConnection('database');

            ProcessFcm::dispatch(
                new Fcm('neworder', $request->no_shop, $response['no_order'], [
                    'no_order' => $response['no_order'],
                    'no_order_user' => makeNoOrderUser($response['no_order']),
                    'nm_order' => $response['nm_order'],
                    'isCurrent' => true,
                    'channel_id' => 'neworder',
                ])
            )->onConnection('database');
        }

        return response()->json([
            'result' => $response['result'],
            'no_order' => $response['no_order'],
            'message' => $response['msg'],
            'detail_message' => $response['pg_msg']
        ]);
    }

    public function orderStatusHistory(string $noOrder): JsonResponse
    {
        $orderStatusHistory = OrderService::getOrderStatusHistory([
            'no_user' => Auth::id(),
            'no_order' => $noOrder
        ])->first();

        $orderStatus = getOrderStatus(
            $orderStatusHistory->partner->cd_biz_kind,
            $orderStatusHistory['cd_order_status'],
            $orderStatusHistory['cd_pickup_status'],
            $orderStatusHistory['cd_payment_status']
        );

        return response()->json([
            'result' => true,
            'order_status' => Arr::last($orderStatus),
            'dt_pickup' => $orderStatusHistory->dt_pickup->format('Y-m-d H:i:s'),
            'order_reserve' => $orderStatusHistory['dt_payment_status'],
            'order_confirm' => $orderStatusHistory['confirm_date'],
            'order_ready' => $orderStatusHistory['ready_date'],
            'order_pickup' => $orderStatusHistory['pickup_date'],
            'employee_call' => match (true) {
                $orderStatusHistory->cd_alarm_event_type >= '607350' => true,
                default => false
            },
            'no_oil_in_shop' => $orderStatusHistory->shop?->oilInShop?->no_shop_in
        ]);
    }

    public function getOrderList(Request $request): JsonResponse
    {
        return (new Member($request))->getOrderList($request);
    }

    public function getOrderListByBizKind(Request $request, string $bizKind): JsonResponse
    {
        Validator::make([
            'bizKind' => $bizKind
        ], [
            'bizKind' => Rule::in(SearchBizKind::keys())
        ])->validate();

        $size = (int)$request->get('size') ?: Code::conf('default_size');
        $offset = (int)$request->get('offset') ?: 0;

        $items = match ($bizKind) {
            SearchBizKind::PARKING->name => OrderService::getParkingOrderList(Auth::id(), $size, $offset),
            default => OrderService::getOrderList(Auth::id(), $bizKind, $size, $offset)
        };

        return response()->json([
            'result' => true,
            'total_cnt' => $items->total(),
            'per_page' => $items->perPage(),
            'current_page' => $items->currentPage(),
            'last_page' => $items->lastPage(),
            'order_list' => collect($items->items())->map(function ($list) {
                $orderStatus = getOrderStatus(
                    $list->cd_biz_kind,
                    $list->cd_order_status,
                    $list->cd_pickup_status,
                    $list->cd_payment_status,
                    $list->cd_parking_status,
                );
                return [
                    'no_order' => $list->no_order,
                    'nm_order' => $list->nm_order,
                    'cd_order_status' => Arr::first($orderStatus),
                    'order_status' => Arr::last($orderStatus),
                    'dt_reg' => $list->dt_reg->format('Y-m-d H:i:s'),
                    'nm_partner' => $list->nm_partner,
                    'no_shop' => $list->no_shop ?? $list->id_site,
                    'nm_shop' => $list->shop?->nm_shop ?? $list->parkingSite?->nm_shop,
                    'cd_biz_kind' => $list->cd_biz_kind,
                    'biz_kind' => SearchBizKind::getBizKind((string)$list->cd_biz_kind)->name
                ];
            })
        ]);
    }

    public function getIncompleteOrder(string $bizKind, string $noOrder): JsonResponse
    {
        $orderInfo = match ($bizKind) {
            SearchBizKind::PARKING->name => OrderService::getParkingOrderInfo([
                'no_user' => Auth::id(),
                'no_order' => $noOrder
            ])->first(),
            default => OrderService::getOrderInfo([
                'no_user' => Auth::id(),
                'no_order' => $noOrder
            ])->first()
        };

        if (!$orderInfo) {
            throw new OwinException(Code::message('P2028'));
        }

        if (!in_array($orderInfo['cd_status'], ['800800', '800810'])) {
            throw new OwinException(Code::message('P2029'));
        }

        return response()->json([
            'result' => true,
            'order' => [
                'nm_shop' => $orderInfo->nm_shop ?? $orderInfo->parkingSite?->nm_shop ?? $orderInfo->autoParking?->nm_shop,
                'no_order' => $orderInfo->no_order,
                'no_order_user' => substr($orderInfo->no_order, -7),
                'biz_kind' => $bizKind,
                'nm_order' => $orderInfo->nm_order,
                'dt_reg' => $orderInfo->dt_reg->format('Y-m-d H:i:s'),
                'at_commission_rate' => $orderInfo->at_commission_rate,
                'at_send_price' => $orderInfo?->at_send_price,
                'at_disct' => $orderInfo->at_disct,
                'at_cpn_disct' => $orderInfo->at_cpn_disct,
                'at_price' => $orderInfo->at_price,
                'at_price_pg' => $orderInfo->at_price_pg,
                'cd_status' => $orderInfo->cd_status,
                'nm_status' => $orderInfo->nm_status,
                'no_shop' => $orderInfo?->no_shop,
                'no_site' => $orderInfo?->no_site,
            ],
            'cards' => Auth::user()->memberCard->unique('no_card')->map(function ($card) {
                return [
                    'no_card' => $card->no_card,
                    'no_card_user' => $card->no_card_user,
                    'cd_card_corp' => $card->cd_card_corp,
                    'card_corp' => CodeService::getCode($card->cd_card_corp)->nm_code,
                    'yn_main_card' => $card->yn_main_card
                ];
            })->sortByDesc('yn_main_card')->values()
        ]);
    }
}
