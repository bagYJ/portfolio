<?php

declare(strict_types=1);

namespace Tests\app\Http\Controllers;

use OpenApi\Attributes as OA;

class Customer extends Controller
{
    #[OA\Get(
        path: '/customer/list',
        description: '이벤트 리스트',
        tags: ['customer'],
        parameters: [
            new OA\Parameter(name: 'size', in: 'query', schema: new OA\Schema(type: 'integer',)),
            new OA\Parameter(name: 'offset', in: 'query', schema: new OA\Schema(type: 'integer',)),
            new OA\Parameter(
                name: 'status', description: '이벤트 종료 여부(Y:진행중 이벤트, N:종료된 이베트) ', in: 'query', schema: new OA\Schema(
                type: 'string', enum: ['Y', 'N']
            )
            )
        ],
        responses: [
            new OA\Response(
                response: 200, description: 'success', content: new OA\JsonContent(
                ref: '#/components/schemas/EventListResponse'
            )
            ),
            new OA\Response(response: 500, description: 'api failed')
        ]

    )]
    public function getEventList()
    {
    }

    #[OA\Get(
        path: '/customer/{no}',
        description: '이벤트 상세',
        tags: ['customer'],
        parameters: [
            new OA\Parameter(name: 'no', description: '이벤트 번호', in: 'path', schema: new OA\Schema(type: 'integer'), example: 1),
        ],
        responses: [
            new OA\Response(
                response: 200, description: 'success', content: new OA\JsonContent(
                ref: '#/components/schemas/EventResponse'
            )
            ),
            new OA\Response(response: 500, description: 'api failed')
        ]

    )]
    public function getEvent()
    {
    }

    #[OA\Get(
        path: '/customer/faq',
        description: 'FAQ 리스트',
        security: [['bearerAuth' => []]],
        tags: ['customer'],
        parameters: [
            new OA\Parameter(name: 'size', in: 'query', schema: new OA\Schema(type: 'integer',)),
            new OA\Parameter(name: 'offset', in: 'query', schema: new OA\Schema(type: 'integer',)),
        ],
        responses: [
            new OA\Response(
                response: 200, description: 'success', content: new OA\JsonContent(
                ref: '#/components/schemas/FaqListResponse'
            )
            ),
            new OA\Response(response: 500, description: 'api failed')
        ]

    )]
    public function getFaqList()
    {
    }

    #[OA\Put(
        path: '/customer/event_push_msg',
        description: '이벤트 푸시 메세지 수신 여부 설정',
        security: [['bearerAuth' => []]],
        tags: ['customer'],
        parameters: [
            new OA\Parameter(
                name: 'yn_push_msg_event', description: '푸시 메세지 수신 여부 (Y:수신, N:미수신) ', in: 'query', required: true, schema: new OA\Schema(
                type: 'string', enum: ['Y', 'N']
            )
            )
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

    #[OA\Get(
        path: '/customer/push_msg',
        description: '이벤트 푸시 메세지 사용 여부',
        security: [['bearerAuth' => []]],
        tags: ['customer'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'success',
                content: new OA\JsonContent(properties: [
                    new OA\Property(property: 'result', description: '성공 여부', type: 'bool'),
                    new OA\Property(property: 'is_push_msg_event', description: '이벤트 푸시 메세지 사용 여부', type: 'bool')
                ], type: 'object')
            ),
            new OA\Response(response: 500, description: 'api failed')
        ]
    )]
    public function getEventPush_test()
    {
    }
}
