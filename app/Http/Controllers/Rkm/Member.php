<?php

declare(strict_types=1);

namespace App\Http\Controllers\Rkm;

use App\Enums\AppType;
use App\Enums\EnumYN;
use App\Enums\MemberLevel;
use App\Exceptions\OwinException;
use App\Services\CodeService;
use App\Services\MemberService;
use App\Services\OrderService;
use App\Utils\Code;
use App\Utils\Common;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class Member extends \App\Http\Controllers\Member
{
    public function regist(Request $request): JsonResponse
    {
        $osType = CodeService::getGroupCode('103');

        $request->validate([
            'id_user' => 'required|string|email:strict',
            'nm_user' => 'required',
            'ds_birthday' => 'required|size:8',
            'ds_phone' => 'required|min:10|max:11',
            'cd_phone_os' => ['string', Rule::in($osType->pluck('no_code')->values())],
            'ds_udid' => 'required',
            'ds_phone_model' => 'string',
            'ds_phone_version' => 'string',
            'ds_phone_nation' => 'string',
            'ds_phone_token' => 'required',
            'ds_ci' => 'required',
            'ds_di' => 'required'
        ]);

        $accessToken = MemberService::getMember([
            'ds_ci' => $request->ds_ci
        ])->whenEmpty(function () use ($request) {
            $noUser = Common::generateNoUser();

            MemberService::createMember([
                'member' => [
                    'id_user' => $request->id_user,
                    'ds_status' => EnumYN::Y->name,
                    'ds_phone' => $request->ds_phone,
                    'ds_birthday' => $request->ds_birthday,
                    'ds_ci' => $request->ds_ci,
                    'ds_di' => $request->ds_di,
                    'nm_nick' => $request->nm_user,
                    'nm_user' => $request->nm_user,
                    'cd_mem_level' => MemberLevel::OWIN->value
                ],
                'detail' => [
                    'ds_udid' => $request->ds_udid,
                    'ds_phone_agency' => $request->ds_phone_agency,
                    'cd_phone_os' => $request->cd_phone_os,
                    'ds_phone_model' => $request->ds_phone_model,
                    'ds_phone_version' => $request->ds_phone_version,
                    'ds_phone_nation' => $request->ds_phone_nation,
                    'ds_phone_token' => $request->ds_phone_token,
                    'cd_third_party' => AppType::OWIN->value
                ]
            ], $noUser);

            $token = MemberService::createAccessToken($noUser)->plainTextToken;
            MemberService::updateMemberDetail([
                'ds_access_token_api' => $token
            ], [
                'no_user' => $noUser
            ]);

            return $token;
        }, function ($member) use ($request) {
            if ($member->first()->ds_status == 'N') {
                throw new OwinException(Code::message('M1413'));
            }

            $token = MemberService::createAccessToken($member->first()->no_user)->plainTextToken;

            MemberService::updateMember([
                'ds_phone' => $request->ds_phone,
                'ds_birthday' => $request->ds_birthday,
                'nm_nick' => $request->nm_user,
                'nm_user' => $request->nm_user
            ], [
                'no_user' => $member->first()->no_user
            ]);
            MemberService::updateMemberDetail([
                'ds_udid' => $request->ds_udid,
                'ds_phone_agency' => $request->ds_phone_agency,
                'cd_phone_os' => $request->cd_phone_os,
                'ds_phone_model' => $request->ds_phone_model,
                'ds_phone_version' => $request->ds_phone_version,
                'ds_phone_nation' => $request->ds_phone_nation,
                'ds_phone_token' => $request->ds_phone_token,
                'ds_access_token_api' => $token,
            ], [
                'no_user' => $member->first()->no_user
            ]);

            return $token;
        });

        return response()->json([
            'result' => true,
            'access_token' => $accessToken
        ]);
    }

    public function withdrawal(Request $request): JsonResponse
    {
        $request->validate([
            'no_withdrawal' => 'required|integer|in:1, 2, 3, 4',
            'ds_withdrawal' => 'nullable|string'
        ]);

        (new OrderService())->orderingByExternal(Auth::id())->whenNotEmpty(function () {
            throw new OwinException(Code::message('P2400'));
        });

        (new MemberService())->withdrawalMember([
            'member' => [
                'ds_status' => 'N'
            ],
            'detail' => [
                'no_withdrawal' => $request['no_withdrawal'],
                'ds_withdrawal' => $request['ds_withdrawal']
            ]
        ], ['no_user' => Auth::id()]);

        return response()->json([
            'result' => true
        ]);
    }

    public function checkRegist(Request $request): JsonResponse
    {
        $request->validate([
            'ds_phone' => 'required',
            'ds_ci' => 'required'
        ]);

        $member = MemberService::getMember([
            'ds_status' => 'Y',
            'ds_phone' => $request->ds_phone,
            'ds_ci' => $request->ds_ci
        ]);

        return response()->json([
            'result' => empty($member->first()?->memberDetail->ds_access_token_api) === false,
            'access_token' => $member->first()?->memberDetail->ds_access_token_api
        ]);
    }

    public function registCar(Request $request): JsonResponse
    {
        $gasKind = CodeService::getGroupCode('204');
        $request->validate([
            'seq' => 'required|integer',
            'ds_car_number' => 'required',
            'ds_car_color' => 'required',
            'cd_gas_kind' => ['required', Rule::in($gasKind->pluck('no_code')->values())],
        ]);

        Auth::user()->memberCarInfoAll->where('ds_car_number', $request->ds_car_number)->whenNotEmpty(function () {
            throw new OwinException(Code::message('PA143'));
        }, function () use ($request) {
            Auth::user()->memberCarInfoAll->map(function ($car) {
                MemberService::updateMemberCarinfo($car, [
                    'yn_main_car' => 'N'
                ]);
            });

            MemberService::createMemberCarinfo([
                'no_user' => Auth::id(),
                'seq' => $request->seq,
                'ds_car_number' => $request->ds_car_number,
                'ds_car_color' => $request->ds_car_color,
                'ds_car_search' => substr($request->ds_car_number, -4),
                'cd_gas_kind' => $request->cd_gas_kind,
                'yn_main_car' => 'Y'
            ]);
        });

        return response()->json([
            'result' => true
        ]);
    }

    public function mainCar(int $no): JsonResponse
    {
        Auth::user()->memberCarInfoAll->whenNotEmpty(function ($car) use ($no) {
            $car->where('no', $no)->whenEmpty(function () {
                throw new OwinException(Code::message('M1510'));
            });
        })->map(function ($car) use ($no) {
            MemberService::updateMemberCarinfo($car, [
                'yn_main_car' => $car->no == $no ? 'Y' : 'N'
            ]);
        });

        return response()->json([
            'result' => true
        ]);
    }
}
