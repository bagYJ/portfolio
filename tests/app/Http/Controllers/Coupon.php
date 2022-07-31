<?php

declare(strict_types=1);

namespace Tests\app\Http\Controllers;

use OpenApi\Attributes as OA;

class Coupon extends Controller
{
    #[OA\Get(
        path: '/coupon/lists',
        description: '쿠폰리스트',
        security: [['bearerAuth' => []]],
        tags: ['coupon'],
        parameters: [
            new OA\Parameter(
                name: 'bizKind',
                description: '쿠폰종류 (FNB: fnb, RETAIL: 편의점, OIL: 주유소, WASH: 세차, PARKING: 주차)',
                in: 'query',
                required: false, schema: new OA\Schema(type: 'string', enum: ['FNB', 'RETAIL', 'OIL', 'WASH', 'PARKING'], nullable: true)
            ),
            new OA\Parameter(
                name: 'use_coupon_yn', description: '쿠폰 가능 사용 여부', in: 'query', schema: new OA\Schema(
                type: 'string', enum: ['Y', 'N']
            )
            )
        ],
        responses: [
            new OA\Response(
                response: 200, description: 'success', content: new OA\JsonContent(
                ref: '#/components/schemas/CouponListResponse'
            )
            ),
            new OA\Response(response: 500, description: 'api failed')
        ]
    )]
    public function lists_test()
    {
    }
}
