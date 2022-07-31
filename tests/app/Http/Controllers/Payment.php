<?php

declare(strict_types=1);

namespace Tests\app\Http\Controllers;

use OpenApi\Attributes as OA;

class Payment extends Controller
{
    #[OA\Put(
        path: '/payment/cancel',
        description: '주문 취소',
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
                            new OA\Property(property: 'cd_reject_reason', description: '취소 이유', type: 'string')
                        ]
                    )
                )
            ]
        ),
        tags: ['payment'],
        responses: [
            new OA\Response(
                response: 200, description: 'success', content: new OA\JsonContent(
                ref: '#/components/schemas/PaymentCancelResponse'
            )
            ),
            new OA\Response(response: 500, description: 'api failed')
        ]
    )]
    public function cancel_test()
    {
    }

    #[OA\Put(
        path: '/payment/incomplete',
        description: '미결제건 결제',
        security: [['bearerAuth' => []]],
        requestBody: new OA\RequestBody(
            description: '',
            content: [
                new OA\MediaType(
                    mediaType: 'application/x-www-form-urlencoded',
                    schema: new OA\Schema(
                        required: ['biz_kind','no_order', 'no_card'],
                        properties: [
                            new OA\Property(property: 'biz_kind', description: '업종 (FNB: fnb, OIL: 주유, RETAIL: 리테일, WASH: 세차, PARKING: 주차)', type: 'string'),
                            new OA\Property(property: 'no_order', description: '주문번호', type: 'string'),
                            new OA\Property(property: 'no_card', description: '카드번호', type: 'string'),
                        ]
                    )
                )
            ]
        ),
        tags: ['payment'],
        responses: [
            new OA\Response(
                response: 200, description: 'success', content: new OA\JsonContent(
                ref: '#/components/schemas/OrderPaymentResponse'
            )
            ),
            new OA\Response(response: 500, description: 'api failed')
        ]
    )]
    public function incomplete_test()
    {
    }
}
