<?php

declare(strict_types=1);

namespace Tests\app\Http\Response\Main;

use OpenApi\Attributes as OA;

#[OA\Schema]
class MainResponse
{
    #[OA\Property(description: '성공 여부')]
    public bool $result;
    #[OA\Property(description: '이미지 도메인')]
    public string $image_path;
    #[OA\Property(description: '추천 메뉴', type: 'array', items: new OA\Items(
        ref: '#/components/schemas/RecommendProduct'
    ))]
    public RecommendProduct $recommend_product;
    #[OA\Property(description: '추천 매장', type: 'array', items: new OA\Items(ref: '#/components/schemas/RecommendShop'))]
    public RecommendShop $recommend_shop;
}

#[OA\Schema]
class RecommendProduct
{
    #[OA\Property(description: '상점번호')]
    public int $no_shop;
    #[OA\Property(description: '상품번호')]
    public int $no_product;
    #[OA\Property(description: '상품명')]
    public string $nm_product;
    #[OA\Property(description: '할인전 상품금액')]
    public int $at_price_before;
    #[OA\Property(description: '상품금액')]
    public int $at_price;
    #[OA\Property(description: '상품이미지')]
    public string $ds_image_path;
    #[OA\Property(description: '할인율')]
    public int $at_ratio;
}

#[OA\Schema]
class RecommendShop
{
    #[OA\Property(description: '상점번호')]
    public int $no_shop;
    #[OA\Property(description: '상점명')]
    public string $nm_shop;
    #[OA\Property(description: '브랜드명')]
    public string $nm_partner;
    #[OA\Property(description: '거리')]
    public float $distance;
    #[OA\Property(description: '상품정보')]
    public RecommendProduct $product;
}
