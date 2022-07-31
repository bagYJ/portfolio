<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Services\CustomerService;
use App\Services\MainTitleService;
use App\Services\NoticeService;
use App\Services\SearchService;
use App\Utils\Common;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class Main extends Controller
{
    public function main(Request $request): JsonResponse
    {
        $body = $request->toArray();
        Validator::make([
            'radius' => $body['radius'],
            'position' => $body['position'],
        ], [
            'radius' => 'required',
            'position' => 'required|array'
        ])->validate();

        $lists = (new SearchService())->homeShopList(
            $body['radius'],
            $body['position'],
        );

        return response()->json([
            'result' => true,
            'image_path' => getenv('IMAGE_PATH'),
            'recommend_product' => (new SearchService())->homeProductList(
                $body['radius'],
                $body['position'],
            ),
            'recommend_shop' => $lists->map(function ($list) {
                return [
                    'no_shop' => $list->no_shop,
                    'nm_shop' => $list->nm_shop,
                    'distance' => $list->distance,
                    'product' => $list->product->forPage(0, 5)->map(function ($product) {
                        return [
                            'no_product' => $product->no_product,
                            'nm_product' => $product->nm_product,
                            'at_price_before' => $product->at_price_before,
                            'at_price' => $product->at_price,
                            'ds_image_path' => Common::getImagePath($product->ds_image_path),
                            'at_ratio' => Common::getSaleRatio($product->at_price_before, $product->at_price)
                        ];
                    })
                ];
            })->forPage(0, 3),
        ]);
    }

    public function notice(): JsonResponse
    {
        return response()->json([
            'result' => true,
            'notice_list' => NoticeService::getMainNotice()
        ]);
    }

    public function header(): JsonResponse
    {
        return response()->json([
            'result' => true,
            'header' => MainTitleService::getRandomTitle()
        ]);
    }
}
