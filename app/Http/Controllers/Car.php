<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Services\CarService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class Car extends Controller
{
    /**
     * @return JsonResponse
     */
    public function makerList(): JsonResponse
    {
        return response()->json([
            'result' => true,
            'car_maker_list' => CarService::makerList()
        ]);
    }

    public function kindByCarList(int $noMaker): JsonResponse
    {
        Validator::make(['noMaker' => $noMaker], ['noMaker' => 'required|integer'])->validate();

        return response()->json([
            'result' => true,
            'kind_by_car_list' => CarService::kindByCarList($noMaker)
        ]);
    }
}
