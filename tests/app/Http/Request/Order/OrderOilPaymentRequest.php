<?php

declare(strict_types=1);

namespace Tests\app\Http\Request\Order;

use OpenApi\Attributes as OA;

#[OA\Schema]
class OrderOilPaymentRequest
{
    #[OA\Property(description: '매장번호')]
    public int $no_shop;
    #[OA\Property(description: '결제 타입 (NORMAL: 일반방식)')]
    public string $cd_service_pay;
    #[OA\Property(description: '주문 금액')]
    public int $at_price;
    #[OA\Property(description: '유종 종류')]
    public string $cd_gas_kind;
    #[OA\Property(description: '카드 번호')]
    public int $no_card;
    #[OA\Property(description: '차 번호')]
    public string $car_number;
    #[OA\Property(description: '리터/금액 주유 타입')]
    public string $order_type;
    #[OA\Property(description: '리터당 금액')]
    public int $at_gas_price;

    #[OA\Property(description: '주유될 리터량', nullable: true)]
    public int $at_liter_gas;

    #[OA\Property(description: '쿠폰 할인 금액')]
    public int $at_cpn_disct;
    #[OA\Property(description: '캐시 사용 금액')]
    public int $at_owin_cash;
    #[OA\Property(description: '포인트 할인 금액')]
    public int $at_point_disct;

    #[OA\Property(description: 'gps 상태 (Y/N)')]
    public string $yn_gps_status;

    #[OA\Property(description: '주문방식(CARID: CARID주문, QR: QR주문, NUMBER_INPUT: 번호입력주문)')]
    public string $cd_booking_type;

    #[OA\Property(description: '할인 정보', nullable: true)]
    public DiscountInfo $discount_info;
}