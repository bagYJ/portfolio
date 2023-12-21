<?php
declare(strict_types=1);

namespace App\Http\Controllers\Retail;

use App\Enums\{AlarmEventType, MemberLevel, OrderStatus, PickupStatus};
use App\Exceptions\CustomCuException;
use App\Http\Controllers\Controller;
use App\Models\OrderList;
use App\Requests\Cu\{ArrivalAlarm,
    ArrivalConfirm,
    CancelCheck,
    DeliveryAlarm,
    DeliveryConfirm,
    Order,
    OrderCancel,
    OrderConfirm,
    OrderReady,
    ProductCheck};
use App\Responses\Cu\{ProductCheck as ProductCheckResponse, Response};
use App\Services\Production\Bizcall;
use App\Services\Production\Push;
use App\Services\Production\Rkm;
use App\Utils\{Code, Common};
use Exception;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use OpenApi\Attributes as OA;
use Owin\OwinCommonUtil\CodeUtil;
use Throwable;

#[OA\Tag(name: 'cu', description: 'CU편의점')]
class Cu extends Controller
{
    private readonly \App\Services\Dev\Cu|\App\Services\Production\Cu $service;
    private readonly \App\Services\Dev\Bizcall|Bizcall $bizcall;
    private readonly \App\Services\Dev\Push|Push $push;
    private readonly \App\Services\Dev\Rkm|Rkm $rkm;

    public function __construct()
    {
        $this->service = Common::getService('Cu');
        $this->bizcall = Common::getService('Bizcall');
        $this->push = Common::getService('Push');
        $this->rkm = Common::getService('Rkm');
    }

