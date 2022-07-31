<?php

declare(strict_types=1);

namespace Tests\app\Http\Controllers;

use OpenApi\Attributes as OA;

class Shop extends Controller
{
    #[OA\Get(
        path: '/shop/info/{noShop}',
        description: '매장소개정보',
        tags: ['shop'],
        parameters: [
            new OA\Parameter(name: 'noShop', in: 'path', required: true, schema: new OA\Schema(type: 'integer',)),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'success',
                content: new OA\JsonContent(
                    ref: '#/components/schemas/Shop'
                )
            ),
            new OA\Response(response: 500, description: 'api failed')
        ]
    )]
    public function info_test()
    {
    }

    #[OA\Get(
        path: '/shop/review',
        description: '매장리뷰조회',
        tags: ['shop'],
        parameters: [
            new OA\Parameter(name: 'no_shop', in: 'query', required: true, schema: new OA\Schema(type: 'integer',)),
            new OA\Parameter(name: 'size', description: '페이지 당 항목 개수', in: 'query', schema: new OA\Schema(type: 'integer',)),
            new OA\Parameter(name: 'offset', description: '페이지 offset', in: 'query', schema: new OA\Schema(type: 'integer',)),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'success',
                content: new OA\JsonContent(
                    ref: '#/components/schemas/ShopReview'
                )
            ),
            new OA\Response(response: 500, description: 'api failed')
        ]
    )]
    public function review_test()
    {
    }

    #[OA\Post(
        path: '/shop/review_regist',
        description: '매장리뷰등록',
        security: [['bearerAuth' => []]],
        requestBody: new OA\RequestBody(
            description: '',
            content: [
                new OA\MediaType(
                    mediaType: 'application/x-www-form-urlencoded',
                    schema: new OA\Schema(
                        required: ['no_shop', 'no_order', 'ds_content', 'at_grade'],
                        properties: [
                            new OA\Property(property: 'no_shop', description: '매장번호', type: 'integer', example: 1),
                            new OA\Property(property: 'no_order', description: '주문번호', type: 'string', example: '1'),
                            new OA\Property(property: 'ds_content', description: '리뷰내용', type: 'string', example: '리뷰 등록 테스트'),
                            new OA\Property(property: 'at_grade', description: '평점', type: 'float', example: 2.5)
                        ]
                    )
                )
            ]
        ),
        tags: ['shop'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'success',
                content: new OA\JsonContent(
                    ref: '#/components/schemas/ReviewRegist'
                )
            ),
            new OA\Response(response: 500, description: 'api failed')
        ]
    )]
    public function reviewRegist_test()
    {
    }

    #[OA\Get(
        path: '/shop/main_info',
        description: '매장조회',
        tags: ['shop'],
        parameters: [
            new OA\Parameter(
                name: 'no_shop', description: '매장번호', in: 'query', required: true, schema: new OA\Schema(
                type: 'integer',
            )
            ),
            new OA\Parameter(name: 'size', in: 'query', schema: new OA\Schema(type: 'integer',)),
            new OA\Parameter(name: 'offset', in: 'query', schema: new OA\Schema(type: 'integer',)),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'success',
                content: new OA\JsonContent(
                    ref: '#/components/schemas/MainInfo'
                )
            ),
            new OA\Response(response: 500, description: 'api failed')
        ]
    )]
    public function mainInfo_test()
    {
    }

    #[OA\Get(
        path: '/shop/commission_info',
        description: '매장 수수료율 조회',
        tags: ['shop'],
        parameters: [
            new OA\Parameter(name: 'no_shop', in: 'query', required: true, schema: new OA\Schema(type: 'integer',)),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'success',
                content: new OA\JsonContent(
                    ref: '#/components/schemas/CommissionResult'
                )
            ),
            new OA\Response(response: 500, description: 'api failed')
        ]
    )]
    public function commissionInfo_test()
    {
    }

    #[OA\Put(
        path: '/shop/review',
        description: '매장리뷰평점조회',
        requestBody: new OA\RequestBody(
            description: '',
            content: [
                new OA\MediaType(
                    mediaType: 'application/json',
                    schema: new OA\Schema(
                        required: ['no_shop'],
                        properties: [
                            new OA\Property(
                                property: 'no_shop',
                                description: '상점번호',
                                type: 'array',
                                items: new OA\Items(type: 'integer')
                            )
                        ]
                    )
                )
            ]
        ),
        tags: ['shop'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'success',
                content: new OA\JsonContent(
                    ref: '#/components/schemas/ShopReviewResponse'
                )
            ),
            new OA\Response(response: 500, description: 'api failed')
        ]
    )]
    public function reviews_test()
    {
    }

    #[OA\Delete(
        path: '/shop/review_remove/{noReview}',
        description: '매장리뷰 삭제',
        security: [['bearerAuth' => []]],
        tags: ['shop'],
        parameters: [
            new OA\Parameter(name: 'noReview', description: '리뷰 번호', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'success',
                content: new OA\JsonContent(properties: [new OA\Property(property: 'result', description: '삭제 여부', type: 'bool')], type: 'object')
            ),
            new OA\Response(response: 500, description: 'api failed')
        ]
    )]
    public function review_remove_test()
    {
    }

    #[OA\Post(
        path: '/shop/review_siren/{noReview}',
        description: '매장리뷰 신고',
        security: [['bearerAuth' => []]],
        tags: ['shop'],
        parameters: [
            new OA\Parameter(name: 'noReview', description: '리뷰 번호', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'success',
                content: new OA\JsonContent(properties: [new OA\Property(property: 'result', description: '성공 여부', type: 'bool')], type: 'object')
            ),
            new OA\Response(response: 500, description: 'api failed')
        ]
    )]
    public function review_siren_test()
    {
    }
}
