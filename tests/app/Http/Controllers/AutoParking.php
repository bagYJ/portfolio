<?php

namespace Tests\app\Http\Controllers;

use OpenApi\Attributes as OA;

class AutoParking extends Controller
{
    #[OA\Get(
        path: '/auto_parking/check_payment',
        description: '미결제내역 체크',
        security: [['bearerAuth' => []]],
        tags: ['auto_parking'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'success',
                content: new OA\JsonContent(ref: '#/components/schemas/AutoParkingCheckPaymentResponse')
            ),
            new OA\Response(response: 500, description: 'api failed')
        ]
    )]
    public function checkPayment_test()
    {
    }

    #[OA\Get(
        path: '/auto_parking/gets',
        description: '자동결제 차량 리스트 조회',
        security: [['bearerAuth' => []]],
        tags: ['auto_parking'],
        responses: [
        new OA\Response(
            response: 200,
            description: 'success',
            content: new OA\JsonContent(ref: '#/components/schemas/AutoParkingGetsResponse')
        ),
        new OA\Response(response: 500, description: 'api failed')
    ]
    )]
    public function getMyAutoParking_test()
    {
    }

    #[OA\Post(
        path: '/auto_parking/register',
        description: '자동결제 차량 등록/해제',
        security: [['bearerAuth' => []]],
        requestBody: new OA\RequestBody(
            description: '',
            content: [
                new OA\MediaType(
                    mediaType: 'application/json',
                    schema: new OA\Schema(ref: '#/components/schemas/AutoParkingRegistRequest')
                )
            ]
        ),
        tags: ['auto_parking'],
        responses: [
            new OA\Response(
                response: 200, description: 'success', content: new OA\JsonContent(
                ref: '#/components/schemas/AutoParkingRegistResponse'
            )
            ),
            new OA\Response(response: 500, description: 'api failed')
        ]
    )]
    public function registerCar_test()
    {
    }

    #[OA\Post(
        path: '/auto_parking/payment',
        description: '미결제내역 결제',
        security: [['bearerAuth' => []]],
        requestBody: new OA\RequestBody(
            description: '',
            content: [
                new OA\MediaType(
                    mediaType: 'application/json',
                    schema: new OA\Schema(ref: '#/components/schemas/AutoParkingPaymentRequest')
                )
            ]
        ),
        tags: ['auto_parking'],
        responses: [
            new OA\Response(
                response: 200, description: 'success', content: new OA\JsonContent(
                ref: '#/components/schemas/OrderPaymentResponse'
            )
            ),
            new OA\Response(response: 500, description: 'api failed')
        ]
    )]
    public function payment_test()
    {
    }
}