    /**
     * @param ProductCheck $request
     * @return JsonResponse
     * @throws Exception
     */
    #[OA\Post(
        path: '/retail/cu/product-check',
        summary: '상품재고조회요청',
        requestBody: new OA\RequestBody(content: new OA\MediaType(mediaType: 'application/json', schema: new OA\Schema(ProductCheck::class))),
        tags: ['cu'],
        responses: [
            new OA\Response(response: 200, description: 'Success', content: new OA\JsonContent(ref: ProductCheckResponse::class)),
            new OA\Response(ref: '#/components/responses/Exception', response: 'default')
        ]
    )]
    public function productCheck(ProductCheck $request): JsonResponse
    {
        $response = $this->service::productCheck($request);
        return response()->json(new ProductCheckResponse($response));
    }

    /**
     * @param CancelCheck $request
     * @return JsonResponse
     * @throws Exception
     */
    #[OA\Post(
        path: '/retail/cu/cancel-check',
        summary: '주문 자동취소 전 알림',
        requestBody: new OA\RequestBody(content: new OA\MediaType(mediaType: 'application/json', schema: new OA\Schema(CancelCheck::class))),
        tags: ['cu'],
        responses: [
            new OA\Response(response: 200, description: 'Success', content: new OA\JsonContent(ref: Response::class)),
            new OA\Response(ref: '#/components/responses/Exception', response: 'default')
        ]
    )]
    public function cancelCheck(CancelCheck $request): JsonResponse
    {
        $response = $this->service::cancelCheck($request);
        return response()->json(new Response($response));
    }

    /**
     * @param OrderCancel $request
     * @return JsonResponse
     * @throws Exception
     */
    #[OA\Post(
        path: '/retail/cu/order-cancel',
        summary: '주문 취소',
        requestBody: new OA\RequestBody(content: new OA\MediaType(mediaType: 'application/json', schema: new OA\Schema(OrderCancel::class))),
        tags: ['cu'],
        responses: [
            new OA\Response(response: 200, description: 'Success', content: new OA\JsonContent(ref: Response::class)),
            new OA\Response(ref: '#/components/responses/Exception', response: 'default')
        ]
    )]
    public function orderCancel(OrderCancel $request): JsonResponse
    {
        $response = $this->service::orderCancel($request);
        return response()->json(new Response($response));
    }

    /**
     * @param ArrivalAlarm $request
     * @return JsonResponse
     * @throws Exception
     */
    #[OA\Post(
        path: '/retail/cu/arrival-alarm',
        summary: '매장도착 알림',
        requestBody: new OA\RequestBody(content: new OA\MediaType(mediaType: 'application/json', schema: new OA\Schema(ArrivalAlarm::class))),
        tags: ['cu'],
        responses: [
            new OA\Response(response: 200, description: 'Success', content: new OA\JsonContent(ref: Response::class)),
            new OA\Response(ref: '#/components/responses/Exception', response: 'default')
        ]
    )]
    public function arrivalAlarm(ArrivalAlarm $request): JsonResponse
    {
        $response = $this->service::arrivalAlarm($request);
        return response()->json(new Response($response));
    }

    /**
     * @param DeliveryAlarm $request
     * @return JsonResponse
     * @throws Exception
     */
    #[OA\Post(
        path: '/retail/cu/delivery-alarm',
        summary: '전달완료 요청알림',
        requestBody: new OA\RequestBody(content: new OA\MediaType(mediaType: 'application/json', schema: new OA\Schema(DeliveryAlarm::class))),
        tags: ['cu'],
        responses: [
            new OA\Response(response: 200, description: 'Success', content: new OA\JsonContent(ref: Response::class)),
            new OA\Response(ref: '#/components/responses/Exception', response: 'default')
        ]
    )]
    public function deliveryAlarm(DeliveryAlarm $request): JsonResponse
    {
        $response = $this->service::deliveryAlarm($request);
        return response()->json(new Response($response));
    }

    /**
     * @param string $noOrder
     * @return JsonResponse
     * @throws Exception
     */
    #[OA\Get(
        path: '/retail/cu/order/{noOrder}',
        summary: '주문정보전달',
        tags: ['cu'],
        parameters: [
            new OA\Parameter(name: 'noOrder', in: 'path', required: true, schema: new OA\Schema(type: 'string'))
        ],
        responses: [
            new OA\Response(response: 200, description: 'Success', content: new OA\JsonContent(ref: Response::class)),
            new OA\Response(ref: '#/components/responses/Exception', response: 'default')
        ]
    )]
    public function order(string $noOrder): JsonResponse
    {
        $serviceSchemaEnum = CodeUtil::getServiceSchemaEnumFromOrderCode($noOrder);
        DB::statement('use ' . $serviceSchemaEnum->value);

        $orderList = Order::getOrderInfo($noOrder);
        $response = $this->service::order((new Order($orderList)));
        return response()->json(new Response($response));
    }

    /**
     * @param OrderConfirm $request
     * @return string
     * @throws Exception
     * @throws GuzzleException
     */
    #[OA\Post(
        path: '/retail/cu/order-confirm',
        summary: '주문접수/거절',
        requestBody: new OA\RequestBody(content: new OA\MediaType(mediaType: 'application/json', schema: new OA\Schema(OrderConfirm::class))),
        tags: ['cu'],
        responses: [
            new OA\Response(response: 200, description: 'Success', content: new OA\MediaType(mediaType: 'text/plain', schema: new OA\Schema(type: 'string'))),
            new OA\Response(ref: '#/components/responses/Exception', response: 'default')
        ]
    )]
    public function orderConfirm(OrderConfirm $request): string
    {
        $serviceSchemaEnum = CodeUtil::getServiceSchemaEnumFromOrderCode($request->no_order);
        DB::statement('use ' . $serviceSchemaEnum->value);

        $order = (new OrderList())->with([
            'member.detail',
            'shop.partner',
            'orderPayment'
        ])->find($request->no_order);

        try {
            $response = (new Response(match ($request->yn_cancel) {
                'Y' => $this->service::orderRefund($request, $order),
                default => $this->service::updateOrder($request->no_order, $request->partner_code, $request->shop_code, [
                    'cd_pickup_status' => PickupStatus::ACCEPT_ORDER->value,
                    'dt_pickup_status' => now(),
                    'cd_alarm_event_type' => AlarmEventType::ACCEPT_ORDER->value,
                    'confirm_date' => now()
                ], $request->json())
            }));
        } catch (Throwable $t) {
            return base64_encode(json_encode(new Response([
                'partner_code' => $request->partner_code,
                'shop_code' => $request->shop_code,
                'result_code' => $t->getCode(),
                'result_msg' => $t->getMessage(),
            ])));
        }
        if ($response->result_code == env('RETURN_TRUE')) {
            match ($request->yn_cancel) {
                'Y' => $this->push::send(
                    $order,
                    sprintf('%s%s', env(sprintf('API_URI_%s', strtoupper(CodeUtil::getServiceSchemaEnumFromOrderCode($request->no_order)->value))), env('API_PATH_PUSH')),
                    'RETAIL',
                    'cancel_etc'
                ),
                default => $this->push::send(
                    $order,
                    sprintf('%s%s', env(sprintf('API_URI_%s', strtoupper(CodeUtil::getServiceSchemaEnumFromOrderCode($request->no_order)->value))), env('API_PATH_PUSH')),
                    'RETAIL',
                    'shop_accept'
                )
            };

            if ($order->member->cd_mem_level == MemberLevel::AVN->value && empty($order->member->detail->ds_access_vin_rsm) === false) {
                match ($request->yn_cancel) {
                    'Y' => $this->rkm::push($order, 'RETAIL', 'cancel_etc'),
                    default => $this->rkm::push($order, 'RETAIL', 'shop_accept'),
                };
            }
        }

        return base64_encode(json_encode($response));
    }

    /**
     * @param OrderReady $request
     * @return string
     * @throws GuzzleException
     */
    #[OA\Post(
        path: '/retail/cu/order-ready',
        summary: '준비완료',
        requestBody: new OA\RequestBody(content: new OA\MediaType(mediaType: 'application/json', schema: new OA\Schema(OrderReady::class))),
        tags: ['cu'],
        responses: [
            new OA\Response(response: 200, description: 'Success', content: new OA\MediaType(mediaType: 'text/plain', schema: new OA\Schema(type: 'string'))),
            new OA\Response(ref: '#/components/responses/Exception', response: 'default')
        ]
    )]
    public function orderReady(OrderReady $request): string
    {
        try {
            $serviceSchemaEnum = CodeUtil::getServiceSchemaEnumFromOrderCode($request->no_order);
            DB::statement('use ' . $serviceSchemaEnum->value);

            $response = new Response($this->service::updateOrder($request->no_order, $request->partner_code, $request->shop_code, [
                'cd_pickup_status' => PickupStatus::READY_PICKUP->value,
                'dt_pickup_status' => now(),
                'cd_alarm_event_type' => AlarmEventType::COMPLETE_READY->value,
                'ready_date' => now()
            ], $request->json()));
        } catch (Throwable $t) {
            return base64_encode(json_encode(new Response([
                'partner_code' => $request->partner_code,
                'shop_code' => $request->shop_code,
                'result_code' => $t->getCode(),
                'result_msg' => $t->getMessage(),
            ])));
        }
        if ($response->result_code == env('RETURN_TRUE')) {
            $order = (new OrderList())->with([
                'member.detail',
                'shop.partner'
            ])->find($request->no_order);
            $this->push::send($order,
                sprintf('%s%s', env(sprintf('API_URI_%s', strtoupper(CodeUtil::getServiceSchemaEnumFromOrderCode($request->no_order)->value))), env('API_PATH_PUSH')),
                'RETAIL',
                'shop_complete'
            );

            if ($order->member->cd_mem_level == MemberLevel::AVN->value && empty($order->member->detail->ds_access_vin_rsm) === false) {
                $this->rkm::push($order, 'RETAIL', 'shop_complete');
            }
        }

        return base64_encode(json_encode($response));
    }

    /**
     * @param ArrivalConfirm $request
     * @return string
     */
    #[OA\Post(
        path: '/retail/cu/arrival-confirm',
        summary: '도착알림 처리 (확인)',
        requestBody: new OA\RequestBody(content: new OA\MediaType(mediaType: 'application/json', schema: new OA\Schema(ArrivalConfirm::class))),
        tags: ['cu'],
        responses: [
            new OA\Response(response: 200, description: 'Success', content: new OA\MediaType(mediaType: 'text/plain', schema: new OA\Schema(type: 'string'))),
            new OA\Response(ref: '#/components/responses/Exception', response: 'default')
        ]
    )]
    public function arrivalConfirm(ArrivalConfirm $request): string
    {
        try {
            $serviceSchemaEnum = CodeUtil::getServiceSchemaEnumFromOrderCode($request->no_order);
            DB::statement('use ' . $serviceSchemaEnum->value);

            $response = new Response($this->service::updateOrder($request->no_order, $request->partner_code, $request->shop_code, [
                'cd_alarm_event_type' => AlarmEventType::CHECK_CLERK->value
            ], $request->json()));
        } catch (Throwable $t) {
            return base64_encode(json_encode(new Response([
                'partner_code' => $request->partner_code,
                'shop_code' => $request->shop_code,
                'result_code' => $t->getCode(),
                'result_msg' => $t->getMessage(),
            ])));
        }

        return base64_encode(json_encode($response));
    }

    /**
     * @param DeliveryConfirm $request
     * @return string
     * @throws Exception
     */
    #[OA\Post(
        path: '/retail/cu/delivery-confirm',
        summary: '전달완료 요청알림 처리 (완료/닫기)',
        requestBody: new OA\RequestBody(content: new OA\MediaType(mediaType: 'application/json', schema: new OA\Schema(DeliveryConfirm::class))),
        tags: ['cu'],
        responses: [
            new OA\Response(response: 200, description: 'Success', content: new OA\MediaType(mediaType: 'text/plain', schema: new OA\Schema(type: 'string'))),
            new OA\Response(ref: '#/components/responses/Exception', response: 'default')
        ]
    )]
    public function deliveryConfirm(DeliveryConfirm $request): string
    {
        try {
            $serviceSchemaEnum = CodeUtil::getServiceSchemaEnumFromOrderCode($request->no_order);
            DB::statement('use ' . $serviceSchemaEnum->value);

            $order = (new OrderList())->with([
                'member.detail',
                'shop.partner'
            ])->find($request->no_order);
            if ($order->cd_order_status >= OrderStatus::MEMBER_CANCEL->value) {
                throw new CustomCuException($request->partner_code, $request->shop_code, Code::message('9970'), 9970);
            }
            $completeCnt = $this->service::getOrderCompleteLogCnt($request->no_order);
            if ($request->yn_delivery == 'Y') {
                $this->service::registMemberShopRetailLog([
                    'no_user' => $order->no_user,
                    'no_shop' => $order->no_shop,
                    'no_order' => $order->no_order,
                    'cd_alarm_event_type' => AlarmEventType::COMPLETE_PICKUP->value
                ]);
                if ($order->cd_pickup_status !== PickupStatus::PROCESSED->value) {
                    $this->service::updateOrder($request->no_order, $request->partner_code, $request->shop_code, [
                        'cd_pickup_status' => PickupStatus::PROCESSED->value,
                        'cd_alarm_event_type' => AlarmEventType::COMPLETE_PICKUP->value
                    ], $request->json());

                    $this->bizcall::closeMapping($order->ds_safe_number);
                }
            }

            if ($order->cd_pickup_status == PickupStatus::PROCESSED->value && $completeCnt > 0) {
                $this->service::registRetailAdminChkLog([
                    'no_order' => $order->no_order,
                    'no_user' => $order->no_user,
                    'no_shop' => $order->no_shop,
                    'log_type' => '902',
                    'content' => '픽업완료 이후 CU매장에서 픽업완료 처리'
                ]);
            }

            $this->service::registRetailExternalApiLog($request->partner_code, $request->shop_code, '', getenv('RETURN_TRUE'));
        } catch (Throwable $t) {
            return base64_encode(json_encode(new Response([
                'partner_code' => $request->partner_code,
                'shop_code' => $request->shop_code,
                'result_code' => $t->getCode(),
                'result_msg' => $t->getMessage(),
            ])));
        }
        $response = new Response((array)$request);
        if ($response->result_code == env('RETURN_TRUE')) {
            $this->push::send($order,
                sprintf('%s%s', env(sprintf('API_URI_%s', strtoupper(CodeUtil::getServiceSchemaEnumFromOrderCode($request->no_order)->value))), env('API_PATH_PUSH')),
                'RETAIL',
                'delivery_complete'
            );

            if ($order->member->cd_mem_level == MemberLevel::AVN->value && empty($order->member->detail->ds_access_vin_rsm) === false) {
                $this->rkm::push($order, 'RETAIL', 'shop_complete');
            }
        }

        return base64_encode(json_encode($response));
    }
}
