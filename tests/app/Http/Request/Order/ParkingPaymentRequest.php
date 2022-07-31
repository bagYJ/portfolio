<?php

declare(strict_types=1);

namespace Tests\app\Http\Request\Order;

use OpenApi\Attributes as OA;

#[OA\Schema]
class ParkingPaymentRequest
{
    #[OA\Property(description: '주차장 고유번호')]
    public int $no_site;
    #[OA\Property(description: '티켓 고유번호')]
    public int $no_product;
    #[OA\Property(description: '결제 타입 (NORMAL: 일반방식)')]
    public string $cd_service_pay;
    #[OA\Property(description: '전체 주문금액')]
    public int $at_price_total;
    #[OA\Property(description: '쿠폰 할인 금액')]
    public int $at_cpn_disct;

    #[OA\Property(description: '카드 번호')]
    public int $no_card;
    #[OA\Property(description: '차 번호')]
    public string $car_number;

    #[OA\Property(ref: '#/components/schemas/DiscountInfo', description: '할인 정보')]
    public DiscountInfo $discount_info;
}