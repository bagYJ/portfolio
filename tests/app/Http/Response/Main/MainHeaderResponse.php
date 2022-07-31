<?php

declare(strict_types=1);

namespace Tests\app\Http\Response\Main;

use OpenApi\Attributes as OA;

#[OA\Schema]
class MainHeaderResponse
{
    #[OA\Property(description: '성공 여부')]
    public bool $result;
    #[OA\Property(description: '텍스트')]
    public string $text;
//    #[OA\Property(description: '이동 (FNB: 식사/음료, OIL: 주유, WASH: 세차, PARKING: 주차, RETAIL: 편의점/마트, CHARGE: 충전, MAINTENANCE: 정비, VALLET: 발렛)', enum: ['FNB', 'OIL', 'WASH', 'PARKING', 'RETAIL', 'CHARGE', 'MAINTENANCE', 'VALLET'])]
//    public string $target;

    #[OA\Property(description: '업종구분코드 (201100: 음료, 201200: 음식, 201300: 주유, 201400: 생필품, 201500: 파킹, 201510: 발렛, 201600: 세차, 201610: 정비, 201700: 톨링, 201800: 리테일, 201998: OWIN_테스트매장, 201999: 오윈)'
        , enum: [
            '201100',
            '201200',
            '201300',
            '201400',
            '201500',
            '201510',
            '201600',
            '201610',
            '201700',
            '201800',
            '201998',
            '201999'
        ])]
    public string $cd_biz_kind;
    #[OA\Property(description: '앱링크')]
    public string $app_route;
}
