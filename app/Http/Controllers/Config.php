<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\BeaconLevel;
use App\Services\MemberService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Config extends Controller
{
    public function profile(): JsonResponse
    {
        return response()->json([
            'id_user' => Auth::user()->id_user,
            'nm_user' => Auth::user()->nm_user,
            'nm_nick' => Auth::user()->nm_nick,
            'cd_reg_kind' => Auth::user()->cd_reg_kind,
            'ds_profile_path' => Auth::user()->memberDetail->ds_profile_path,
            'ds_user_level' => match (count(Auth::user()->beaconCount)) {
                0 => BeaconLevel::DEF->value,
                default => BeaconLevel::BEACON->value
            }
        ]);
    }

    public function profileEdit(Request $request): JsonResponse
    {
        $request->validate([
            'nm_change_nick' => 'between:2,12'
        ]);

        (new MemberService())->profileEdit($request);

        return response()->json([
            'result' => true
        ]);
    }

    public function pushmessage()
    {
    }
}
