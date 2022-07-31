<?php

declare(strict_types=1);

namespace App\Http\Controllers\Rkm;

use App\Exceptions\OwinException;
use App\Services\MemberService;
use App\Services\OAuthService;
use App\Utils\Code;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class Oauth extends \App\Http\Controllers\Oauth
{
    public function refreshToken(Request $request): JsonResponse
    {
        $request->validate([
            'ds_phone' => 'required',
            'ds_ci' => 'required',
        ]);

        $memberInfo = MemberService::getMember([
            'ds_phone' => $request->ds_phone,
            'ds_ci' => $request->ds_ci
        ])->whenEmpty(function () {
            throw new OwinException(Code::message('M1501'));
        })->first();

        return response()->json([
            'result' => true,
            'access_token' => OauthService::authorization($memberInfo, $memberInfo->memberDetail->no_vin, null, $request->ip())
        ]);
    }
}
