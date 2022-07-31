<?php

namespace Tests\app\Http\Controllers;

use OpenApi\Attributes as OA;

#[OA\Info(
    version: '1.0.1',
    description: '오윈 api 문서',
    title: 'OWin API DOCUMENT'
)]
#[OA\Server(
    url: 'http://owinapi-dev.owinpay.com/v1',
    description: '개발서버 uri'
)]
#[OA\Server(
    url: 'http://localhost/v1',
    description: '로컬서버 uri'
)]
#[OA\Server(
    url: 'http://owinapi.owinpay.com/v1',
    description: '운영서버 uri'
)]
#[OA\Components(
    securitySchemes: [
        new OA\SecurityScheme(
            securityScheme: 'bearerAuth',
            type: 'http',
            bearerFormat: 'JWT',
            scheme: 'bearer'
        )
    ]
)]
#[OA\Tag(name: 'oauth', description: '인증')]
#[OA\Tag(name: 'search', description: '매장 검색')]
#[OA\Tag(name: 'product', description: '상품')]
#[OA\Tag(name: 'parking', description: '주차')]
#[OA\Tag(name: 'card', description: '카드')]
#[OA\Tag(name: 'coupon', description: '쿠폰')]
#[OA\Tag(name: 'customer', description: '이벤트, FAQ')]
#[OA\Tag(name: 'main', description: '메인')]
#[OA\Tag(name: 'notice', description: '공지사항')]
#[OA\Tag(name: 'order', description: '주문')]
#[OA\Tag(name: 'payment', description: '결제')]
#[OA\Tag(name: 'retail', description: '편의점')]
#[OA\Tag(name: 'shop', description: '상점')]
#[OA\Tag(name: 'cert', description: '본인인증')]
#[OA\Tag(name: 'order_oil', description: '주유')]
#[OA\Tag(name: 'member', description: '회원')]
#[OA\Tag(name: 'car', description: '자동차')]
#[OA\Tag(name: 'ev_charger', description: '전기자동차 충전소')]
#[OA\Tag(name: 'wash', description: '세차')]
#[OA\Tag(name: 'auto_parking', description: '자동결제주차')]
#[OA\Tag(name: 'apt', description: '아파트')]
#[OA\Tag(name: 'direct_order', description: '바로주문')]
#[OA\Tag(name: 'promotion', description: '프로모션')]
#[OA\Tag(name: 'version', description: '버전')]
class Controller
{
}
