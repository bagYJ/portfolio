<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\EnumYN;
use App\Exceptions\OwinException;
use App\Services\MemberService;
use App\Services\OAuthService;
use App\Services\OrderService;
use App\Utils\Code;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response as HttpResponse;
use Throwable;

class Oauth extends Controller
{
    /**
     * 인증번호 발급
     *
     * @return JsonResponse
     * @throws Throwable
     */
    public function registCode(): JsonResponse
    {
        $oauthCode = (new OAuthService())->getRegistCode();

        return response()->json([
            'result' => true,
            'oauth_code' => $oauthCode
        ]);
    }

    /**
     * 회원인증
     *
     * @param Request $request
     * @return JsonResponse
     * @throws OwinException|Throwable
     */
    public function authorization(Request $request): JsonResponse
    {
        $request->validate([
            'oauth_code' => 'required|digits:6',
            'no_vin' => 'required'
        ]);

        return response()->json([
            'result' => true,
            'access_token' => OauthService::checkAuthCode($request->oauth_code, $request->no_vin, $request->ip())
        ]);
    }

    /**
     * 앱 토큰 발급 (제한 15일, 만료일이 지나면 새로 발급 or refreshToken 사용하여 재발급)
     *
     * @param Request $request
     * @return HttpResponse
     * @throws OwinException
     */
    public function token(Request $request): HttpResponse
    {
        $request->validate([
            'ds_udid' => 'required',
            'id_user' => 'required|email:rfc,dns',
            'ds_passwd' => 'required'
        ]);

        // 회원 인증시 비밀번호 암호화 다시 세팅해야됨 (bcrypt 사용)
        $credential = [
            'id_user' => $request->post('id_user'),
            'password' => md5($request->post('ds_passwd')),
        ];

        if (Auth::attempt($credential) === false) {
            throw new OwinException(Code::message('M1305'));
        }
        if (Auth::user()->ds_status != EnumYN::Y->name) {
            throw new OwinException(Code::message('M1302'));
        }

        Auth::user()->update(['yn_push_msg_mobile' => $request->get('yn_push_msg_mobile')]);

        Auth::user()->memberDetail->update([
            'ds_phone_nation' => $request->post('ds_phone_nation'),
            'ds_last_login_ip' => $request->ip(),
            'dt_last_login' => now(),
            'ds_udid' => $request->post('ds_udid')
        ]);

        return (new OAuthService())->token(Auth::user(), $credential);
    }

    /**
     * 토큰 재발급
     *
     * @param Request $request
     * @return HttpResponse
     */
    public function refreshToken(Request $request): HttpResponse
    {
        return (new OAuthService())->refreshToken($request->header('refresh-token'));
    }

    /**
     * 발급한 전체 토큰 삭제
     *
     * @return JsonResponse
     */
    public function deleteTokens(): JsonResponse
    {
        Auth::user()->oauthAccessTokens()->delete();

        return response()->json([
            'result' => true
        ]);
    }

    public function accessCheck(): JsonResponse
    {
        return response()->json([
            'result' => true,
            ...OAuthService::rkmMember(Auth::user())
        ]);
    }

    public function accessDisconnect(): JsonResponse
    {
        (new OrderService())->orderingByExternal(Auth::id())->whenNotEmpty(function () {
            throw new OwinException(Code::message('P2400'));
        });

        MemberService::updateMember([
            'cd_mem_level' => '104100'
        ], [
            'no_user' => Auth::id()
        ]);
        MemberService::updateMemberDetail([
            'cd_third_party' => '110000',
            'yn_account_status_rsm' => 'N',
            'ds_access_token_rsm' => null,
            'ds_access_vin_rsm' => null,
            'dt_account_reg_rsm' => null
        ], [
            'no_user' => Auth::id()
        ]);

        return response()->json([
            'result' => true
        ]);
    }
}
