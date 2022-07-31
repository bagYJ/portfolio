<?php

/**
 *
 */

declare(strict_types=1);

namespace Tests\app\Http\Controllers;

use OpenApi\Attributes as OA;

class Search extends Controller
{
    #[OA\Get(
        path: '/search/tag',
        description: '매장 태그 검색',
        tags: ['search'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'success',
                content: new OA\JsonContent(ref: '#/components/schemas/Tag')
            ),
            new OA\Response(response: 500, description: 'api failed')
        ]
    )]
    public function tag_test()
    {
    }
}
