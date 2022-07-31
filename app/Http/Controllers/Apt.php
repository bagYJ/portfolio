<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Exceptions\OwinException;
use App\Services\AptService;
use App\Utils\Code;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class Apt extends Controller
{
    public function getMemberAptList(): JsonResponse
    {
        return response()->json([
            'result' => true,
            'apt_list' => AptService::list(Auth::id())->first()
        ]);
    }

    public function register(string $idApt): JsonResponse
    {
        AptService::register($idApt, Auth::id());

        return response()->json(
            ['result' => true]
        );
    }

    public function remove(string $idApt): JsonResponse
    {
        AptService::deleteApt([
            'no_user' => Auth::id(),
            'id_apt' => AptService::list(Auth::id())->where('id_apt', $idApt)
                ->whenEmpty(function () {
                    throw new OwinException(Code::message('B3080'));
                })->first()
        ]);

        return response()->json([
            'result' => true
        ]);
    }

    public function list(): JsonResponse
    {
        return response()->json([
            'result' => true,
            'apt_list' => AptService::aptList()
        ]);
    }
}
