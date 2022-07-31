<?php

namespace Tests\app\Http\Controllers;

use OpenApi\Attributes as OA;

class OrderOil extends Controller
{
    #[OA\Get(
        path: '/order_oil/intro',
        description: '주유 주문 요청정보',
        security: [['bearerAuth' => []]],
        tags: ['order_oil'],
        parameters: [
            new OA\Parameter(
                name: 'no_shop',
                in: 'query',
                required: true,
                schema: new OA\Schema(
                    type: 'string',
                )
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'success',
                content: new OA\JsonContent(ref: '#/components/schemas/OrderOilIntro')
            ),
            new OA\Response(response: 500, description: 'api failed')
        ]
    )]
    public function intro_test()
    {
    }

    #[OA\Post(
        path: '/order_oil/payment',
        description: '결제 요청',
        security: [['bearerAuth' => []]],
        tags: ['order_oil'],
        requestBody: new OA\RequestBody(
            description: '',
            content: [
                new OA\MediaType(
                    mediaType: 'application/json',
                    schema: new OA\Schema(ref: '#/components/schemas/OrderOilPaymentRequest')
                )
            ]
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'success',
                content: new OA\JsonContent(ref: '#/components/schemas/OrderOilPayment')
            ),
            new OA\Response(response: 500, description: 'api failed')
        ]
    )]
    public function payment_test()
    {
    }

    #[OA\Get(
        path: '/order_oil/detail/{no_order}',
        description: '주문 상세',
        security: [['bearerAuth' => []]],
        tags: ['order_oil'],
        parameters: [
            new OA\Parameter(
                name: 'no_order',
                in: 'path',
                required: true,
                schema: new OA\Schema(
                    type: 'string',
                )
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'success',
                content: new OA\JsonContent(ref: '#/components/schemas/OrderOilDetail')
            ),
            new OA\Response(response: 500, description: 'api failed')
        ]
    )]
    public function detail_test()
    {
    }

    #[OA\Put(
        path: '/order_oil/cancel/{no_order}',
        description: '결제 취소',
        security: [['bearerAuth' => []]],
        tags: ['order_oil'],
        parameters: [
            new OA\Parameter(
                name: 'no_order',
                in: 'path',
                required: true,
                schema: new OA\Schema(
                    type: 'string',
                )
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'success',
                content: new OA\JsonContent(ref: '#/components/schemas/OrderOilCancel')
            ),
            new OA\Response(response: 500, description: 'api failed')
        ]
    )]
    public function cancel_test()
    {
    }

//    #[OA\Get(
//        path: '/order_oil/check/{no_order}',
//        description: '주문확인',
//        security: [['bearerAuth' => []]],
//        tags: ['order_oil'],
//        parameters: [
//            new OA\Parameter(
//                name: 'no_order',
//                in: 'path',
//                required: true,
//                schema: new OA\Schema(
//                    type: 'string',
//                )
//            ),
//        ],
//        responses: [
//        new OA\Response(
//            response: 200,
//            description: 'success',
//            content: new OA\JsonContent(ref: '#/components/schemas/OrderOilCheck')
//        ),
//        new OA\Response(
//            response: 404,
//            description: '도착내역 없음',
//        ),
//        new OA\Response(response: 500, description: 'api failed')
//    ]
//    )]
//    public function check_test()
//    {
//    }

    #[OA\Get(
        path: '/order_oil/oil_dp_list/{noOrder}',
        description: '클라이언트에서 150m 이내일 경우에 dp 리스트 조회',
        security: [['bearerAuth' => []]],
        tags: ['order_oil'],
        parameters: [
            new OA\Parameter(
                name: 'no_order',
                in: 'path',
                required: true,
                schema: new OA\Schema(
                    type: 'string',
                )
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'success',
                content: new OA\JsonContent(ref: '#/components/schemas/OrderOilDpListResponse')
            ),
            new OA\Response(response: 500, description: 'api failed')
        ]
    )]
    public function oilDpList_test()
    {
    }

//    #[OA\Post(
//        path: '/order_oil/preset_check',
//        description: '주유현재 진행상황 전달',
//        security: [['bearerAuth' => []]],
//        tags: ['order_oil'],
//        parameters: [
//            new OA\Parameter(
//                name: 'no_order',
//                in: 'query',
//                required: true,
//                schema: new OA\Schema(
//                    type: 'string',
//                )
//            ),
//        ],
//        responses: [
//        new OA\Response(
//            response: 200,
//            description: 'success',
//            content: new OA\JsonContent(ref: '#/components/schemas/OrderOilPresetCheck')
//        ),
//        new OA\Response(response: 500, description: 'api failed')
//    ]
//    )]
//    public function presetCheck_test()
//    {
//    }

    #[OA\Post(
        path: '/order_oil/qr_regist',
        description: 'APP내 리더기로 QR리드후 파라미터정보를 서버로 전달',
        security: [['bearerAuth' => []]],
        requestBody: new OA\RequestBody(
            description: '',
            content: [
                new OA\MediaType(
                    mediaType: 'application/x-www-form-urlencoded',
                    schema: new OA\Schema(
                        required: ['no_order', 'ds_display_ark_id'],
                        properties: [
                            new OA\Property(property: 'no_order', description: '주문번호', type: 'string'),
                            new OA\Property(property: 'ds_display_ark_id', description: 'ark id', type: 'string'),
                        ]
                    )
                )
            ]
        ),
        tags: ['order_oil'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'success',
                content: new OA\JsonContent(ref: '#/components/schemas/OrderOilPresetCheck')
            ),
            new OA\Response(response: 500, description: 'api failed')
        ]
    )]
    public function qrRegist_test()
    {
    }

    #[OA\Get(
        path: '/order_oil/order_process/{no_order}',
        description: '주문 진행상황 확인',
        security: [['bearerAuth' => []]],
        tags: ['order_oil'],
        parameters: [
            new OA\Parameter(
                name: 'no_order',
                in: 'path',
                required: true,
                schema: new OA\Schema(
                    type: 'string',
                )
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'success',
                content: new OA\JsonContent(ref: '#/components/schemas/OrderProcessResponse')
            ),
            new OA\Response(response: 500, description: 'api failed')
        ]
    )]
    public function orderProcess_test()
    {
    }

    #[OA\Post(
        path: '/order_oil/preset_check',
        description: '주유 프리셋 준비조회',
        security: [['bearerAuth' => []]],
        requestBody: new OA\RequestBody(
            description: '',
            content: [
                new OA\MediaType(
                    mediaType: 'application/x-www-form-urlencoded',
                    schema: new OA\Schema(
                        required: ['no_order'],
                        properties: [
                            new OA\Property(property: 'no_order', description: '주문번호', type: 'string')
                        ]
                    )
                )
            ]
        ),
        tags: ['order_oil'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'success',
                content: new OA\JsonContent(ref: '#/components/schemas/OrderOilPresetCheck')
            ),
            new OA\Response(response: 500, description: 'api failed')
        ]
    )]
    public function presetCheck_test()
    {
    }
}
