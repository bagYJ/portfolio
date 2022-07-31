<?php

declare(strict_types=1);

namespace Tests\app\Http\Response\AutoParking;

use OpenApi\Attributes as OA;
use Tests\app\Http\Response\Order\CardInfo;

#[OA\Schema]
class AutoParkingGetsResponse
{
    #[OA\Property(description: '차량번호')]
    public string $ds_car_number;
    #[OA\Property(description: '유종코드')]
    public string $cd_gas_kind;
    #[OA\Property(description: '유종')]
    public string $gas_kind;
    #[OA\Property(description: '자동결제사용여부')]
    public string $yn_use_auto_parking;
    #[OA\Property(description: '제조사 코드')]
    public int $no_maker;
    #[OA\Property(description: '제조사 명')]
    public string $ds_maker;
    #[OA\Property(description: '차종명')]
    public string $ds_kind;
    #[OA\Property(description: '등록 카드 정보')]
    public CardInfo $card;
}

