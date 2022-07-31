<?php

declare(strict_types=1);

namespace Tests\app\Http\Controllers;

use OpenApi\Attributes as OA;

class Order extends Controller
{
    #[OA\Post(
        path: '/order/init',
        description: '주문 시작',
        security: [['bearerAuth' => []]],
        requestBody: new OA\RequestBody(
            description: '',
            content: [
                new OA\MediaType(
                    mediaType: 'application/json',
                    schema: new OA\Schema(ref: '#/components/schemas/OrderInitRequest')
                )
            ]
        ),
        tags: ['order'],
        responses: [
            new OA\Response(
                response: 200, description: 'success', content: new OA\JsonContent(
                ref: '#/components/schemas/OrderInitResponse'
            )
            ),
            new OA\Response(response: 500, description: 'api failed')
        ]
    )]
    public function init_test()
    {
    }

    #[OA\Post(
        path: '/order/payment',
        description: '주문 결제',
        security: [['bearerAuth' => []]],
        requestBody: new OA\RequestBody(
            description: '',
            content: [
                new OA\MediaType(
                    mediaType: 'application/json',
                    schema: new OA\Schema(ref: '#/components/schemas/OrderPaymentRequest')
                )
            ]
        ),
        tags: ['order'],
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

    #[OA\Get(
        path: '/order/order_status_history/{noOrder}',
        description: '주문 상태 변경 시각',
        security: [['bearerAuth' => []]],
        tags: ['order'],
        parameters: [
            new OA\Parameter(name: 'noOrder', description: '주문번호', in: 'path', schema: new OA\Schema(type: 'string')),
        ],
        responses: [
            new OA\Response(
                response: 200, description: 'success', content: new OA\JsonContent(
                ref: '#/components/schemas/OrderStatusHistoryResponse'
            )
            ),
            new OA\Response(response: 500, description: 'api failed')
        ]
    )]
    public function orderStatusHistory_test()
    {
    }

    #[OA\Put(
        path: '/order/gps_alarm',
        description: '주문 알람',
        security: [['bearerAuth' => []]],
        requestBody: new OA\RequestBody(
            description: '',
            content: [
                new OA\MediaType(
                    mediaType: 'application/x-www-form-urlencoded',
                    schema: new OA\Schema(
                        required: ['no_shop', 'no_order', 'cd_alarm_event_type'],
                        properties: [
                            new OA\Property(property: 'no_shop', description: '상점번호', type: 'integer'),
                            new OA\Property(property: 'no_order', description: '주문번호', type: 'string'),
                            new OA\Property(
                                property: 'cd_alarm_event_type',
                                description: '알람타입 (607100: 1차알림, 607200: 	2차알림, 607300: 	매장도착, 607350: 점원호출)',
                                type: 'string',
                                enum: ['607100', '607200', '607300', '607350'],
                            ),
                            new OA\Property(property: 'at_lat', description: '위도', type: 'float'),
                            new OA\Property(property: 'at_lng', description: '경도', type: 'float'),
                            new OA\Property(property: 'at_distance', description: '거리', type: 'integer'),
                            new OA\Property(
                                property: 'at_yn_gps_statuslng',
                                description: 'GPS 활성화 상태',
                                type: 'string',
                                enum: ['Y', 'N']
                            )
                        ]
                    )
                )
            ]
        ),
        tags: ['order'],
        responses: [
            new OA\Response(
                response: 200, description: 'success', content: new OA\JsonContent(properties: [
                new OA\Property(property: 'result', description: '성공 여부', type: 'bool')
            ], type: 'object')
            ),
            new OA\Response(response: 500, description: 'api failed')
        ]
    )]
    public function gpsAlarm_test()
    {
    }

    #[OA\Get(
        path: '/order/list',
        description: '회원 주문 목록',
        security: [['bearerAuth' => []]],
        tags: ['order'],
        parameters: [
            new OA\Parameter(name: 'size', description: '페이지 당 항목 개수', in: 'query', schema: new OA\Schema(type: 'integer',)),
            new OA\Parameter(name: 'offset', description: '페이지 offset', in: 'query', schema: new OA\Schema(type: 'integer',)),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'success',
                content: new OA\JsonContent(
                    ref: '#/components/schemas/MemberOrderListResponse'
                )
            ),
            new OA\Response(response: 500, description: 'api failed')
        ]
    )]
    public function getOrderList_test()
    {
    }

    #[OA\Get(
        path: '/order/list/{bizKind}',
        description: '회원 주문 목록 (업종구분)',
        security: [['bearerAuth' => []]],
        tags: ['order'],
        parameters: [
            new OA\Parameter(name: 'bizKind', description: '업종구분', in: 'path', required: true, schema: new OA\Schema(type: 'string', enum: ['FNB', 'OIL', 'RETAIL', 'WASH', 'PARKING'])),
            new OA\Parameter(name: 'size', description: '페이지 당 항목 개수', in: 'query', schema: new OA\Schema(type: 'integer',)),
            new OA\Parameter(name: 'offset', description: '페이지 offset', in: 'query', schema: new OA\Schema(type: 'integer',)),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'success',
                content: new OA\JsonContent(
                    ref: '#/components/schemas/MemberOrderListResponse'
                )
            ),
            new OA\Response(response: 500, description: 'api failed')
        ]
    )]
    public function getOrderListByBizKind_test()
    {
    }

    #[OA\Get(
        path: '/order/detail/{bizKind}/{noOrder}',
        description: '주문상세',
        security: [['bearerAuth' => []]],
        tags: ['order'],
        parameters: [
            new OA\Parameter(name: 'bizKind', description: '업종구분', in: 'path', required: true, schema: new OA\Schema(type: 'string', enum: ['FNB', 'OIL', 'RETAIL', 'WASH', 'PARKING'])),
            new OA\Parameter(name: 'noOrder', description: '주문번호', in: 'path', required: true, schema: new OA\Schema(type: 'string')),
        ],
        responses: [
            new OA\Response(
                response: 200, description: 'success', content: new OA\JsonContent(
                ref: '#/components/schemas/OrderDetailResponse'
            )
            ),
            new OA\Response(response: 500, description: 'api failed')
        ]
    )]
    public function detail_test()
    {
    }

    #[OA\Get(
        path: '/order/incomplete/{bizKind}/{noOrder}',
        description: '미결제 주문내역 확인',
        security: [['bearerAuth' => []]],
        tags: ['order'],
        parameters: [
            new OA\Parameter(name: 'bizKind', description: '업종구분', in: 'path', required: true, schema: new OA\Schema(type: 'string', enum: ['FNB', 'OIL', 'RETAIL', 'WASH', 'PARKING'])),
            new OA\Parameter(name: 'noOrder', description: '주문번호', in: 'path', required: true, schema: new OA\Schema(type: 'string')),
        ],
        responses: [
            new OA\Response(
                response: 200, description: 'success', content: new OA\JsonContent(
                ref: '#/components/schemas/OrderIncompleteResponse'
            )
            ),
            new OA\Response(response: 500, description: 'api failed')
        ]
    )]
    public function incomplete_test()
    {
    }
}
