<?php

declare(strict_types=1);

namespace Tests\app\Http\Response\OrderOil;

use OpenApi\Attributes as OA;

#[OA\Schema]
class OrderOilPayment
{
    #[OA\Property(description: '결과')]
    public bool $result;
    #[OA\Property(description: '주문 번호')]
    public string $no_order;
}

