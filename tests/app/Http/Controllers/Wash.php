<?php

namespace Tests\app\Http\Controllers;

use OpenApi\Attributes as OA;

class Wash extends Controller
{
    #[OA\Get(
        path: '/wash/intro',
        description: '세차 주문정보 요청',
        security: [['bearerAuth' => []]],
        tags: ['wash'],
        parameters: [
            new OA\Parameter(
                name: 'no_shop',
                in: 'query',
                required: true,
                schema: new OA\Schema(
                    type: 'string',
                )
            ),
        ], responses: [
        new OA\Response(
            response: 200,
            description: 'success',
            content: new OA\JsonContent(ref: '#/components/schemas/WashIntroResponse')
        ),
        new OA\Response(response: 500, description: 'api failed')
    ]
    )]
    public function intro_test()
    {
    }

    #[OA\Post(
        path: '/wash/order_complete',
        description: '세차요청처리 - 결과메세지 전달 (세차직원확인->세차요청으로 변경)',
        security: [['bearerAuth' => []]],
        requestBody: new OA\RequestBody(
            description: '',
            content: [
                new OA\MediaType(
                    mediaType: 'application/x-www-form-urlencoded',
                    schema: new OA\Schema(
                        required: ['no_order'],
                        properties: [
                            new OA\Property(property: 'no_order', description: '주문번호', type: 'string'),
                        ]
                    )
                )
            ]
        ),
        tags: ['wash'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'success',
                content: new OA\JsonContent(ref: '#/components/schemas/WashOrderCompleteResponse')
            ),
            new OA\Response(response: 500, description: 'api failed')
        ]
    )]
    public function orderComplete_test()
    {
    }
}
