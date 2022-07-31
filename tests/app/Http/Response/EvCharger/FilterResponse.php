<?php

declare(strict_types=1);

namespace Tests\app\Http\Response\EvCharger;

use OpenApi\Attributes as OA;

#[OA\Schema]
class FilterResponse
{
    #[OA\Property]
    public bool $result;

    #[OA\Property(description: '검색 필터', type: 'array', items: new OA\Items(ref: '#/components/schemas/Filter'))]
    public Filter $filter;
}

#[OA\Schema]
class Filter
{
    #[OA\Property(description: '충전기 타입 (01: DC차데모,
    02: AC완속,
    03: DC차데모+AC3상,
    04: DC콤보,
    05: DC차데모+DC콤보,
    06: DC차데모+AC3상+DC콤보,
    07: AC3상)')]
    public string $type;
    #[OA\Property(description: '운영기관 명 (EV: 에버온,
    KP: 한국전력공사,
    PW: 파워큐브,
    PI: 차지비,
    ME: 환경부,
    GN: 지커넥트,
    HE: 한국전기차충전서비스,
    SF: 스타코프,
    ST: 에스트래픽,
    EP: 이카플러그,
    CV: 대영채비,
    KL: 클린일렉스,
    JE: 제주전기자동차서비스,
    HM: 휴맥스이브이,
    KE: 한국전기차인프라기술,
    TD: 타디스테크놀로지,
    EZ: 차지인,
    JD: 제주특별자치도,
    LH: LG헬로비전,
    SS: 삼성EVC)')]
    public string $company;
    #[OA\Property(description: '유료/무료', enum: ['Y', 'N'])]
    public string $parking_free_yn;
}
