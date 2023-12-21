<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Responses\AutoWash\Detail;
use App\Responses\AutoWash\Info;
use App\Responses\AutoWash\Intro;
use App\Utils\Common;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;

class AutoWash extends Controller
{
    private readonly \App\Services\Dev\AutoWash|\App\Services\Production\AutoWash $service;

    public function __construct()
    {
        $this->service = Common::getService('AutoWash');
    }

    /**
     * @param int $noShop
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function info(int $noShop): JsonResponse
    {
        $response = $this->service::info($noShop);
        return response()->json(new Info($response));
    }

    /**
     * @param int $noShop
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function intro(int $noShop): JsonResponse
    {
        $response = $this->service::intro($noShop);
        return response()->json(new Intro($response));
    }

    public function detail(string $noOrder): JsonResponse
    {
        $response = $this->service::detail($noOrder);
        return response()->json(new Detail($response));
    }

    public function payment(): JsonResponse
    {
        return response()->json([]);
    }

    public function complete(string $noOrder): JsonResponse
    {
        return response()->json([]);
    }
}
