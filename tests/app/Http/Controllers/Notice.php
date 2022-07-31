<?php

declare(strict_types=1);

namespace Tests\app\Http\Controllers;

use OpenApi\Attributes as OA;

class Notice
{
    #[OA\Get(
        path: '/notice/gets',
        description: '공지사항 조회',
        tags: ['notice'],
        parameters: [
            new OA\Parameter(name: 'size', description: '페이지 당 항목 개수', in: 'query', schema: new OA\Schema(type: 'integer',)),
            new OA\Parameter(name: 'offset', description: '페이지 offset', in: 'query', schema: new OA\Schema(type: 'integer',))
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'success',
                content: new OA\JsonContent(
                    ref: '#/components/schemas/NoticeGets'
                )
            ),
            new OA\Response(response: 500, description: 'api failed')
        ]
    )]
    public function gets_test()
    {
    }

    #[OA\Get(
        path: '/notice/get/{no}',
        description: '공지사항 단일 조회',
        tags: ['notice'],
        parameters: [
            new OA\Parameter(
                name: 'no', description: 'no', in: 'path', required: true, schema: new OA\Schema(
                type: 'integer', example: 1
            )
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'success',
                content: new OA\JsonContent(
                    ref: '#/components/schemas/NoticeGet'
                )
            ),
            new OA\Response(response: 500, description: 'api failed')
        ]
    )]
    public function get_test()
    {
    }
}
