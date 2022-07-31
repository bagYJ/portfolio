<?php

/**
 *
 */

declare(strict_types=1);

namespace Tests\app\Http\Controllers;

use OpenApi\Attributes as OA;

class Oauth extends Controller
{
    #[OA\Get(
        path: '/oauth/get_regist_code',
        description: '인증번호 발급',
        security: [['bearerAuth' => []]],
        tags: ['oauth'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'success',
                content: new OA\JsonContent(ref: '#/components/schemas/GetRegistCode')
            ),
            new OA\Response(response: 500, description: 'api failed')
        ]
    )]
    public function registCode_test()
    {
    }

    #[OA\Post(
        path: '/oauth/authorization',
        description: '회원 인증',
        requestBody: new OA\RequestBody(
            description: '',
            content: [
                new OA\MediaType(
                    mediaType: 'application/x-www-form-urlencoded',
                    schema: new OA\Schema(
                        required: ['oauth_code', 'no_vin'],
                        properties: [
                            new OA\Property(
                                property: 'oauth_code',
                                description: '인증코드 (6자리 숫자)',
                                type: 'string',
                                maxLength: 6
                            ),
                            new OA\Property(property: 'no_vin', description: 'vin 코드', type: 'string')
                        ]
                    )
                )
            ]
        ),
        tags: ['oauth'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'success',
                content: new OA\JsonContent(ref: '#/components/schemas/Authorization')
            ),
            new OA\Response(response: 500, description: 'api failed')
        ]
    )]
    public function authorization_test()
    {
    }

    #[OA\Post(
        path: '/oauth/token',
        description: '인증 토큰 발급 (로그인)',
        requestBody: new OA\RequestBody(
            description: '',
            content: [
                new OA\MediaType(
                    mediaType: 'application/x-www-form-urlencoded',
                    schema: new OA\Schema(
                        required: ['ds_udid', 'id_user', 'ds_passwd'],
                        properties: [
                            new OA\Property(
                                property: 'ds_udid',
                                description: '핸드폰 토큰 (firebase 관련)',
                                type: 'string',
                                nullable: false
                            ),
                            new OA\Property(property: 'id_user', description: '아이디', type: 'string', nullable: false),
                            new OA\Property(property: 'ds_passwd', description: '비밀번호', type: 'string', nullable: false)
                        ]
                    )
                )
            ]
        ),
        tags: ['oauth'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'success',
                content: new OA\JsonContent(ref: '#/components/schemas/Token')
            ),
            new OA\Response(response: 500, description: 'api failed')
        ]
    )]
    public function token_test()
    {
    }

    #[OA\Post(
        path: '/oauth/refresh_token',
        description: '인증 토큰 재발급',
        tags: ['oauth'],
        parameters: [
            new OA\Parameter(name: 'refresh-token', in: 'header', required: true, schema: new OA\Schema(type: 'string'))
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'success',
                content: new OA\JsonContent(ref: '#/components/schemas/Token')
            ),
            new OA\Response(response: 500, description: 'api failed')
        ]
    )]
    public function refreshToken_test()
    {
    }

    #[OA\Delete(
        path: '/oauth/token',
        description: '발급 토큰 삭제',
        tags: ['oauth'],
        responses: [
            new OA\Response(
                response: 200, description: 'success', content: new OA\JsonContent(properties: [
                new OA\Property(property: 'result', description: '성공 여부', type: 'bool')
            ], type: 'object')
            ),
            new OA\Response(response: 500, description: 'api failed')
        ]
    )]
    public function deleteTokens_test()
    {
    }

    #[OA\Get(
        path: '/oauth/get_access_check',
        description: '커넥티드카 연동 확인',
        security: [['bearerAuth' => []]],
        tags: ['oauth'],
        responses: [
            new OA\Response(
                response: 200, description: 'success', content: new OA\JsonContent(properties: [
                new OA\Property(property: 'result', description: '성공 여부', type: 'bool'),
                new OA\Property(property: 'yn_access_status', description: '연동상태 (Y:연동 / N:미연동)', type: 'string'),
                new OA\Property(property: 'dt_account_reg_rsm', description: '계정연동일시 (연동 상태일경우 전달)', type: 'string'),
                new OA\Property(property: 'ds_access_vin_rsm', description: '등록차량 차대번호', type: 'string'),
            ], type: 'object')
            ),
            new OA\Response(response: 500, description: 'api failed')
        ]
    )]
    public function accessCheck_test()
    {
    }

    #[OA\Put(
        path: '/oauth/access_disconnect',
        description: '커넥티드카 연동 해제',
        security: [['bearerAuth' => []]],
        tags: ['oauth'],
        responses: [
            new OA\Response(
                response: 200, description: 'success', content: new OA\JsonContent(properties: [
                new OA\Property(property: 'result', description: '성공 여부', type: 'bool')
            ], type: 'object')
            ),
            new OA\Response(response: 500, description: 'api failed')
        ]
    )]
    public function accessDisconnect_test()
    {
    }
}
