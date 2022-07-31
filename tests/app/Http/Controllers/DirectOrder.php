<?php

declare(strict_types=1);

namespace Tests\app\Http\Controllers;

use OpenApi\Attributes as OA;

class DirectOrder extends Controller
{
    #[OA\Get(
        path: '/direct_order',
        description: '바로주문 리스트',
        security: [['bearerAuth' => []]],
        tags: ['direct_order'],
        responses: [
            new OA\Response(
                response: 200, description: 'success', content: new OA\JsonContent(
                ref: '#/components/schemas/DirectOrderResponse'
            )
            ),
            new OA\Response(response: 500, description: 'api failed')
        ]
    )]
    public function list_test()
    {}

    #[OA\Post(
        path: '/direct_order',
        description: '바로주문 등록',
        security: [['bearerAuth' => []]],
        tags: ['direct_order'],
        parameters: [
            new OA\Parameter(name: 'no_order', description: '주문번호', in: 'query', required: true, schema: new OA\Schema(type: 'string')),
            new OA\Parameter(name: 'biz_kind', description: '업종 (FNB: fnb, OIL: 주유, RETAIL: 리테일, WASH: 세차, PARKING: 주차)', in: 'query', required: true, schema: new OA\Schema(type: 'string', enum: ['FNB', 'OIL', 'RETAIL', 'WASH', 'PARKING']))
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'success',
                content: new OA\JsonContent(properties: [
                    new OA\Property(property: 'result', description: '성공 여부', type: 'bool')
                ], type: 'object')
            ),
            new OA\Response(response: 500, description: 'api failed')
        ]
    )]
    public function create_test()
    {}

    #[OA\Delete(
        path: '/direct_order/{no}',
        description: '바로주문 삭제',
        security: [['bearerAuth' => []]],
        tags: ['direct_order'],
        parameters: [
            new OA\Parameter(name: 'no', description: '바로주문 키', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'success',
                content: new OA\JsonContent(properties: [
                    new OA\Property(property: 'result', description: '성공 여부', type: 'bool')
                ], type: 'object')
            ),
            new OA\Response(response: 500, description: 'api failed')
        ]
    )]
    public function remove_test()
    {}
}
