<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Exceptions\TMapException;
use App\Services\TmapService;
use App\Utils\Code;
use App\Utils\Common;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Tmap extends Controller
{
    /**
     * 회원인증
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function authorization(Request $request)
    {
        $request->validate([
            'ci' => 'required',
        ]);

        $response = (new TmapService())->authorization($request);
        return response()->json([
            'result' => "1",
            'access_token' => $response->plainTextToken
        ]);
    }

    /**
     * 로그아웃
     * @return JsonResponse
     */
    public function logout(): JsonResponse
    {
        Auth::user()->tokens()->delete();

        return response()->json([
            'result' => true,
        ]);
    }

    /**
     * 회원 정보 리턴
     * @return JsonResponse
     */
    public function getInfo(): JsonResponse
    {
        return response()->json((new TmapService())->getMemberInfo(Auth::id()));
    }

    /**
     * userCarList:: 회원의 등록한 자동차 리스트를 가지고 온다
     *
     * @return JsonResponse
     */
    public function userCarList(): JsonResponse
    {
        $cars = Auth::user()->memberCarInfoAll->map(function ($car) {
            return [
                'no_seq' => intval($car['seq']),
                'ds_kind' => $car->carList->ds_kind,
                'no_maker' => (string)$car->carList->no_maker,
                'ds_maker' => $car->carList->ds_maker,
                'ds_car_number' => $car->ds_car_number,
                'cd_gas_kind' => $car->cd_gas_kind,
            ];
        });

        return response()->json([
            'result' => "1",
            'car_info' => $cars,
        ]);
    }

    /**
     * userCardList:: 회원이 등록한 카드리스트
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function userCardList(Request $request): JsonResponse
    {
        $user = Auth::user();
        if ($user->memberDetail->yn_account_status_rsm !== 'Y') {
            throw new TMapException('M1505', 400);
        }

        $memberCards = Auth::user()->memberCard->unique('no_card')->map(function ($card, $index) {
            return [
                'no_card' => (string)$card['no_card'],
                'no_card_user' => $card['no_card_user'],
                'cd_card_corp' => $card['cd_card_corp'],
                'dt_reg' => $card['dt_reg'],
                'img_card' => Common::getImagePath(Code::conf("card_image.{$card['cd_card_corp']}"), "/data2/card/"),
                'yn_main_card' => $index === 0 ? 'Y' : 'N',
                'cd_payment_method' => '504100',
            ];
        })->values();

        return response()->json([
            'result' => '1',
            'list_card' => $memberCards
        ]);
    }

    /**
     * 주문내역
     * @param Request $request
     *
     * @return JsonResponse
     * @throws TMapException
     */
    public function userOrderList(Request $request): JsonResponse
    {
        $request->validate([
            'cd_service' => 'required|string',
            'ct_page_now' => 'nullable|integer',
            'ct_page_num' => 'nullable|integer'
        ]);

        if ($request->get('cd_service') != '900100') {
            throw new TMapException('C0900', 400);
        }

        $currentPage = intval($request->get('ct_page_now') ?? 1);
        $size = intval($request->get('ct_page_num') ?? 10);
        $response = (new TmapService())->getOrderList(Auth::id(), $currentPage, $size);

        return response()->json([
            'result' => true,
            'ct_page_now' => $currentPage,
            'ct_page_num' => $size,
            'ct_page_total' => ceil($response['count'] / $size),
            'ct_total' => $response['count'],
            'list_order' => $response['rows'],
        ]);
    }

    /**
     * 주문상세
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function userOrderDetail(Request $request): JsonResponse
    {
        $request->validate([
            'no_order' => 'required',
        ]);
        
        return response()->json(
            (new TmapService())->getOrderDetail($request->get('no_order'), Auth::user())
        );
    }

    /**
     * 주유소 리스트
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function opinetList(Request $request): JsonResponse
    {
        $list = (new TmapService())->getOilShopList($request->all());
        return response()->json($list);
    }

    /**
     * 주유소 단일 조회
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function get(Request $request): JsonResponse
    {
        $request->validate([
            'type' => 'required|in:OWIN,OPINET',
            'code' => 'required'
        ]);

        $shop = (new TmapService())->getOilShopList($request->all())->whenEmpty(function () {
            throw new TMapException('M1303', 400);
        })->first();

        return response()->json($shop);
    }
}
