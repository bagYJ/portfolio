<?php

declare(strict_types=1);

namespace Tests\app\Http\Response\Retail;

use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'RetailProductListResponse')]
class ProductListResponse
{
    #[OA\Property(description: '성공 여부')]
    public bool $result;
    #[OA\Property(description: '상품리스트', type: 'array', items: new OA\Items(ref: '#/components/schemas/ProductList'))]
    public ProductList $products;
}

#[OA\Schema]
class ProductList
{
    #[OA\Property(description: '상품번호')]
    public int $no_product;
    #[OA\Property(description: '카테고리번호')]
    public int $no_category;
    #[OA\Property(description: '서브카테고리번호')]
    public ?int $no_sub_category;
    #[OA\Property(description: '상품명')]
    public string $nm_product;
    #[OA\Property(description: '할인전 상품금액')]
    public int $at_price_before;
    #[OA\Property(description: '상품금액')]
    public int $at_price;
    #[OA\Property(description: '상품이미지')]
    public string $ds_image_path;
    #[OA\Property(description: '상품 재고수량')]
    public int $cnt_product;
    #[OA\Property(description: '품절여부')]
    public string $yn_soldout;
    #[OA\Property(description: '할인율')]
    public int $at_ratio;
    #[OA\Property(description: '부분품절여부')]
    public string $yn_part_soldout;
}
