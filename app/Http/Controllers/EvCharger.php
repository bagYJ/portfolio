<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Services\EvChargerService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class EvCharger extends Controller
{
    public function filter(): JsonResponse
    {
        return response()->json([
            'result' => true,
            'filter' => EvChargerService::getFilter()
        ]);
    }

    public function info(string $idStat): JsonResponse
    {
        Validator::make(['idStat' => $idStat], ['idStat' => 'required'])->validate();

        return response()->json([
            'result' => true,
            'item' => EvChargerService::getInfo($idStat)
        ]);
    }
}
