<?php

declare(strict_types=1);

namespace Tests\app\Http\Controllers;

use OpenApi\Attributes as OA;

class Retail extends Controller
{
    #[OA\Get(
        path: '/pickup/retail/product_list',
        description: '상품 리스트',
        tags: ['retail'],
        parameters: [
            new OA\Parameter(
                name: 'no_shop', description: '상점번호', in: 'query', required: true, schema: new OA\Schema(
                type: 'integer'
            )
            ),
            new OA\Parameter(
                name: 'no_category',
                description: '카테고리번호',
                in: 'query',
                required: true,
                schema: new OA\Schema(type: 'integer')
            ),
            new OA\Parameter(
                name: 'no_sub_category', description: '서브카테고리번호', in: 'query', schema: new OA\Schema(
                type: 'integer'
            )
            )
        ],
        responses: [
            new OA\Response(
                response: 200, description: 'success', content: new OA\JsonContent(
                ref: '#/components/schemas/RetailProductListResponse'
            )
            ),
            new OA\Response(response: 500, description: 'api failed')
        ]
    )]
    public function productList_test()
    {
    }

    #[OA\Put(
        path: '/retail/product_check',
        description: '상품 재고 체크',
        requestBody: new OA\RequestBody(
            description: '',
            content: [
                new OA\MediaType(
                    mediaType: 'application/json',
                    schema: new OA\Schema(
                        required: ['no_shop', 'no_product'],
                        properties: [
                            new OA\Property(property: 'no_shop', description: '상점번호', type: 'integer'),
                            new OA\Property(
                                property: 'no_product',
                                description: '상품번호',
                                type: 'array',
                                items: new OA\Items(type: 'integer')
                            )
                        ]
                    )
                )
            ]
        ),
        tags: ['retail'],
        responses: [
            new OA\Response(
                response: 200, description: 'success', content: new OA\JsonContent(
                ref: '#/components/schemas/RetailProductCheckResponse'
            )
            ),
            new OA\Response(response: 500, description: 'api failed')
        ]
    )]
    public function productCheck_test()
    {
    }

    #[OA\Get(
        path: '/pickup/retail/product_info',
        description: '상품정보',
        tags: ['retail'],
        parameters: [
            new OA\Parameter(
                name: 'no_shop', description: '상점번호', in: 'query', required: true, schema: new OA\Schema(
                type: 'integer'
            )
            ),
            new OA\Parameter(
                name: 'no_product',
                description: '상품번호',
                in: 'query',
                required: true,
                schema: new OA\Schema(type: 'integer')
            ),
        ],
        responses: [
            new OA\Response(
                response: 200, description: 'success', content: new OA\JsonContent(
                ref: '#/components/schemas/RetailProductInfoResponse'
            )
            ),
            new OA\Response(response: 500, description: 'api failed')
        ]
    )]
    public function productInfo_test()
    {
    }

    #[OA\Get(
        path: '/retail/arrival_alarm/{noOrder}',
        description: '매장도착알림',
        security: [['bearerAuth' => []]],
        tags: ['retail'],
        parameters: [
            new OA\Parameter(
                name: 'noOrder', description: '주문번호', in: 'path', required: true, schema: new OA\Schema(type: 'string')
            )
        ],
        responses: [
            new OA\Response(
                response: 200, description: 'success', content: new OA\JsonContent(properties: [
                new OA\Property(property: 'result', description: '성공 여부', type: 'bool')
            ], type: 'object')
            ),
            new OA\Response(response: 500, description: 'api failed')
        ]
    )]
    public function arrivalAlarm_test()
    {
    }
}
