<?php

declare(strict_types=1);

namespace Tests\app\Http\Controllers;

use OpenApi\Attributes as OA;

class Cert extends Controller
{
    #[OA\Post(
        path: '/cert/request',
        description: '본인인증',
        requestBody: new OA\RequestBody(
            description: '',
            content: [
                new OA\MediaType(
                    mediaType: 'application/x-www-form-urlencoded',
                    schema: new OA\Schema(
                        required: ['pm_name', 'pm_birth', 'pm_agency', 'pm_phone', 'pm_nation', 'pm_sex'],
                        properties: [
                            new OA\Property(property: 'pm_name', description: '이름', type: 'string'),
                            new OA\Property(property: 'pm_birth', description: '생년월일', type: 'string', maxLength: 8),
                            new OA\Property(property: 'pm_agency', description: '핸드폰 회사', type: 'string', enum: [
                                'SKT',
                                'KTF',
                                'LGT',
                                'SKM',
                                'KTM',
                                'LGM'
                            ]),
                            new OA\Property(
                                property: 'pm_phone',
                                description: '핸드폰번호',
                                type: 'string',
                                maxLength: 11,
                                minLength: 10
                            ),
                            new OA\Property(
                                property: 'pm_nation',
                                description: '내외국인 (K: 내국인, F: 외국인)',
                                type: 'string',
                                enum: ['K', 'F']
                            ),
                            new OA\Property(
                                property: 'pm_sex',
                                description: '성별 (M: 남, F: 여)',
                                type: 'string',
                                enum: ['M', 'F']
                            )
                        ]
                    )
                )
            ]
        ),
        tags: ['cert'],
        responses: [
            new OA\Response(
                response: 200, description: 'success', content: new OA\JsonContent(properties: [
                new OA\Property(property: 'result', description: '성공 여부', type: 'bool'),
                new OA\Property(property: 'no_auth_seq', description: '요청식별번호', type: 'string')
            ], type: 'object')
            ),
            new OA\Response(response: 500, description: 'api failed')
        ]
    )]
    public function request_test()
    {
    }

    #[OA\Post(
        path: '/cert/retry',
        description: '본인인증 재시도',
        requestBody: new OA\RequestBody(
            description: '',
            content: [
                new OA\MediaType(
                    mediaType: 'application/x-www-form-urlencoded',
                    schema: new OA\Schema(
                        required: ['no_auth_seq'],
                        properties: [
                            new OA\Property(property: 'no_auth_seq', description: '요청식별번호', type: 'string')
                        ]
                    )
                )
            ]
        ),
        tags: ['cert'],
        responses: [
            new OA\Response(
                response: 200, description: 'success', content: new OA\JsonContent(properties: [
                new OA\Property(property: 'result', description: '성공 여부', type: 'bool'),
                new OA\Property(property: 'no_auth_seq', description: '요청식별번호', type: 'string')
            ], type: 'object')
            ),
            new OA\Response(response: 500, description: 'api failed')
        ]
    )]
    public function retry_test()
    {
    }

    #[OA\Post(
        path: '/cert/complete',
        description: '본인인증 완료',
        requestBody: new OA\RequestBody(
            description: '',
            content: [
                new OA\MediaType(
                    mediaType: 'application/x-www-form-urlencoded',
                    schema: new OA\Schema(
                        required: ['no_auth_seq', 'sms_num'],
                        properties: [
                            new OA\Property(property: 'no_auth_seq', description: '요청식별번호', type: 'string'),
                            new OA\Property(property: 'sms_num', description: 'sms 인증번호', type: 'string')
                        ]
                    )
                )
            ]
        ),
        tags: ['cert'],
        responses: [
            new OA\Response(
                response: 200, description: 'success', content: new OA\JsonContent(properties: [
                new OA\Property(property: 'result', description: '성공 여부', type: 'bool'),
                new OA\Property(property: 'joined', description: '회원가입여부', type: 'bool'),
                new OA\Property(property: 'id_user', description: '회원아이디', type: 'string'),
                new OA\Property(property: 'no_user', description: '회원번호', type: 'integer')
            ], type: 'object')
            ),
            new OA\Response(response: 500, description: 'api failed')
        ]
    )]
    public function complete_test()
    {
    }
}
