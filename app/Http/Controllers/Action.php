<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\EnumYN;
use App\Services\ActionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Throwable;

class Action extends Controller
{
    /**
     * @param string $article
     * @return JsonResponse
     * @throws ValidationException
     */
    public function uptimeCheck(string $article): JsonResponse
    {
        Validator::make(['article' => $article], [
            'article' => [
                'string',
                Rule::in(['S', 'O'])
            ]
        ])->validate();

        $response = (new ActionService())->uptimeCheck((int)now()->format('Hi'), $article);

        return response()->json([
            'result' => true,
            'current_msg' => $response['message'],
            'cd_uptime_msg' => $response['code']
        ]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws Throwable
     */
    public function locationSave(Request $request): JsonResponse
    {
        $request->validate([
            'no_order' => 'required|numeric',
            'user_lat' => 'string',
            'user_lng' => 'string',
            'yn_inside' => Rule::in(array_column(EnumYN::cases(), 'name')),
            'ds_addr' => 'string'
        ]);

        (new ActionService())->locationSave(Auth::id(), $request->all());

        return response()->json([
            'result' => true,
        ]);
    }

    public function cacheClear(string $cacheKey): JsonResponse
    {
        return response()->json([
            'result' => (new ActionService())->cacheClear($cacheKey)
        ]);
    }
}
