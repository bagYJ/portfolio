<?php

declare(strict_types=1);

namespace Tests\app\Http\Controllers;

use OpenApi\Attributes as OA;

class Parking extends Controller
{
    #[OA\Post(
        path: '/parking/gets',
        description: '주차장 정보 검색',
        security: [['bearerAuth' => []]],
        requestBody: new OA\RequestBody(
            description: '',
            content: [
                new OA\MediaType(
                    mediaType: 'application/json',
                    schema: new OA\Schema(
                        required: ['radius', 'position'],
                        properties: [
                            new OA\Property(
                                property: 'radius',
                                description: '반경',
                                type: 'float',
                                example: 5.0,
                                nullable: false
                            ),
                            new OA\Property(property: 'position', description: '위경도', properties: [
                                new OA\Property(
                                    property: 'x',
                                    description: '위도',
                                    type: 'float',
                                    example: 37.56247988835981,
                                    nullable: false
                                ),
                                new OA\Property(
                                    property: 'y',
                                    description: '경도',
                                    type: 'float',
                                    example: 126.9714889516946,
                                    nullable: false
                                )
                            ], type: 'object')
                        ]
                    )
                )
            ]
        ),
        tags: ['parking'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'success',
                content: new OA\JsonContent(
                    ref: '#/components/schemas/ParkingSiteGets'
                )
            ),
            new OA\Response(response: 500, description: 'api failed')
        ]
    )]
    public function gets_test()
    {
    }

    #[OA\Get(
        path: '/parking/get/{noSite}',
        description: '주차장 정보 단일 검색',
        security: [['bearerAuth' => []]],
        tags: ['parking'],
        parameters: [
            new OA\Parameter(
                name: 'noSite', description: '주차장 uid', in: 'path', required: true, schema: new OA\Schema(
                type: 'string', example: 1
            )
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'success',
                content: new OA\JsonContent(
                    ref: '#/components/schemas/ParkingSite'
                )
            ),
            new OA\Response(response: 500, description: 'api failed')
        ]
    )]
    public function get_test()
    {
    }

    #[OA\Post(
        path: '/parking/order_ticket',
        description: '주차 웹 할인권 구매',
        security: [['bearerAuth' => []]],
        requestBody: new OA\RequestBody(
            description: '',
            content: [
                new OA\MediaType(
                    mediaType: 'application/json',
                    schema: new OA\Schema(ref: '#/components/schemas/ParkingPaymentRequest'),
                )
            ]
        ),
        tags: ['parking'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'success',
                content: new OA\JsonContent(
                    ref: '#/components/schemas/OrderResult'
                )
            ),
            new OA\Response(response: 500, description: 'api failed')
        ]
    )]
    public function orderTicket_test()
    {
    }

    #[OA\Get(
        path: '/parking/get_my_tickets',
        description: '나의 주문정보 조회',
        security: [['bearerAuth' => []]],
        tags: ['parking'],
        parameters: [
            new OA\Parameter(
                name: 'size', description: '조회개수', in: 'query', schema: new OA\Schema(
                type: 'int',
                example: 1
            )
            ),
            new OA\Parameter(
                name: 'offset', description: 'offset', in: 'query', schema: new OA\Schema(
                type: 'int',
                example: 0
            )
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'success',
                content: new OA\JsonContent(
                    ref: '#/components/schemas/ParkingSite'
                )
            ),
            new OA\Response(response: 500, description: 'api failed')
        ]
    )]
    public function getMyTickets_gets()
    {
    }

    #[OA\Get(
        path: '/parking/get_ticket',
        description: '나의 주문정보 조회',
        security: [['bearerAuth' => []]],
        tags: ['parking'],
        parameters: [
            new OA\Parameter(
                name: 'no_order', description: '주문번호', in: 'query', schema: new OA\Schema(
                type: 'string',
                example: 1
            )
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'success',
                content: new OA\JsonContent(
                    ref: '#/components/schemas/ParkingOrder'
                )
            ),
            new OA\Response(response: 500, description: 'api failed')
        ]
    )]
    public function getTicket_test()
    {
    }

    #[OA\Post(
        path: '/parking/cancel_ticket',
        description: '웹 할인권 결제 취소',
        security: [['bearerAuth' => []]],
        requestBody: new OA\RequestBody(
            description: '',
            content: [
                new OA\MediaType(
                    mediaType: 'application/x-www-form-urlencoded',
                    schema: new OA\Schema(
                        required: ['no_order'],
                        properties: [
                            new OA\Property(property: 'no_order', description: '주문번호', type: 'string', example: 1),
                        ]
                    )
                )
            ]
        ),
        tags: ['parking'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'success',
                content: new OA\JsonContent(
                    ref: '#/components/schemas/CancelResult'
                )
            ),
            new OA\Response(response: 500, description: 'api failed')
        ]
    )]
    public function cancelTicket_test()
    {
    }

    #[OA\Get(
        path: '/parking/intro',
        description: '주차 주문 정보 조회',
        security: [['bearerAuth' => []]],
        tags: ['parking'],
        parameters: [
            new OA\Parameter(
                name: 'no_site', description: '주차장 uid', in: 'query', required: true, schema: new OA\Schema(
                type: 'string', example: 1
            )
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'success',
                content: new OA\JsonContent(
                    ref: '#/components/schemas/ParkingIntroResponse'
                )
            ),
            new OA\Response(response: 500, description: 'api failed')
        ]
    )]
    public function intro_test()
    {
    }
}
