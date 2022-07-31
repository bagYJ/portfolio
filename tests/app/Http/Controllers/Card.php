<?php

declare(strict_types=1);

namespace Tests\app\Http\Controllers;

use OpenApi\Attributes as OA;

class Card extends Controller
{
    #[OA\Get(
        path: '/card/lists',
        description: '카드 정보',
        security: [['bearerAuth' => []]],
        tags: ['card'],
        responses: [
            new OA\Response(
                response: 200, description: 'success', content: new OA\JsonContent(
                ref: '#/components/schemas/CardListResponse'
            )
            ),
            new OA\Response(response: 500, description: 'api failed')
        ]
    )]
    public function lists_test()
    {
    }

    #[OA\Post(
        path: '/card/regist',
        description: '카드 등록',
        security: [['bearerAuth' => []]],
        requestBody: new OA\RequestBody(
            description: '',
            content: [
                new OA\MediaType(
                    mediaType: 'application/x-www-form-urlencoded',
                    schema: new OA\Schema(
                        required: ['no_cardnum', 'no_expyea', 'no_expmon', 'no_pin'],
                        properties: [
                            new OA\Property(property: 'no_cardnum', description: '카드번호', type: 'integer'),
                            new OA\Property(property: 'no_expyea', description: '카드 만료 년', type: 'string'),
                            new OA\Property(property: 'no_expmon', description: '카드 만료 월', type: 'string'),
                            new OA\Property(property: 'no_pin', description: '비밀번호 앞 2자리', type: 'string')
                        ]
                    )
                )
            ]
        ),
        tags: ['card'],
        responses: [
            new OA\Response(
                response: 200, description: 'success', content: new OA\JsonContent(properties: [
                new OA\Property(property: 'result', description: '성공 여부', type: 'bool'),
                new OA\Property(property: 'no_card', description: '카드번호', type: 'string')
            ], type: 'object')
            ),
            new OA\Response(response: 500, description: 'api failed')
        ],
    )]
    public function regist_test()
    {
    }

    #[OA\Delete(
        path: '/card/remove/{noCard}',
        description: '카드 삭제',
        security: [['bearerAuth' => []]],
        tags: ['card'],
        parameters: [
            new OA\Parameter(name: 'noCard', description: '카드번호', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))
        ],
        responses: [
            new OA\Response(
                response: 200, description: 'success', content: new OA\JsonContent(properties: [
                new OA\Property(property: 'result', description: '성공 여부', type: 'bool')
            ], type: 'object')
            ),
            new OA\Response(response: 500, description: 'api failed')
        ],
    )]
    public function remove_test()
    {
    }

    #[OA\Put(
        path: '/card/main/{noCard}',
        description: '메인 카드 등록',
        security: [['bearerAuth' => []]],
        tags: ['card'],
        parameters: [
            new OA\Parameter(name: 'noCard', description: '카드번호', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))
        ],
        responses: [
            new OA\Response(
                response: 200, description: 'success', content: new OA\JsonContent(properties: [
                new OA\Property(property: 'result', description: '성공 여부', type: 'bool')
            ], type: 'object')
            ),
            new OA\Response(response: 500, description: 'api failed')
        ],
    )]
    public function mainCard_test()
    {
    }
}
