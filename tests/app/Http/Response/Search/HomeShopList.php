<?php

/**
 *
 */

declare(strict_types=1);

namespace Tests\app\Http\Response\Search;

use OpenApi\Attributes as OA;

#[OA\Schema]
class HomeShopList
{
    #[OA\Property(description: '성공 여부', example: true)]
    public bool $result;
    #[OA\Property(description: '상점 리스트', type: 'array', items: new OA\Items('#/components/schemas/HomeShopLists'))]
    public HomeShopLists $shopList;
}

#[OA\Schema]
class HomeShopLists
{
    #[OA\Property(description: '브랜드명 + 상점명')]
    public string $nm_shop;
    #[OA\Property(description: '상점번호')]
    public int $no_shop;
    #[OA\Property(description: '거리')]
    public float $distance;
    #[OA\Property(description: '상품', type: 'array', items: new OA\Items('#/components/schemas/HomeShopListsProduct'))]
    public HomeShopListsProduct $product;
}

#[OA\Schema]
class HomeShopListsProduct
{
    #[OA\Property(description: '상품명')]
    public string $nm_product;
    #[OA\Property(description: '상품가격')]
    public int $at_price;
    #[OA\Property(description: '할인전 상품가격')]
    public int $at_price_before;
    #[OA\Property(description: '상품 할인율')]
    public int $at_ratio;
    #[OA\Property(description: '상품번호')]
    public int $no_product;
    #[OA\Property(description: '상품이미지')]
    public string $ds_image_path;
    #[OA\Property(description: '인기여부')]
    public bool $populate;
}
