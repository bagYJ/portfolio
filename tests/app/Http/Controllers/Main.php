<?php

declare(strict_types=1);

namespace Tests\app\Http\Controllers;

use OpenApi\Attributes as OA;

class Main extends Controller
{
    #[OA\Post(
        path: '/main',
        description: '메인',
        requestBody: new OA\RequestBody(
            description: '',
            content: [
                new OA\MediaType(
                    mediaType: 'application/json',
                    schema: new OA\Schema(
                        required: ['radius', 'position'],
                        properties: [
                            new OA\Property(
                                property: 'radius',
                                description: '반경',
                                type: 'float',
                                example: 5.0,
                                nullable: false
                            ),
                            new OA\Property(property: 'position', description: '위경도', properties: [
                                new OA\Property(
                                    property: 'x',
                                    description: '위도',
                                    type: 'float',
                                    example: 37.56247988835981,
                                    nullable: false
                                ),
                                new OA\Property(
                                    property: 'y',
                                    description: '경도',
                                    type: 'float',
                                    example: 126.9714889516946,
                                    nullable: false
                                )
                            ], type: 'object')
                        ]
                    )
                )
            ]
        ),
        tags: ['main'],
        responses: [
            new OA\Response(
                response: 200, description: 'success', content: new OA\JsonContent(
                ref: '#/components/schemas/MainResponse'
            )
            ),
            new OA\Response(response: 500, description: 'api failed')
        ]
    )]
    public function main_test()
    {
    }

    #[OA\Get(
        path: '/main/notice',
        description: '메인 공지사항 리스트',
        tags: ['main'],
        responses: [
            new OA\Response(
                response: 200, description: 'success', content: new OA\JsonContent(
                ref: '#/components/schemas/MainNoticeResponse'
            )
            ),
            new OA\Response(response: 500, description: 'api failed')
        ]
    )]
    public function notice()
    {
    }

    #[OA\Get(
        path: '/main/header',
        description: '메인 상단 텍스트',
        tags: ['main'],
        responses: [
            new OA\Response(
                response: 200, description: 'success', content: new OA\JsonContent(
                ref: '#/components/schemas/MainHeaderResponse'
            )
            ),
            new OA\Response(response: 500, description: 'api failed')
        ]
    )]
    public function header()
    {
    }
}
