<?php


declare(strict_types=1);

namespace Tests\app\Http\Response\Parking;

use OpenApi\Attributes as OA;
use Tests\app\Http\Response\OrderOil\Card;
use Tests\app\Http\Response\OrderOil\Coupon;
use Tests\app\Http\Response\OrderOil\MemberCarInfo;

#[OA\Schema]
class ParkingIntroResponse
{
    #[OA\Property(description: '이전 주문 번호')]
    public string $no_order;
    #[OA\Property(description: '사용자 차량 정보')]
    public MemberCarInfo $cars;
    #[OA\Property(description: '사용 가능 카드 정보')]
    public Card $cards;
    #[OA\Property(description: '사용 가능 쿠폰 정보')]
    public Coupon $coupons;
}
