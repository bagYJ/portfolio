<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\Pg;
use App\Enums\ServicePayCode;
use App\Exceptions\OwinException;
use App\Jobs\ProcessArkServer;
use App\Jobs\ProcessFcm;
use App\Queues\Fcm\Fcm;
use App\Queues\Socket\ArkServer;
use App\Services\CodeService;
use App\Services\CouponService;
use App\Services\OrderService;
use App\Services\ParkingService;
use App\Services\Pg\PgService;
use App\Utils\Code;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class Parking extends Controller
{

    public function intro(Request $request): JsonResponse
    {
        $request->validate([
            'no_site' => 'required|integer',
        ]);

        $member = Auth::user();
        $noShop = intval($request->get('no_shop'));

        //이전주문내역
        $orderInfo = (new ParkingService())->ordering($member->no_user, [
            'no_site' => ['=', $noShop],
            'cd_parking_status' => ['=', 'WAIT'],
            'cd_order_status' => ['=', '601200'],
            'cd_payment_status' => ['=', '603300'],
        ])->first();

        $response = [
            'no_order' => $orderInfo?->no_order,
            'cars' => $member->memberCarInfoAll->sortByDesc('yn_main_car'),
            'cards' => $member->memberCard->filter(function ($query) {
                return $query['cd_pg'] == 500600;
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
            'coupons' => (new CouponService())->myParkingCoupon(
                $member['no_user'],
                'Y'
            ),
        ];

        return response()->json($response);
    }

    /**
     * 지도 위치 기준으로 주차장 조회
     * todo 사이즈 등 상세 조회 로직 추가
     * @param Request $request
     * @return JsonResponse
     */
    public function gets(Request $request): JsonResponse
    {
        $body = $request->toArray();
        Validator::make([
            'radius' => $body['radius'],
            'position' => $body['position'],
        ], [
            'radius' => 'required',
            'position' => 'required|array',
        ])->validate();

        $parkingSite = ParkingService::gets($body['radius'], $body['position']['x'], $body['position']['y']);
        if (count($parkingSite)) {
            return response()->json([
                'result' => true,
                'rows' => $parkingSite,
            ]);
        } else {
            throw new OwinException(Code::message('404'));
        }
    }

    /**
     * 주차장 단일 조회
     * @param string $noSite
     * @return JsonResponse
     * @throws OwinException
     */
    public function get(string $noSite): JsonResponse
    {
        $parkingSite = ParkingService::get([
            'no_site' => $noSite
        ]);
        if ($parkingSite) {
            return response()->json($parkingSite);
        } else {
            throw new OwinException('404');
        }
    }

    /**
     * 티켓 구매 및 결제
     * @param Request $request
     * @return JsonResponse
     */
    public function orderTicket(Request $request): JsonResponse
    {
        $request->validate([
            'no_site' => 'required|integer',
            'no_product' => 'required|integer',
            'cd_service_pay' => ['required', Rule::in(ServicePayCode::keys())],
            'at_price_total'     => 'required|numeric',
            'at_cpn_disct'       => 'required|numeric',
            'at_commission_rate' => 'numeric',
            'no_card' => 'required|numeric',
            'car_number' => 'required',
            'discount_info'      => 'nullable',
        ]);
        if (empty(Auth::user()->memberCarInfo->seq)) {
            throw new OwinException(Code::message('PA141'));
        }
        $parkingSite = ParkingService::get([
            'no_site' => $request->get('no_site')
        ]);

        $orderService = new OrderService();
        $response = $orderService->parkingPayment(Auth::user(), $parkingSite, collect($request->post()));
        if ($response['result']) {
            ProcessArkServer::dispatch(new ArkServer('ORDER', 'orderStatus', $request->input('no_site') . '00000000' . $response['no_order'], '11'))
                ->onConnection('database');
            ProcessFcm::dispatch(
                new Fcm('neworder', intval($request->input('no_shop')), (string)$response['no_order'], [
                    'no_order' => (string)$response['no_order'],
                    'no_order_user' => makeNoOrderUser((string)$response['no_order']),
                    'nm_order' => $response['nm_order'],
                    'isCurrent' => true,
                    'channel_id' => 'neworder',
                ])
            )->onConnection('database');
        }

        return response()->json([
            'result' => $response['result'],
            'no_order' => $response['no_order'],
            'message' => $response['msg']
        ]);
    }

    /**
     * 주차장 할인권 구매 정보 내역 조회
     * @param Request $request
     * @return JsonResponse
     * @throws OwinException
     */
    public function getMyTickets(Request $request): JsonResponse
    {
        $response = ParkingService::getOrderList(['no_user' => Auth::id()]);
        if ($response && $response['rows']) {
            return response()->json($response);
        } else {
            throw new OwinException(Code::message('404'));
        }
    }

    /**
     *
     * 구매한 웹 할인권 조회
     * @param Request $request
     * @return JsonResponse
     */
    public function getTicket(Request $request): JsonResponse
    {
        $request->validate([
            'no_order' => 'required|integer',
        ]);
        $data = ParkingService::getOrderInfo(Auth::id(), $request->get('no_order'));
        if ($data) {
            return response()->json($data);
        } else {
            throw new OwinException(Code::message('404'));
        }
    }

    /**
     * 웹 할인권 취소
     * @param Request $request
     * @return JsonResponse
     * @throws OwinException
     */
    public function cancelTicket(Request $request): JsonResponse
    {
        $request->validate([
            'no_order' => 'required|string',
        ]);
        $noOrder = $request->get('no_order');
        $order = ParkingService::getOrderInfo(Auth::id(), $noOrder);
        $parkingSite = ParkingService::get([
            'no_parking_site' => $order['no_parking_site']
        ]);
        if (!$parkingSite) {
            throw new OwinException(Code::message('M1304'));
        }

        if ($order['cd_parking_status'] != 'WAIT') {
            throw new OwinException(Code::message('1006'));
        }

        if ($order['dt_user_parking_canceled']) {
            throw new OwinException(Code::message('1006'));
        }

        $orderService = new OrderService();
        $response = $orderService->parkingRefund(
            Auth::user(),
            $parkingSite,
            (new PgService(Pg::from(500600)->name))->setPg(),
            $noOrder,
            '601900',
            Code::message('PG9999')
        );

        if ($order['at_cpn_disct'] > 0) {
            (new CouponService())->refundMemberParkingCoupon(
                $noOrder,
                Auth::id(),
            );
        }

        return response()->json($response);
    }

    /**
     * 자동취소 배치
     * @return array
     * @throws OwinException
     */
    public function autoCancelTicket()
    {
        $orders = ParkingService::getOrderList([
            ['cd_parking_status', '=', 'EXPIRED'],
            ['cd_payment_status', '=', '603300'],
            ['cd_order_status', '=', '601200'],
        ]);
        $count = 0;
        if (count($orders['rows'])) {
            try {
                $orderService = new OrderService();
                foreach ($orders['rows'] as $row) {
                    if ($row['user'] && $row['parkingSite']) {
                        $orderService->parkingRefund(
                            $row['user'],
                            $row['parkingSite'],
                            (new PgService(Pg::from(500600)->name))->setPg(),
                            $row['no_order'],
                            '601999',
                            Code::message('PG9998')
                        );
                        $count ++;
                    }
                }
                return [
                    'result' => true,
                    'count' => $count
                ];
            } catch (Exception $e) {
                return [
                    'result' => false,
                    'result_msg' => $e->getMessage()
                ];
            }
        }
        return [
            'result' => false,
            'result_msg' => Code::message('404')
        ];
    }

    public function adminCancelTicket(string $noOrder): JsonResponse
    {
        try {
            $orderService = new OrderService();
            $orders = ParkingService::getOrderList([
                'no_order' => $noOrder
            ]);

            $orderService->parkingRefund(
                $orders['rows']->first()->user,
                $orders['rows']->first()->parkingSite,
                (new PgService(Pg::from(500600)->name))->setPg(),
                $orders['rows']->first()->no_order,
                '601999',
                Code::message('PG9998')
            );

            return response()->json([
                'result' => true
            ]);
        } catch (Exception $e) {
            return response()->json([
                'result' => false,
                'result_msg' => $e->getMessage()
            ]);
        }
    }
}
