<?php

namespace Tests\app\Http\Controllers;

use OpenApi\Attributes as OA;

class Action extends Controller
{
    #[OA\Get(
        path: '/action/uptime_check/{article}',
        description: '주유주문관련 시간체크',
        security: [['bearerAuth' => []]],
        tags: ['action'],
        parameters: [
            new OA\Parameter(
                name: 'article', in: 'path', required: true, schema: new OA\Schema(
                type: 'string',
                enum: ['S', 'O']
            )
            ),
        ], responses: [
        new OA\Response(
            response: 200,
            description: 'success',
            content: new OA\JsonContent(ref: '#/components/schemas/UptimeCheck')
        ),
        new OA\Response(response: 500, description: 'api failed')
    ]
    )]
    public function uptimeCheck_test()
    {
    }

    #[OA\Delete(
        path: '/action/cache_clear/{key}',
        description: '캐시 파일 삭제',
        tags: ['action'],
        parameters: [
            new OA\Parameter(
                name: 'key', description: '캐시 key 값', in: 'path', required: true, schema: new OA\Schema(type: 'string')
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'success',
                content: new OA\JsonContent(properties: [new OA\Property(property: 'result', description: '캐시 삭제 여부', type: 'bool')], type: 'object')
            ),
            new OA\Response(response: 500, description: 'api failed')
        ],
    )]
    public function cacheClear_test()
    {
    }
}
