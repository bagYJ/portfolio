<?php

declare(strict_types=1);

namespace Tests\app\Http\Request\Order;

use OpenApi\Attributes as OA;

#[OA\Schema]
class OrderInitRequest
{
    #[OA\Property(description: '서비스타입 (일단 PICKUP만 사용)', enum: ['PICKUP', 'PARKING', 'FOOD'], nullable: false)]
    public string $cd_service;
    #[OA\Property(description: '상점번호', nullable: false)]
    public int $no_shop;
    #[OA\Property(description: '결제금액', nullable: false)]
    public int $at_price_total;
    #[OA\Property(description: '주문 상품', type: 'array', items: new OA\Items(ref: '#/components/schemas/OrderProduct'), nullable: false)]
    public OrderProduct $list_product;
}
