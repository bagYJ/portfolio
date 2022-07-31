<?php

declare(strict_types=1);

namespace Tests\app\Http\Request\AutoParking;

use OpenApi\Attributes as OA;

#[OA\Schema]
class AutoParkingRegistRequest
{
    #[OA\Property(description: '자동결제 상태(Y:등록/N:해제)', enum: ['Y', 'N'],nullable: false)]
    public string $yn_use_auto_parking;
    #[OA\Property(description: '차량번호', nullable: false)]
    public string $ds_car_number;
    #[OA\Property(description: '카드번호', nullable: false)]
    public string $no_card;
}
