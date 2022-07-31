<?php

declare(strict_types=1);

namespace Tests\app\Http\Controllers;

use OpenApi\Attributes as OA;

class EvCharger extends Controller
{
    #[OA\Get(
        path: '/ev_charger/filter',
        description: '검색 필터',
        tags: ['ev_charger'],
        responses: [
            new OA\Response(
                response: 200, description: 'success', content: new OA\JsonContent(
                ref: '#/components/schemas/FilterResponse'
            )
            ),
            new OA\Response(response: 500, description: 'api failed')
        ]
    )]
    public function filter()
    {
    }

    #[OA\Get(
        path: '/ev_charger/info/{idStat}',
        description: '충전소 정보',
        tags: ['ev_charger'],
        parameters: [
            new OA\Parameter(name: 'idStat', in: 'path', required: true, schema: new OA\Schema(type: 'string',)),
        ],
        responses: [
            new OA\Response(
                response: 200, description: 'success', content: new OA\JsonContent(
                ref: '#/components/schemas/InfoResponse'
            )
            ),
            new OA\Response(response: 500, description: 'api failed')
        ]
    )]
    public function info()
    {
    }
}
