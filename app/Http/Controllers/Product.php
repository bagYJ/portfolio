<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Services\OrderService;
use App\Services\ProductService;
use App\Services\ShopService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class Product extends Controller
{
    public function getList(Request $request, int $noShop): JsonResponse
    {
        $request->validate([
            'noCategory' => 'nullable|integer'
        ]);
        $noCategory = (int)$request->get('noCategory');

        $shopInfo = ShopService::getShop($noShop);
        $product = ProductService::gets($shopInfo->no_partner, $noShop, $noCategory, $shopInfo->partner->cd_biz_kind);

        return response()->json([
            'result' => true,
            ...$product,
        ]);
    }

    /**
     * 현재위치알림
     * @param Request $request
     * @return JsonResponse
     */
    public function setGps(Request $request)
    {
        $request->validate([
            'no_order' => 'required|string',
            'at_lat' => 'required|numeric',
            'at_lng' => 'required|numeric',
            'at_distance' => 'required|numeric',
        ]);

        $noOrder = $request->get('no_order');
        $atLat = $request->get('at_lat');
        $atLng = $request->get('at_lng');
        $atDistance = $request->get('at_distance');

        OrderService::changeDistance($atDistance, $atLat, $atLng, $noOrder);

        return response()->json([
            'result' => true,
        ]);
    }

    public function product(int $noShop, int $noProduct): JsonResponse
    {
        return response()->json([
            'result' => true,
            'no_shop' => $noShop,
            'product' => (new ProductService())->getProduct(parameter: [
                'no_product' => $noProduct
            ], excludeShop: $noShop)->first()
        ]);
    }
}
