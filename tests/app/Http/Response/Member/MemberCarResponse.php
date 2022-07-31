<?php

declare(strict_types=1);

namespace Tests\app\Http\Response\Member;

use OpenApi\Attributes as OA;

#[OA\Schema]
class MemberCarResponse
{
    #[OA\Property(description: '성공 여부')]
    public bool $result;
    #[OA\Property(description: '보유 차량 리스트', type: 'array', items: new OA\Items(ref: '#/components/schemas/CarInfo'))]
    public CarInfo $car_info;
}

#[OA\Schema]
class CarInfo
{
    #[OA\Property(description: '보유차량 기본키')]
    public int $no;
    #[OA\Property(description: '차량번호')]
    public string $ds_car_number;
    #[OA\Property(description: '차량색상')]
    public ?string $ds_car_color;
    #[OA\Property(description: '유종 코드')]
    public string $cd_gas_kind;
    #[OA\Property(description: '유종')]
    public string $gas_kind;
    #[OA\Property(description: '메이커 번호')]
    public int $no_maker;
    #[OA\Property(description: '메이커')]
    public string $ds_maker;
    #[OA\Property(description: '차종 번호')]
    public int $seq;
    #[OA\Property(description: '차종')]
    public string $ds_kind;
    #[OA\Property(description: '메인차량여부')]
    public string $yn_main_car;
    #[OA\Property(description: '자동주차 등록여부(Y: 등록, N: 미등록)')]
    public string $yn_use_auto_parking;
    #[OA\Property(description: '차량수정 가능 여부')]
    public string $is_modify;
}
