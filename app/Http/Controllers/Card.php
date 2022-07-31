<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Exceptions\OwinException;
use App\Services\CardService;
use App\Services\CodeService;
use App\Services\MemberService;
use App\Services\OrderService;
use App\Utils\AutoParking as AutoParkingUtil;
use App\Utils\Code;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Card extends Controller
{
    /**
     * 카드 등록 (/card/regist)
     *
     * @param Request $request
     * @return JsonResponse
     * @throws OwinException
     */
    public function regist(Request $request): JsonResponse
    {
        $request->validate([
            'no_cardnum' => 'required|numeric|digits:16',
            'no_expyea' => 'required|digits:2',
            'no_expmon' => 'required|digits:2',
            'no_pin' => 'required|digits:2'
        ]);

        // KB 알파카드 등록 차단 [2018.11.09 김목영 추가]
        if (in_array(substr($request->no_cardnum, 0, 6), ['949098', '516453'])) {
            throw new OwinException(Code::message('P1024_1'));
        }

        $noCard = (new CardService())->regist(Auth::id(), $request->all(), Auth::user()->memberCard?->where('yn_main_card', 'Y')->count() <= 0);

        return response()->json([
            'result' => true,
            'no_card' => $noCard
        ]);
    }

    /**
     * 카드리스트 (/card/lists)
     *
     * @return JsonResponse
     */
    public function lists(): JsonResponse
    {
        return response()->json([
            'result' => true,
            'card_list' => Auth::user()->memberCard->unique('no_card')->map(function ($card) {
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

    /**
     * 카드등록갯수 (/card/get_card_cnt)
     *
     * @return JsonResponse
     */
    public function cardCnt(): JsonResponse
    {
        return response()->json([
            'result' => true,
            'ct_card' => Auth::user()->memberCard->unique('no_card')->count()
        ]);
    }

    /**
     * 카드삭제 (/card/remove)
     *
     * @param int $noCard
     * @return JsonResponse
     */
    public function remove(int $noCard): Jsonresponse
    {

        //주문내역 체크
        (new OrderService())->checkIncompleteOrder(Auth::id());

        //자동결제 차량에 연결된 카드 삭제
        Auth::user()->memberCarInfoAll->where('no_card', $noCard)
            ->where('yn_use_auto_parking', 'Y')
            ->whenNotEmpty(function ($collect) {
                $dsCarNumber = $collect->first()->ds_car_number;
                AutoParkingUtil::registerCar($dsCarNumber, false);
                MemberService::updateAutoParkingInfo([
                    'no_user' => Auth::id(),
                    'ds_car_number' => $dsCarNumber
                ], [
                    'yn_use_auto_parking' => 'N',
                    'no_card' => null,
                    'dt_auto_parking' => Carbon::now(),
                ]);
            });

        //카드 삭제
        Auth::user()->memberCard->where('no_card', $noCard)->whenEmpty(function () {
            throw new OwinException(Code::message('P1020'));
        }, function ($card) use ($noCard) {
            (new CardService())->remove($noCard, Auth::id());

            if ($card->first()->yn_main_card == 'Y') {
                MemberService::updateMemberCardInfo(Auth::user()->refresh()->memberCard?->first(), [
                    'yn_main_card' => 'Y'
                ]);
            }
        });

        return response()->json([
            'result' => true
        ]);
    }

    public function mainCard(int $noCard): JsonResponse
    {
        Auth::user()->memberCard->whenNotEmpty(function ($card) use ($noCard) {
            $card->where('no_card', $noCard)->whenEmpty(function () {
                throw new OwinException(Code::message('P1020'));
            });
        })->map(function ($card) use ($noCard) {
            MemberService::updateMemberCardInfo($card, [
                'yn_main_card' => $card->no_card == $noCard ? 'Y' : 'N'
            ]);
        });

        return response()->json([
            'result' => true
        ]);
    }
}
