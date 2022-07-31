<?php

declare(strict_types=1);

namespace Tests\app\Http\Response\Coupon;

use OpenApi\Attributes as OA;

#[OA\Schema]
class CouponListResponse
{
    #[OA\Property(description: '성공 여부')]
    public bool $result;
    #[OA\Property(description: '쿠폰리스트', type: 'array', items: new OA\Items(ref: '#/components/schemas/CouponList'))]
    public CouponList $coupon_list;
}

#[OA\Schema]
class CouponList
{
    #[OA\Property(description: '쿠폰번호')]
    public int $no;
    #[OA\Property(description: '쿠폰 타입 (FNB: fnb, RETAIL: 편의점, OIL: 주유소)')]
    public string $coupon_type;
    #[OA\Property(description: '쿠폰 타입명')]
    public string $coupon_type_nm;
    #[OA\Property(description: '쿠폰명')]
    public int $coupon_nm;
    #[OA\Property(description: '사용 가능 브랜드')]
    public int $available_partner;
    #[OA\Property(description: '사용 가능 매장')]
    public int $available_shop;
    #[OA\Property(description: '사용 가능 카드사')]
    public int $available_card;
    #[OA\Property(description: '사용 가능 요일')]
    public int $available_weekday;
    #[OA\Property(description: '사용 가능 카테고리')]
    public int $available_category;
    #[OA\Property(description: '사용 가능 상품')]
    public int $available_product;
    #[OA\Property(description: '할인 금액')]
    public int $at_discount;
    #[OA\Property(description: '쿠폰 할인 타입')]
    public int $discount_type;
    #[OA\Property(description: '이상 결제시 사용 가능')]
    public int $at_price_limit;
    #[OA\Property(description: '최대 사용 가능 금액')]
    public int $at_max_disc;
    #[OA\Property(description: '만료일')]
    public int $expire_date;
}
