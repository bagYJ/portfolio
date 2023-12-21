<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Requests\AutoParking\{CarEnter, CarExit, CheckFee, Payment, Refund, RegistCar};
use App\Responses\AutoParking\{CarEnter as CarEnterResponse,
    CarExit as CarExitResponse,
    CarExitFailed as CarExitFailedResponse,
    CheckFee as CheckFeeResponse,
    Failed,
    ParkingList as ParkingListResponse,
    Payment as PaymentResponse,
    Refund as RefundResponse,
    RegistCar as RegistCarResponse};
use App\Utils\Common;
use Illuminate\{Auth\Access\AuthorizationException, Http\JsonResponse};
use OpenApi\Attributes as OA;
use Owin\OwinCommonUtil\CodeUtil;
use Throwable;

#[OA\Tag(name: 'auto-parking', description: '자동주차')]
class AutoParking extends Controller
{
    private readonly \App\Services\Dev\AutoParking|\App\Services\Production\AutoParking $service;
    private readonly \App\Services\Dev\Push|\App\Services\Production\Push $push;

    public function __construct()
    {
        $this->service = Common::getService('AutoParking');
        $this->push = Common::getService('Push');
    }

    /**
     * @return JsonResponse
     * @throws AuthorizationException
     */
    #[OA\Get(
        path: '/auto-parking',
        summary: '제휴 주차장 목록 조회',
        tags: ['auto-parking'],
        responses: [
            new OA\Response(response: 200, description: 'Success', content: new OA\JsonContent(ref: ParkingListResponse::class)),
            new OA\Response(ref: '#/components/responses/Exception', response: 'default')
        ]
    )]
    public function parkingList(): JsonResponse
    {
        $response = $this->service::parkingList();
        return response()->json(new ParkingListResponse($response));
    }

    /**
     * @param RegistCar $request
     * @return JsonResponse
     * @throws AuthorizationException
     */
    #[OA\Post(
        path: '/auto-parking/regist-car',
        summary: '자동 결제 차량 등록/해제',
        requestBody: new OA\RequestBody(content: new OA\MediaType(mediaType: 'application/json', schema: new OA\Schema(RegistCar::class))),
        tags: ['auto-parking'],
        responses: [
            new OA\Response(response: 200, description: 'Success', content: new OA\JsonContent(ref: RegistCarResponse::class)),
            new OA\Response(ref: '#/components/responses/Exception', response: 'default')
        ]
    )]
    public function registCar(RegistCar $request): JsonResponse
    {
        $response = $this->service::registCar($request);
        return response()->json(new RegistCarResponse($response));
    }

    /**
     * @param CheckFee $request
     * @return JsonResponse
     * @throws AuthorizationException
     */
    #[OA\Post(
        path: '/auto-parking/check-fee',
        summary: '주차 비용 조회 요청',
        requestBody: new OA\RequestBody(content: new OA\MediaType(mediaType: 'application/json', schema: new OA\Schema(CheckFee::class))),
        tags: ['auto-parking'],
        responses: [
            new OA\Response(response: 200, description: 'Success', content: new OA\JsonContent(ref: CheckFeeResponse::class)),
            new OA\Response(ref: '#/components/responses/Exception', response: 'default')
        ]
    )]
    public function checkFee(CheckFee $request): JsonResponse
    {
        $response = $this->service::checkFee($request);
        return response()->json(new CheckFeeResponse($response));
    }

    /**
     * @param Payment $request
     * @return JsonResponse
     * @throws AuthorizationException
     */
    #[OA\Post(
        path: '/auto-parking/payment',
        summary: '결제 완료 정보 전달',
        requestBody: new OA\RequestBody(content: new OA\MediaType(mediaType: 'application/json', schema: new OA\Schema(Payment::class))),
        tags: ['auto-parking'],
        responses: [
            new OA\Response(response: 200, description: 'Success', content: new OA\JsonContent(ref: PaymentResponse::class)),
            new OA\Response(ref: '#/components/responses/Exception', response: 'default')
        ]
    )]
    public function payment(Payment $request): JsonResponse
    {
        $response = $this->service::payment($request);
        return response()->json(new PaymentResponse($response));
    }

    /**
     * @param Refund $request
     * @return JsonResponse
     * @throws AuthorizationException
     */
    #[OA\Post(
        path: '/auto-parking/refund',
        summary: '결제 승인 취소 후 정보 전달',
        requestBody: new OA\RequestBody(content: new OA\MediaType(mediaType: 'application/json', schema: new OA\Schema(Refund::class))),
        tags: ['auto-parking'],
        responses: [
            new OA\Response(response: 200, description: 'Success', content: new OA\JsonContent(ref: RefundResponse::class)),
            new OA\Response(ref: '#/components/responses/Exception', response: 'default')
        ]
    )]
    public function refund(Refund $request): JsonResponse
    {
        $response = $this->service::refund($request);
        return response()->json(new RefundResponse($response));
    }

    /**
     * @param CarEnter $request
     * @return JsonResponse
     */
    #[OA\Post(
        path: '/auto-parking/enter',
        summary: '입차 차량 정보 전달',
        requestBody: new OA\RequestBody(content: new OA\MediaType(mediaType: 'application/json', schema: new OA\Schema(CarEnter::class))),
        tags: ['auto-parking'],
        responses: [
            new OA\Response(response: 200, description: 'Success', content: new OA\JsonContent(ref: CarEnterResponse::class)),
            new OA\Response(ref: '#/components/responses/Exception', response: 'default')
        ]
    )]
    public function enter(CarEnter $request): JsonResponse
    {
        try {
            $response = $this->service::enter($request);
            if (data_get($response, 'resultCode') == env('RETURN_TRUE')) {
                $this->push::send(
                    data_get($response, 'order'),
                    sprintf('%s%s', env(sprintf('API_URI_%s', strtoupper(CodeUtil::getServiceSchemaEnumFromOrderCode(data_get($response, 'txId'))->value))), env('API_PATH_PUSH')),
                    'PARKING',
                    'enter'
                );
            }

            return response()->json(new CarEnterResponse($response));
        } catch (Throwable $throwable) {
            return response()->json(new Failed($throwable));
        }
    }

    /**
     * @param CarExit $request
     * @return JsonResponse
     */
    #[OA\Post(
        path: '/auto-parking/exit',
        summary: '출차 차량 정보 전달',
        requestBody: new OA\RequestBody(content: new OA\MediaType(mediaType: 'application/json', schema: new OA\Schema(CarExit::class))),
        tags: ['auto-parking'],
        responses: [
            new OA\Response(response: 200, description: 'Success', content: new OA\JsonContent(ref: CarEnterResponse::class)),
            new OA\Response(ref: '#/components/responses/Exception', response: 'default')
        ]
    )]
    public function exit(CarExit $request): JsonResponse
    {
        try {
            $response = $this->service::exit($request);
            if (data_get($response, 'code') == env('RETURN_TRUE')) {
                $this->push::send(
                    data_get($response, 'order'),
                    sprintf('%s%s', env(sprintf('API_URI_%s', strtoupper(CodeUtil::getServiceSchemaEnumFromOrderCode(data_get($response, 'txId'))->value))), env('API_PATH_PUSH')),
                    'PARKING',
                    'complete'
                );
            }

            return response()->json(new CarExitResponse($response));
        } catch (Throwable $throwable) {
            return response()->json(new CarExitFailedResponse($throwable));
        }
    }
}
