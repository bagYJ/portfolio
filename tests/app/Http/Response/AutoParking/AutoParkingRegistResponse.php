<?php

declare(strict_types=1);

namespace Tests\app\Http\Response\AutoParking;

use OpenApi\Attributes as OA;

#[OA\Schema]
class AutoParkingRegistResponse
{
    #[OA\Property(description: '상태')]
    public bool $result;
    #[OA\Property(description: '결과메시지')]
    public string $message;
    #[OA\Property(description: '차량번호')]
    public string $ds_car_number;
}

