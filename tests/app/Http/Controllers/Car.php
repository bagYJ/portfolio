<?php

declare(strict_types=1);

namespace Tests\app\Http\Controllers;

use OpenApi\Attributes as OA;

class Car extends Controller
{
    #[OA\Get(
        path: '/car/maker',
        description: '차량 제조사 목록',
        tags: ['car'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'success',
                content: new OA\JsonContent(ref: '#/components/schemas/CarMakerResponse')
            ),
            new OA\Response(response: 500, description: 'api failed')
        ]
    )]
    public function makerList()
    {
    }

    #[OA\Get(
        path: '/car/kind_by_car/{noMaker}',
        description: '제조사 별 차종 목록',
        tags: ['car'],
        parameters: [
            new OA\Parameter(name: 'noMaker', in: 'path', required: true, schema: new OA\Schema(type: 'integer',)),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'success',
                content: new OA\JsonContent(ref: '#/components/schemas/KindByCarResponse')
            ),
            new OA\Response(response: 500, description: 'api failed')
        ]
    )]
    public function kindByCarList()
    {
    }
}
