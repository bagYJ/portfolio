<?php

declare(strict_types=1);

namespace Tests\app\Http\Response\DirectOrder;

use OpenApi\Attributes as OA;

#[OA\Schema]
class DirectOrderResponse
{
    #[OA\Property(description: '성공 여부')]
    public bool $result;
    #[OA\Property(description: '바로주문 등록갯수')]
    public int $count;
    #[OA\Property(description: '주문상품', type: 'array', items: new OA\Items(ref: '#/components/schemas/DirectOrderInfo'))]
    public DirectOrderInfo $rows;
}

#[OA\Schema]
class DirectOrderInfo
{
    #[OA\Property(description: '바로주문 키')]
    public int $no;
    #[OA\Property(description: '업종코드')]
    public string $cd_biz_kind;
    #[OA\Property(description: '업종')]
    public string $biz_kind;
    #[OA\Property(description: '주차 매장 번호')]
    public int $no_site;
    #[OA\Property(description: 'fnb, 리테일, 주유 매장 번호')]
    public int $no_shop;
    #[OA\Property(description: '주문금액')]
    public int $at_price_total;
    #[OA\Property(description: '주문상품명')]
    public int $nm_order;
    #[OA\Property(description: '매장명')]
    public int $nm_shop;
    #[OA\Property(description: '주문상품', type: 'array', items: new OA\Items(ref: '#/components/schemas/DirectOrderProduct'))]
    public DirectOrderProduct $list_product;
}

#[OA\Schema]
class DirectOrderProduct
{
    #[OA\Property(description: '상품번호', nullable: false)]
    public int $no_product;
    #[OA\Property(description: '카테고리번호', nullable: false)]
    public int $category;
    #[OA\Property(description: '주문갯수', nullable: false)]
    public int $ea;
    #[OA\Property(description: '2+1 상품타입 (SINGLE: 단품, DOUBLE: 2+1)', nullable: true)]
    public ?string $discount_type;
    #[OA\Property(description: '상품금액', nullable: false)]
    public int $at_price;
    #[OA\Property(description: '구매가능여부', nullable: false)]
    public bool $is_buy;
    #[OA\Property(description: '상품옵션', type: 'array', items: new OA\Items(ref: '#/components/schemas/DirectOrderProductOption'))]
    public DirectOrderProductOption $option;
}

#[OA\Schema]
class DirectOrderProductOption
{
    #[OA\Property(description: '옵션그룹번호')]
    public int $no_option_group;
    #[OA\Property(description: '옵션번호')]
    public int $no_option;
    #[OA\Property(description: '옵션금액')]
    public int $add_price;
    #[OA\Property(description: '구매가능여부', nullable: false)]
    public bool $is_buy;
}