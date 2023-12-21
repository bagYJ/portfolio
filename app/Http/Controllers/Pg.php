<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Requests\Pg\Payment;
use App\Requests\Pg\Refund;
use App\Requests\Pg\Regist;
use App\Utils\Common;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;

class Pg extends Controller
{
    private readonly \App\Services\Dev\Pg|\App\Services\Production\Pg $service;

    public function __construct()
    {
        $this->service = Common::getService('Pg');
    }

    /**
     * @param Regist $request
     * @return JsonResponse
     * @throws Exception
     */
    public function regist(Regist $request): JsonResponse
    {
        $response = $this->service::regist($request);
        return response()->json($response);
    }

    /**
     * @param Payment $request
     * @return JsonResponse
     */
    public function payment(Payment $request): JsonResponse
    {
        $response = $this->service::payment($request);
        return response()->json($response);
    }

    /**
     * @param Refund $request
     * @return JsonResponse
     */
    public function refund(Refund $request): JsonResponse
    {
        $response = $this->service::refund($request);
        return response()->json($response);
    }
}
