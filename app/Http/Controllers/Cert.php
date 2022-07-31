<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\CertAgency;
use App\Enums\CertNation;
use App\Enums\Sex;
use App\Exceptions\OwinException;
use App\Services\CertService;
use App\Utils\Code;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class Cert extends Controller
{
    public function request(Request $request): JsonResponse
    {
        $request->validate([
            'pm_name' => 'required|',
            'pm_birth' => 'required|digits:8',
            'pm_agency' => ['required', Rule::in(CertAgency::keys())],
            'pm_phone' => 'required|digits_between:10,11',
            'pm_nation' => ['required', Rule::in(CertNation::keys())],
            'pm_sex' => ['required', Rule::in(Sex::keys())],
        ]);

        $response = CertService::request($request->all());
        if ($response[8] != 'Y' || $response[9] != 'KISQ0000') {
            throw new OwinException(Code::message('M1130'));
        }

        return response()->json([
            'result' => true,
            'no_auth_seq' => $response[0]
        ]);
    }

    public function retry(Request $request): JsonResponse
    {
        $request->validate([
            'no_auth_seq' => 'required|integer'
        ]);

        CertService::getMemberOwnAuthlog([
            'no_auth_seq' => $request->no_auth_seq
        ])->whenEmpty(function () {
            throw new OwinException(Code::message('M1132'));
        })->first(function ($certLog) {
            CertService::retry($certLog);
        });

        return response()->json([
            'result' => true,
            'no_auth_seq' => $request->no_auth_seq
        ]);
    }

    public function complete(Request $request): JsonResponse
    {
        $request->validate([
            'no_auth_seq' => 'required|integer',
            'sms_num' => 'required|digits:6'
        ]);

        $certLog = CertService::getMemberOwnAuthlog([
            'no_auth_seq' => $request->no_auth_seq
        ])->whenEmpty(function () {
            throw new OwinException(Code::message('M1132'));
        })->first();
        $member = CertService::complete($request->sms_num, $certLog)->first();

        return response()->json([
            'result' => true,
            'joined' => empty($member?->no_user) == false,
            'id_user' => $member?->id_user,
            'no_user' => $member?->no_user
        ]);
    }
}
