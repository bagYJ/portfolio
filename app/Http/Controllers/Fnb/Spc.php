<?php

declare(strict_types=1);

namespace App\Http\Controllers\Fnb;

use App\Http\Controllers\Controller;
use App\Requests\Spc\{Cancel, Order, OrderStatus, ProductStatus, ShopStatus, Stock, Uptime};
use App\Responses\Spc\{Failed, Response, Stock as StockResponse,};
use App\Utils\Common;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Owin\OwinCommonUtil\CodeUtil;
use Throwable;


class Spc extends Controller
{
    private readonly \App\Services\Dev\Spc|\App\Services\Production\Spc $service;

    public function __construct()
    {
        $this->service = Common::getService('Spc');
    }

    /**
     * @param string $noOrder
     * @return JsonResponse
     * @throws Exception
     */
    public function order(string $noOrder): JsonResponse
    {
        $serviceSchemaEnum = CodeUtil::getServiceSchemaEnumFromOrderCode($noOrder);
        DB::statement('use ' . $serviceSchemaEnum->value);

        $orderList = Order::getOrderInfo($noOrder);
        $response = $this->service::order((new Order($orderList)));
        return response()->json(new Response($response));
    }

    /**
     * @param Stock $request
     * @return JsonResponse
     * @throws Exception
     */
    public function stock(Stock $request): JsonResponse
    {
        $response = $this->service::stock($request);
        return response()->json(new StockResponse($response));
    }

    /**
     * @param Cancel $request
     * @return JsonResponse
     * @throws Exception
     */
    public function cancel(Cancel $request): JsonResponse
    {
        $response = $this->service::cancel($request);
        return response()->json(new Response($response));
    }

    /**
     * @param Uptime $request
     * @return JsonResponse
     * @throws Exception
     */
    public function uptime(Uptime $request): JsonResponse
    {
        $response = $this->service::uptime($request);
        return response()->json(new Response($response));
    }

    /**
     * @param OrderStatus $request
     * @return JsonResponse
     */
    public function orderStatusChange(OrderStatus $request): JsonResponse
    {
        try {
            $response = $this->service::updateOrderStatus($request);
            return response()->json(new Response($response));
        } catch (Throwable $t) {
            return response()->json(new Failed($t));
        }
    }

    /**
     * @param ProductStatus $request
     * @return JsonResponse
     */
    public function productStatusChange(ProductStatus $request): JsonResponse
    {
        try {
            $response = $this->service::updateProductStatus($request);
            return response()->json(new Response($response));
        } catch (Throwable $t) {
            return response()->json(new Failed($t));
        }
    }

    /**
     * @param ShopStatus $request
     * @return JsonResponse
     */
    public function shopStatusChange(ShopStatus $request): JsonResponse
    {
        try {
            $response = $this->service::updateShopStatus($request);
            return response()->json(new Response($response));
        } catch (Throwable $t) {
            return response()->json(new Failed($t));
        }
    }
}