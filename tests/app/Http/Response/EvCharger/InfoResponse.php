<?php

declare(strict_types=1);

namespace Tests\app\Http\Response\EvCharger;

use OpenApi\Attributes as OA;

#[OA\Schema]
class InfoResponse
{
    #[OA\Property]
    public bool $result;

    #[OA\Property(description: '충전소 정보', type: 'array', items: new OA\Items(ref: '#/components/schemas/Info'))]
    public Info $item;
}

#[OA\Schema]
class Info
{
    #[OA\Property(description: '충전소 ID')]
    public string $id_stat;
    #[OA\Property(description: '충전소 명')]
    public string $nm_stat;
    #[OA\Property(description: '이용자 제한 여부 (N:제한 없음)')]
    public string $yn_limit;
    #[OA\Property(description: '운영기관 연락처')]
    public string $ds_busi_tel;
    #[OA\Property(description: '충전소 주소')]
    public string $ds_addr;
    #[OA\Property(description: '이용가능시간')]
    public string $ds_use_time;
    #[OA\Property(description: '위도')]
    public float $ds_lat;
    #[OA\Property(description: '경도')]
    public float $ds_lng;
    #[OA\Property(description: 'Kw당 금액')]
    public float $at_ev_price;
    #[OA\Property(description: '충전소 안내')]
    public string $ds_note;
    #[OA\Property(description: '이용제한 사유')]
    public string $ds_limit;
    #[OA\Property(description: '충전기 목록', type: 'array', items: new OA\Items(ref: '#/components/schemas/Machine'))]
    public Machine $items;
}

#[OA\Schema]
class Machine
{
    #[OA\Property(description: '충전기 ID')]
    public string $id_chger;
    #[OA\Property(description: '충전기 상태 (1: 통신이상, 2: 충전대기, 3: 충전중, 4: 운영중지, 5: 점검중, 9: 상태미확인)')]
    public string $cd_chger_stat;
    #[OA\Property(description: '충전기 타입 (01: DC차데모,
    02: AC완속,
    03: DC차데모+AC3상,
    04: DC콤보,
    05: DC차데모+DC콤보,
    06: DC차데모+AC3상+DC콤보,
    07: AC3상)')]
    public string $cd_chger_type;
    #[OA\Property(description: '충전용량 (완속 : 3, 7 / 급속 : 50 / 초급속 : 100, 200)')]
    public string $ds_output;
    #[OA\Property(description: '충전방식')]
    public string $ds_method;
}

