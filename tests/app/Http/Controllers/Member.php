<?php

declare(strict_types=1);

namespace Tests\app\Http\Controllers;

use OpenApi\Attributes as OA;

class Member extends Controller
{
    #[OA\Get(
        path: '/member',
        description: '회원번호 전달',
        security: [['bearerAuth' => []]],
        tags: ['member'],
        responses: [
            new OA\Response(
                response: 200, description: 'success', content: new OA\JsonContent(
                ref: '#/components/schemas/MemberResponse'
            )
            ),
            new OA\Response(response: 500, description: 'api failed')
        ]
    )]
    public function getUser_test()
    {
    }

    #[OA\Get(
        path: '/member/order_list',
        description: '회원 주문 목록',
        security: [['bearerAuth' => []]],
        tags: ['member'],
        parameters: [
            new OA\Parameter(name: 'size', description: '페이지 당 항목 개수', in: 'query', schema: new OA\Schema(type: 'integer',)),
            new OA\Parameter(name: 'offset', description: '페이지 offset', in: 'query', schema: new OA\Schema(type: 'integer',)),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'success',
                content: new OA\JsonContent(
                    ref: '#/components/schemas/MemberOrderListResponse'
                )
            ),
            new OA\Response(response: 500, description: 'api failed')
        ]
    )]
    public function getNoUser_test()
    {
    }

    #[OA\Get(
        path: '/member/car',
        description: '회원 차량 리스트',
        security: [['bearerAuth' => []]],
        tags: ['member'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'success',
                content: new OA\JsonContent(
                    ref: '#/components/schemas/MemberCarResponse'
                )
            ),
            new OA\Response(response: 500, description: 'api failed')
        ]
    )]
    public function getCar_test()
    {
    }

    #[OA\Post(
        path: '/member/car',
        description: '회원 차량 등록',
        security: [['bearerAuth' => []]],
        requestBody: new OA\RequestBody(
            description: '',
            content: [
                new OA\MediaType(
                    mediaType: 'application/x-www-form-urlencoded',
                    schema: new OA\Schema(
                        required: ['seq', 'ds_car_number', 'ds_car_color', 'cd_gas_kind'],
                        properties: [
                            new OA\Property(property: 'seq', description: '차종 번호', type: 'int'),
                            new OA\Property(property: 'ds_car_number', description: '차량 번호', type: 'string'),
                            new OA\Property(property: 'ds_car_color', description: '차량 색상', type: 'string'),
                            new OA\Property(
                                property: 'cd_gas_kind',
                                description: '유종 (204100: 휘발유, 204200: 경유, 204300: LPG, 204400: 고급 휘발유, 204500: 전기, 204600: 실내등유)',
                                type: 'string',
                                enum: ['204100', '204200', '204300', '204400', '204500', '204600']
                            )
                        ]
                    )
                )
            ]
        ),
        tags: ['member'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'success',
                content: new OA\JsonContent(properties: [
                    new OA\Property(property: 'result', description: '성공 여부', type: 'bool')
                ], type: 'object')
            ),
            new OA\Response(response: 500, description: 'api failed')
        ]
    )]
    public function registCar_test()
    {
    }

    #[OA\Put(
        path: '/member/car',
        description: '회원 차량 수정',
        security: [['bearerAuth' => []]],
        requestBody: new OA\RequestBody(
            description: '',
            content: [
                new OA\MediaType(
                    mediaType: 'application/x-www-form-urlencoded',
                    schema: new OA\Schema(
                        required: ['no', 'seq', 'ds_car_number', 'ds_car_color', 'cd_gas_kind'],
                        properties: [
                            new OA\Property(property: 'no', description: '보유차량 기본키', type: 'int'),
                            new OA\Property(property: 'seq', description: '차종 번호', type: 'int'),
                            new OA\Property(property: 'ds_car_number', description: '차량 번호', type: 'string'),
                            new OA\Property(property: 'ds_car_color', description: '차량 색상', type: 'string'),
                            new OA\Property(
                                property: 'cd_gas_kind',
                                description: '유종 (204100: 휘발유, 204200: 경유, 204300: LPG, 204400: 고급 휘발유, 204500: 전기, 204600: 실내등유)',
                                type: 'string',
                                enum: ['204100', '204200', '204300', '204400', '204500', '204600']
                            )
                        ]
                    )
                )
            ]
        ),
        tags: ['member'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'success',
                content: new OA\JsonContent(properties: [
                    new OA\Property(property: 'result', description: '성공 여부', type: 'bool')
                ], type: 'object')
            ),
            new OA\Response(response: 500, description: 'api failed')
        ]
    )]
    public function modifyCar_test()
    {
    }

    #[OA\Delete(
        path: '/member/car/{no}',
        description: '회원 차량 삭제',
        security: [['bearerAuth' => []]],
        tags: ['member'],
        parameters: [
            new OA\Parameter(name: 'no', description: '보유차량 기본키', in: 'path', schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'success',
                content: new OA\JsonContent(properties: [
                    new OA\Property(property: 'result', description: '성공 여부', type: 'bool')
                ], type: 'object')
            ),
            new OA\Response(response: 500, description: 'api failed')
        ]
    )]
    public function deleteCar_test()
    {
    }

    #[OA\Put(
        path: '/member/car/main/{no}',
        description: '회원 차량 메인 등록',
        security: [['bearerAuth' => []]],
        tags: ['member'],
        parameters: [
            new OA\Parameter(name: 'no', description: '보유차량 기본키', in: 'path', schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'success',
                content: new OA\JsonContent(properties: [
                    new OA\Property(property: 'result', description: '성공 여부', type: 'bool')
                ], type: 'object')
            ),
            new OA\Response(response: 500, description: 'api failed')
        ]
    )]
    public function mainCar_test()
    {
    }

    #[OA\Post(
        path: '/member',
        description: '회원 가입',
        requestBody: new OA\RequestBody(
            description: '',
            content: [
                new OA\MediaType(
                    mediaType: 'application/x-www-form-urlencoded',
                    schema: new OA\Schema(
                        required: ['id_user', 'password', 'password_confirmation', 'no_auth_seq'],
                        properties: [
                            new OA\Property(property: 'id_user', description: '회원 ID', type: 'string'),
                            new OA\Property(
                                property: 'password',
                                description: '비밀번호(영문+숫자/특수기호 or 영문+숫자+특수기호)',
                                type: 'string',
                                maxLength: 20,
                                minLength: 5,
                            ),
                            new OA\Property(
                                property: 'password_confirmation',
                                description: '비밀번호 확인',
                                type: 'string',
                                maxLength: 20,
                                minLength: 5,
                            ),
                            new OA\Property(property: 'no_auth_seq', description: '본인인증 식별 번호', type: 'string'),
                            new OA\Property(property: 'cd_phone_os', description: '휴대폰 OS 버전', type: 'string'),
                            new OA\Property(property: 'ds_phone_model', description: '휴데폰 모델', type: 'string'),
                            new OA\Property(property: 'ds_phone_version', description: '휴대폰 OS 버전 (103001: android, 103002:ios)', type: 'string', enum: [
                                '103001',
                                '103002'
                            ]),
                            new OA\Property(property: 'ds_phone_nation', description: '휴대폰 언어설정', type: 'string'),
                            new OA\Property(property: 'ds_phone_token', description: '휴대폰 토큰', type: 'string'),
                        ]
                    )
                )
            ]
        ),
        tags: ['member'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'success',
                content: new OA\JsonContent(properties: [new OA\Property(property: 'result', description: '성공 여부', type: 'bool')], type: 'object')
            ),
            new OA\Response(response: 500, description: 'api failed')
        ]
    )]
    public function regist()
    {
    }

    #[OA\Put(
        path: '/member',
        description: '회원 정보 수정',
        security: [['bearerAuth' => []]],
        requestBody: new OA\RequestBody(
            description: '',
            content: [
                new OA\MediaType(
                    mediaType: 'application/x-www-form-urlencoded',
                    schema: new OA\Schema(
                        required: ['no_user', 'password', 'password_confirmation'],
                        properties: [
                            new OA\Property(property: 'no_user', description: '회원 ID', type: 'integer'),
                            new OA\Property(
                                property: 'password',
                                description: '비밀번호(영문+숫자/특수기호 or 영문+숫자+특수기호)',
                                type: 'string',
                                maxLength: 20,
                                minLength: 5,
                            ),
                            new OA\Property(
                                property: 'password_confirmation',
                                description: '비밀번호 확인',
                                type: 'string',
                                maxLength: 20,
                                minLength: 5,
                            )
                        ]
                    )
                )
            ]
        ),
        tags: ['member'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'success',
                content: new OA\JsonContent(properties: [
                    new OA\Property(property: 'result', description: '성공 여부', type: 'bool')
                ], type: 'object')
            ),
            new OA\Response(response: 500, description: 'api failed')
        ]
    )]
    public function modify()
    {
    }

    #[OA\Put(
        path: '/member/withdrawal',
        description: '회원 탈퇴',
        security: [['bearerAuth' => []]],
        requestBody: new OA\RequestBody(
            description: '',
            content: [
                new OA\MediaType(
                    mediaType: 'application/x-www-form-urlencoded',
                    schema: new OA\Schema(
                        required: ['no_withdrawal'],
                        properties: [
                            new OA\Property(property: 'no_withdrawal', description: '회원 탈퇴 사유 번호 (1: 개인정보 우려 2: 서비스 불편, 3: 미사용, 4: 기타)', type: 'integer', enum: [
                                1,
                                2,
                                3,
                                4
                            ]),
                            new OA\Property(property: 'ds_withdrawal', description: '회원 탈퇴 사유', type: 'string', maxLength: 300)
                        ]
                    )
                )
            ]
        ),
        tags: ['member'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'success',
                content: new OA\JsonContent(properties: [
                    new OA\Property(property: 'result', description: '성공 여부', type: 'bool')
                ], type: 'object')
            ),
            new OA\Response(response: 500, description: 'api failed')
        ]
    )]
    public function withdrawal()
    {
    }

    #[OA\Get(
        path: '/member/qna',
        description: '회원 문의 리스트',
        security: [['bearerAuth' => []]],
        tags: ['member'],
        parameters: [
            new OA\Parameter(name: 'size', in: 'query', schema: new OA\Schema(type: 'integer',)),
            new OA\Parameter(name: 'offset', in: 'query', schema: new OA\Schema(type: 'integer',)),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'success',
                content: new OA\JsonContent(
                    ref: '#/components/schemas/MemberQnaListResponse'
                )
            ),
            new OA\Response(response: 500, description: 'api failed')
        ]
    )]
    public function getQnaList()
    {
    }

    #[OA\Post(
        path: '/member/qna',
        description: '회원 문의 등록',
        security: [['bearerAuth' => []]],
        requestBody: new OA\RequestBody(
            description: '',
            content: [
                new OA\MediaType(
                    mediaType: 'application/x-www-form-urlencoded',
                    schema: new OA\Schema(
                        required: ['ds_title', 'ds_content'],
                        properties: [
                            new OA\Property(property: 'ds_title', description: '제목', type: 'string'),
                            new OA\Property(property: 'ds_content', description: '내용', type: 'string')
                        ]
                    )
                )
            ]
        ),
        tags: ['member'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'success',
                content: new OA\JsonContent(properties: [new OA\Property(property: 'result', description: '성공 여부', type: 'bool')], type: 'object')
            ),
            new OA\Response(response: 500, description: 'api failed')
        ]
    )]
    public function registerQna()
    {
    }
}
