<?php
declare(strict_types=1);

namespace Tests\app\Http\Response\Wash;

use OpenApi\Attributes as OA;
use Tests\app\Http\Response\OrderOil\Card;
use Tests\app\Http\Response\OrderOil\Coupon;
use Tests\app\Http\Response\OrderOil\MemberCarInfo;

#[OA\Schema]
class WashIntroResponse
{
    #[OA\Property(description: '이전 주문 번호')]
    public string $no_order;
    #[OA\Property(description: '사용자 차량 정보')]
    public MemberCarInfo $cars;
    #[OA\Property(description: '사용 가능 카드 정보')]
    public Card $cards;
    #[OA\Property(description: '사용 가능 쿠폰 정보')]
    public Coupon $coupons;
    #[OA\Property(description: '상품 정보')]
    public WashProduct $wash_products;
}

#[OA\Schema]
class WashProduct
{
    #[OA\Property(description: '관리 번호')]
    public int $no;
    #[OA\Property(description: '상품 번호')]
    public int $no_product;
    #[OA\Property(description: '매장 번호')]
    public int $no_shop;
    #[OA\Property(description: '상품명')]
    public static $nm_product;
    #[OA\Property(description: '금액')]
    public int $at_price;
    #[OA\Property(description: '차량 종류')]
    public int $cd_car_kind;
    #[OA\Property(description: '사용 여부')]
    public int $yn_status;

    #[OA\Property(description: '상품 옵션')]
    public WashProductOption $wash_product_option;
}

#[OA\Schema]
class WashProductOption
{
    #[OA\Property(description: '관리 번호')]
    public int $no;
    #[OA\Property(description: '옵션 번호')]
    public int $no_option;
    #[OA\Property(description: '매장 번호')]
    public int $no_shop;
    #[OA\Property(description: '상품 번호')]
    public int $no_product;
    #[OA\Property(description: '옵션명')]
    public static $nm_option;
    #[OA\Property(description: '금액')]
    public int $at_price;
    #[OA\Property(description: '사용 여부')]
    public int $yn_status;
}