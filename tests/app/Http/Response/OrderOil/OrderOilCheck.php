<?php

declare(strict_types=1);

namespace Tests\app\Http\Response\OrderOil;

use OpenApi\Attributes as OA;

#[OA\Schema]
class OrderOilCheck
{
    #[OA\Property(description: '매장 도착 메시지')]
    public bool $message;
}

