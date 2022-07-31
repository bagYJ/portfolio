<?php

declare(strict_types=1);

namespace Tests\app\Http\Controllers;

use OpenApi\Attributes as OA;

class Product extends Controller
{
    #[OA\Get(
        path: '/product/get_list/{noShop}',
        description: '상품 리스트',
        security: [['bearerAuth' => []]],
        tags: ['product'],
        parameters: [
            new OA\Parameter(
                name: 'noShop', description: '상점번호', in: 'path', required: true, schema: new OA\Schema(
                type: 'integer', example: 10921000
            )
            ),
            new OA\Parameter(
                name: 'noCategory', description: '카테고리번호', in: 'query', schema: new OA\Schema(
                type: 'integer'
            )
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'success',
                content: new OA\JsonContent(
                    ref: '#/components/schemas/GetList'
                )
            ),
            new OA\Response(response: 500, description: 'api failed')
        ]
    )]
    public function getList_test()
    {
    }

    #[OA\Get(
        path: '/product/{noShop}/{noProduct}',
        description: '상품 상세',
        tags: ['product'],
        parameters: [
            new OA\Parameter(
                name: 'noShop', description: '상점번호', in: 'path', required: true, schema: new OA\Schema(
                type: 'integer', example: 10921000
            )
            ),
            new OA\Parameter(
                name: 'noProduct', description: '상품번호', in: 'path', required: true, schema: new OA\Schema(
                type: 'integer', example: 10921001
            )
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'success',
                content: new OA\JsonContent(
                    ref: '#/components/schemas/ProductInfo',
                )
            ),
            new OA\Response(response: 500, description: 'api failed')
        ]
    )]
    public function product_test()
    {
    }
}
