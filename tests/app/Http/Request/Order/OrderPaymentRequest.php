<?php

declare(strict_types=1);

namespace Tests\app\Http\Request\Order;

use OpenApi\Attributes as OA;

#[OA\Schema]
class OrderPaymentRequest
{
    #[OA\Property(description: '주문 타입 (PICKUP: 픽업)')]
    public string $cd_service;
    #[OA\Property(description: '결제 타입 (NORMAL: 일반방식)')]
    public string $cd_service_pay;
    #[OA\Property(description: '픽업 타입 (CAR: 차안픽업, SHOP: 매장픽업)')]
    public string $pickup_type;
    #[OA\Property(description: '매장번호')]
    public int $no_shop;
    #[OA\Property(description: '주문 금액')]
    public int $at_price_total;
    #[OA\Property(description: '최종결제금액', nullable: false)]
    public int $at_price_calc;
    #[OA\Property(description: '카드 번호')]
    public int $no_card;
    #[OA\Property(description: '차 번호')]
    public string $car_number;
    #[OA\Property(description: '도착시간')]
    public string $arrived_time;
    #[OA\Property(description: '커미션 (일단 세팅이 되어있어서 init에서 받은 값으로 보내주세요)')]
    public int $at_commission_rate;
    #[OA\Property(description: '전달비 (리테일에서 사용)')]
    public int $at_send_price;
    #[OA\Property(description: '쿠폰 할인 금액')]
    public int $at_cpn_disct;
    #[OA\Property(description: '요청사항')]
    public string $ds_request_msg;

    #[OA\Property(ref: '#/components/schemas/DiscountInfo', description: '할인 정보')]
    public DiscountInfo $discount_info;

    #[OA\Property(description: '주문 상품', type: 'array', items: new OA\Items(
        ref: '#/components/schemas/OrderProduct'
    ), nullable: false)]
    public OrderProduct $list_product;
}

#[OA\Schema]
class DiscountInfo
{
    #[OA\Property(ref: '#/components/schemas/CouponInfoRequest', description: '쿠폰 할인 정보')]
    public CouponInfo $coupon;

    #[OA\Property(ref: '#/components/schemas/PointCardInfo', description: '포인트 카드 정보')]
    public PointCardInfo $point_card;
}

#[OA\Schema(schema: 'CouponInfoRequest')]
class CouponInfo
{
    #[OA\Property(description: '쿠폰번호')]
    public int $no;
    #[OA\Property(description: '쿠폰 할인 금액')]
    public int $at_coupon;
}

#[OA\Schema(schema: 'PointCardInfo')]
class PointCardInfo
{
    #[OA\Property(description: '포인트카드 번호')]
    public int $id;
    #[OA\Property(description: '리터당 할인금액')]
    public int $at_disct_price;
    #[OA\Property(description: '총 포인트 할인금액')]
    public int $at_point_disct;
}
