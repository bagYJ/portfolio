<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\EnumYN;
use App\Exceptions\OwinException;
use App\Models\MemberAuthCodeLog;
use App\Models\MemberDetail;
use App\Models\PersonalAccessToken;
use App\Models\User;
use App\Utils\Code;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

use function app;
use function now;

class OAuthService extends Service
{
    /**
     * @param string $oauthCode
     * @param string $vin
     * @param string|null $ip
     * @return string
     * @throws OwinException
     */
    public static function checkAuthCode(string $oauthCode, string $vin, ?string $ip = null): string
    {
        $oauthInfo = MemberAuthCodeLog::where('oauth_code', $oauthCode)
            ->orderByDesc('dt_reg')->get()->whenEmpty(function () {
                throw new OwinException(Code::message('M1514'));
            })
            ->first();

        $memberInfo = User::with('memberDetail')->where('no_user', '=', $oauthInfo->no_user)->first();

        if ($memberInfo->memberDetail->ds_access_token_rsm && $memberInfo->memberDetail->yn_account_status_rsm == EnumYN::Y->name) {
            throw new OwinException(Code::message('M1512'));
        }
        if (($oauthInfo->yn_auth != EnumYN::Y->name && $oauthInfo->expir_time >= Carbon::now()->addSeconds(-30)) === false) {
            throw new OwinException(Code::message('M1513'));
        }

        return self::authorization($memberInfo->first(), $vin, $oauthInfo, $ip);
    }

    /**
     * 회원인증
     *
     * @param User $memberInfo
     * @param string|null $vin
     * @param MemberAuthCodeLog|null $oauthInfo
     * @param string|null $ip
     * @return string
     */
    public static function authorization(User $memberInfo, ?string $vin, ?MemberAuthCodeLog $oauthInfo = null, ?string $ip = null): string
    {
        $accessToken = MemberService::createAccessToken($memberInfo->no_user);
        DB::transaction(function () use ($accessToken, $memberInfo, $vin, $ip, $oauthInfo) {
//            PersonalAccessTokens::where('tokenable_id', $memberInfo->no_user)->whereNot('token', $accessToken->accessToken->token)->delete();

            MemberDetail::find($memberInfo->no_user)->update([
                'ds_access_token_rsm' => $accessToken->plainTextToken,
                'ds_access_vin_rsm' => $vin,
                'yn_account_status_rsm' => EnumYN::Y->name,
                'cd_third_party' => getAppType()->value,
                'ds_last_login_ip' => $ip,
                'ds_last_login' => now(),
                'dt_account_reg_rsm' => now(),
            ]);
            $memberInfo->update([
                'cd_mem_level' => getMemberLevel()->value
            ]);

            //인증코드 발급 로우를 실제로는 업데이트시키는데 사용할 이유가 따로 없어 제거하는 방식으로 진행할 예정
            $oauthInfo?->delete();
        });

        return $accessToken->plainTextToken;
    }

    public function token(User $user, $credential): Response
    {
//        OauthAccessTokens::where('user_id', $user->no_user)->delete();

        $request = Request::create(uri: '/oauth/token', method: 'POST', parameters: [
            'grant_type' => 'password',
            'client_id' => getenv('PASSPORT_PERSONAL_ACCESS_CLIENT_ID'),
            'client_secret' => getenv('PASSPORT_PERSONAL_ACCESS_CLIENT_SECRET'),
            'username' => $user->id_user,
            'id_user' => $user->id_user,
            'password' => $credential['password'],
            'no_user' => $user->no_user,
            'scope' => '*',
        ]);

        return app()->handle($request);
    }

    public function refreshToken(string $refreshToken): Response
    {
        $request = Request::create(uri: '/oauth/token', method: 'POST', parameters: [
            'grant_type' => 'refresh_token',
            'refresh_token' => $refreshToken,
            'client_id' => getenv('PASSPORT_PERSONAL_ACCESS_CLIENT_ID'),
            'client_secret' => getenv('PASSPORT_PERSONAL_ACCESS_CLIENT_SECRET'),
            'scope' => '*',
        ]);

        return app()->handle($request);
    }

    /**
     * @return string
     * @throws Exception
     * @throws Throwable
     */
    public function getRegistCode(): string
    {
        $memberDetail = MemberDetail::find(Auth::id())->first();

        if (!$memberDetail->ds_access_vin_rsm && $memberDetail->yn_account_status_rsm == EnumYN::Y->name) {
            $memberDetail->update([
                'yn_account_status_rsm' => EnumYN::N->name,
                'cd_third_party' => '110000',
                'ds_access_token_rsm' => null,
                'ds_access_vin_rsm' => null,
                'dt_account_reg_rsm' => null
            ]);
            $memberDetail->refresh();

            Auth::user()->update([
                'cd_mem_level' => '104100'
            ]);
        }

        Auth::user()->memberCarInfoAll->whenEmpty(function () {
            throw new OwinException(Code::message('M1510'));
        }, function ($list) {
            if (count(
                    $list->map(function ($car) {
                        return [$car->carList?->no_maker];
                    })->flatten()->intersect([1003, 2016])
                ) < 1) {
                throw new OwinException(Code::message('M1519'));
            }
        });

        if ($memberDetail->ds_access_vin_rsm && $memberDetail->yn_account_status_rsm == EnumYN::Y->name) {
            throw new OwinException(Code::message('M1512'));
        }


        $oauthCode = sprintf('%06d', rand(0, 999999));
        (new MemberAuthCodeLog([
            'no_user' => Auth::id(),
            'oauth_code' => $oauthCode,
            'expir_time' => now()->addSeconds(30)
        ]))->saveOrFail();

        return $oauthCode;
    }

    public static function rkmMember(?User $user): array
    {
        return match (
            $user?->cd_mem_level == '104600'
            && $user?->memberDetail->yn_account_status_rsm == 'Y'
            && empty($user?->memberDetail->ds_access_token_rsm) === false
        ) {
            true => [
                'yn_access_status' => 'Y',
                'dt_account_reg_rsm' => $user?->memberDetail->dt_account_reg_rsm,
                'ds_access_vin_rsm' => $user?->memberDetail->ds_access_vin_rsm
            ],
            default => [
                'yn_access_status' => 'N',
                'dt_account_reg_rsm' => null,
                'ds_access_vin_rsm' => null
            ]
        };
    }
}
