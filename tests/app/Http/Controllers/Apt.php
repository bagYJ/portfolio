<?php

namespace Tests\app\Http\Controllers;

use OpenApi\Attributes as OA;

class Apt extends Controller
{
    #[OA\Get(
        path: '/apt/member',
        description: '회원이 등록한 아파트 목록',
        security: [['bearerAuth' => []]],
        tags: ['apt'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'success',
                content: new OA\JsonContent(ref: '#/components/schemas/MemberAptListResponse')
            ),
            new OA\Response(response: 500, description: 'api failed')
        ],
    )]
    public function getMemberAptList()
    {
    }

    #[OA\Get(
        path: '/apt',
        description: '아파트 목록',
        security: [['bearerAuth' => []]],
        tags: ['apt'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'success',
                content: new OA\JsonContent(ref: '#/components/schemas/AptListResponse')
            ),
            new OA\Response(response: 500, description: 'api failed')
        ],
    )]
    public function list()
    {
    }

    #[OA\Post(
        path: '/apt/{idApt}',
        description: '아파트 등록',
        security: [['bearerAuth' => []]],
        tags: ['apt'],
        parameters: [
            new OA\Parameter(name: 'idApt', in: 'path', required: true, schema: new OA\Schema(type: 'string')),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'success',
                content: new OA\JsonContent(properties: [new OA\Property(property: 'result', description: '성공 여부', type: 'bool')], type: 'object')
            ),
            new OA\Response(response: 500, description: 'api failed')
        ]
    )]
    public function register()
    {
    }

    #[OA\Delete(
        path: '/apt/{idApt}',
        description: '아파트 삭제',
        security: [['bearerAuth' => []]],
        tags: ['apt'],
        parameters: [
            new OA\Parameter(name: 'idApt', in: 'path', required: true, schema: new OA\Schema(type: 'string')),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'success',
                content: new OA\JsonContent(properties: [new OA\Property(property: 'result', description: '성공 여부', type: 'bool')], type: 'object')
            ),
            new OA\Response(response: 500, description: 'api failed')
        ]
    )]
    public function remove()
    {
    }
}
