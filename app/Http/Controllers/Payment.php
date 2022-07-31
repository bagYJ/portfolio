<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\Pg;
use App\Enums\SearchBizKind;
use App\Exceptions\OwinException;
use App\Jobs\ProcessArkServer;
use App\Jobs\ProcessFcm;
use App\Queues\Fcm\Fcm;
use App\Queues\Socket\ArkServer;
use App\Services\OrderService;
use App\Services\Pg\PgService;
use App\Services\ShopService;
use App\Utils\Code;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class Payment extends Controller
{
    /**
     * 결제 취소
     * @param Request $request
     * @return JsonResponse
     */
    public function cancel(Request $request): JsonResponse
    {
        $request->validate([
            'no_order' => 'required|string',
            'cd_reject_reason' => 'nullable|string',
        ]);

        // 주문정보조회
        $orderInfo = OrderService::getOrder($request->no_order);
        if (!$orderInfo) {
            throw new OwinException(Code::message('P2120'));
        }

        $response = (new OrderService())->refund(
            user: Auth::user(),
            shop: ShopService::getShop($orderInfo->no_shop),
            pg: (new PgService(Pg::from($orderInfo->cd_pg)->name))->setPg(),
            noOrder: $orderInfo->no_order,
            cdOrderStatus: '601900',
            reason: $request->cd_reject_reason
        );

        if ($response['res_cd'] === '0000') {
            ProcessArkServer::dispatch(new ArkServer('ORDER', 'orderStatus', $orderInfo->no_shop . '1', '14'))
                ->onConnection('database');
        }

        return response()->json([
            'result' => match ($response['res_cd']) {
                '0000' => true,
                default => false
            },
            'msg' => $response['res_msg']
        ]);
    }

    /**
     * 관리자 PG 결제 취소
     * @param Request $request
     * @return JsonResponse
     */
    public function cancelAdmin(Request $request): JsonResponse
    {
        $request->validate([
            'no_order' => 'required|string',
            'cd_reject_reason' => 'nullable|string',
        ]);

        // 주문정보조회
        $orderInfo = OrderService::getOrder($request->no_order);
        if (!$orderInfo) {
            throw new OwinException(Code::message('P2120'));
        }

        $response = (new OrderService())->refundAdmin(
            shop: ShopService::getShop($orderInfo->no_shop),
            pg: (new PgService(Pg::from($orderInfo->cd_pg)->name))->setPg(),
            noOrder: $orderInfo->no_order,
            cdOrderStatus: '601999',
            reason: $request->cd_reject_reason
        );

        if ($response['res_cd'] === '0000') {
            ProcessArkServer::dispatch(new ArkServer('ORDER', 'orderStatus', $orderInfo->no_shop . '1', '14'))
                ->onConnection('database');

            ProcessFcm::dispatch(new Fcm(
                'FNB',
                $orderInfo->no_shop,
                $orderInfo->no_order,
                [
                    'ordering' => 'N',
                    'nm_shop' => sprintf('%s/%s', $orderInfo->partner->nm_partner, $orderInfo->shop->nm_shop)
                ],
                true,
                'user',
                $orderInfo->no_user,
                'cancel_etc'
            ))->onConnection('database');
        }

        return response()->json([
            'result' => match ($response['res_cd']) {
                '0000' => true,
                default => false
            },
            'msg' => $response['res_msg']
        ]);
    }

    public function incompletePayment(Request $request): JsonResponse
    {
        $request->validate([
            'biz_kind' => ['required', Rule::in(SearchBizKind::keys())],
            'no_order' => 'required|string',
            'no_card' => 'required|numeric'
        ]);

        $orderInfo = match ($request->biz_kind) {
            SearchBizKind::PARKING->name => OrderService::getParkingOrderInfo([
                'no_user' => Auth::id(),
                'no_order' => $request->no_order
            ])->first(),
            default => OrderService::getOrderInfo([
                'no_user' => Auth::id(),
                'no_order' => $request->no_order
            ])->first()
        };

        if (!$orderInfo) {
            throw new OwinException(Code::message('P2028'));
        }

        if (!in_array($orderInfo['cd_status'], ['800800', '800810'])) {
            throw new OwinException(Code::message('P2029'));
        }

        $response = match ($request->biz_kind) {
            SearchBizKind::PARKING->name => (new OrderService())->autoParkingPayment($orderInfo, $orderInfo['parkingSite'] ?? $orderInfo['autoParking'], $orderInfo['carInfo'], collect($request->post())),
            default => (new OrderService())->incompletePayment(Auth::user(), $orderInfo, collect($request->post())),
        };

        return response()->json([
            'result' => $response['result'],
            'no_order' => $response['no_order'],
            'message' => $response['msg'],
            'detail_message' => $response['pg_msg']
        ]);
    }
}
