<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\EnumYN;
use App\Enums\ServiceCode;
use App\Enums\ServicePayCode;
use App\Exceptions\OwinException;
use App\Jobs\ProcessArkServer;
use App\Jobs\ProcessFcm;
use App\Queues\Fcm\Fcm;
use App\Queues\Socket\ArkServer;
use App\Services\CouponService;
use App\Services\OrderService;
use App\Services\ShopService;
use App\Services\WashService;
use App\Utils\Code;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class Wash extends Controller
{
    /**
     * 세차주문정보 요청
     *
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

        $cdBizKind = Code::conf('biz_kind.wash');
        //이전주문내역
        $orderInfo = OrderService::getUserOrderInfo([
            ['a.no_user', '=', $member['no_user']],
            ['a.no_shop', '=', $noShop],
            ['b.cd_biz_kind', '=', $cdBizKind],
            ['a.cd_pickup_status', '<', 602400],
            ['a.cd_order_status', '=', '601200'],
            ['a.cd_payment_status', '=', '603300'],
        ]);

        $unUseCards = array_unique(
            ShopService::getShopUnUseCards($noShop)->pluck('cd_card_corp')->all()
        );
        $response = [
            'no_order' => $orderInfo?->no_order,
            'cars' => $member->memberCarInfoAll->sortByDesc('yn_main_car'),
            'cards' => $member->memberCard->filter(
                function ($query) use ($unUseCards) {
                    return !in_array($query['cd_card_corp'], $unUseCards)
                        && $query['cd_pg'] === '500100';
                }
            )->map(function ($collect) {
                return [
                    'no_seq' => $collect->no_seq,
                    'cd_card_corp' => $collect->cd_card_corp, //const 로 변경 필요
                    'no_card' => $collect->no_card,
                    'no_card_user' => $collect->no_card_user,
                    'nm_card' => $collect->nm_card,
                    'yn_main_card' => $collect->yn_main_card,
                    'yn_credit' => $collect->yn_credit,
                ];
            })->sortByDesc('yn_main_card')->values(),
            'coupons' => (new CouponService())->myWashCoupon(
                $member['no_user'],
                'Y'
            ),
            'products' => WashService::getWashProductList($noShop),
        ];

        return response()->json($response);
    }

    /**
     * 세차주문 처리 - PG결제, 결제요청
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function payment(Request $request): JsonResponse
    {
        $request->validate([
            'cd_service' => ['required', Rule::in(ServiceCode::keys())],
            'cd_service_pay' => ['required', Rule::in(ServicePayCode::keys())],
            'no_shop' => 'required|numeric:8',
            'at_price_total' => 'required|numeric',
            'at_price_calc' => 'required|numeric',
            'at_cpn_disct' => 'required|numeric',
            'no_card' => 'required|numeric',
            'car_number' => 'required',
            'list_product' => 'required|array',
            'discount_info' => 'nullable|array'
        ]);

        $shop = ShopService::getShop($request->no_shop);
        if ($shop->ds_status != EnumYN::Y->name) {
            throw new OwinException(Code::message('M1304'));
        }

        $orderService = new OrderService();
        $response = $orderService->payment(
            Auth::user(),
            $shop,
            collect($request->post())
        );
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
                new Fcm('neworder', (int)$request->no_shop, $response['no_order'], [
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
            'message' => $response['msg']
        ]);
    }

    /**
     * [처리] 세차요청처리 - 결과메세지 전달 (세차직원확인->세차요청으로 변경)
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function orderComplete(Request $request): JsonResponse
    {
        $request->validate([
            'no_order' => 'required|string'
        ]);

        $orderInfo = OrderService::getOrder($request->get('no_order'));
        if (!$orderInfo) {
            throw new OwinException(Code::message('P2120'));
        }

        if ($orderInfo['cd_order_status'] > 601200) {
            // 취소된 주문인경우
            throw new OwinException(Code::message('P2401'));
        } elseif ($orderInfo['cd_pickup_status'] != '602100') {
            // 대기상태주문이 아닌경우
            throw new OwinException(Code::message('P2407'));
        }

        WashService::washComplete(Auth::user(), $orderInfo);

        ProcessArkServer::dispatch(
            new ArkServer(
                'ORDER',
                'orderStatus',
                "WK" . $orderInfo->shop->washInShop->no_shop_in
                . $orderInfo->no_order,
                '52'
            )
        )->onConnection('database');

        return response()->json([
            'result' => true
        ]);
    }
}
