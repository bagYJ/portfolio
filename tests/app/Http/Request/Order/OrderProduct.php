<?php

declare(strict_types=1);

namespace Tests\app\Http\Request\Order;

use OpenApi\Attributes as OA;

#[OA\Schema]
class OrderProduct
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
    #[OA\Property(description: '상품옵션', type: 'array', items: new OA\Items(ref: '#/components/schemas/OrderProductOption'))]
    public OrderProductOption $option;
}

#[OA\Schema]
class OrderProductOption
{
    #[OA\Property(description: '옵션그룹번호')]
    public int $no_option_group;
    #[OA\Property(description: '옵션번호')]
    public int $no_option;
    #[OA\Property(description: '옵션금액')]
    public int $add_price;
}
