<?php

declare(strict_types=1);

namespace Tests\app\Http\Request\AutoParking;

use OpenApi\Attributes as OA;

#[OA\Schema]
class AutoParkingPaymentRequest
{
    #[OA\Property(description: '차량번호', nullable: false)]
    public string $no_order;
    #[OA\Property(description: '카드번호', nullable: false)]
    public string $no_card;
}
