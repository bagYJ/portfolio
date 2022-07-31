<?php

declare(strict_types=1);

namespace Tests\app\Http\Response\Retail;

use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'RetailProductCheckResponse')]
class ProductCheckResponse
{
    #[OA\Property(description: '성공 여부')]
    public bool $result;
    #[OA\Property(description: '상품리스트', type: 'array', items: new OA\Items(
        ref: '#/components/schemas/StockProductList'
    ))]
    public StockProductList $products;
}

#[OA\Schema]
class StockProductList
{
    #[OA\Property(description: '상품번호')]
    public int $no_product;
    #[OA\Property(description: '재고')]
    public int $cnt_product;
    #[OA\Property(description: '옵션정보', type: 'array', items: new OA\Items(
        ref: '#/components/schemas/StockProductOptionList'
    ))]
    public StockProductOptionList $option;
}

#[OA\Schema]
class StockProductOptionList
{
    #[OA\Property(description: '옵션번호')]
    public int $no_option;
    #[OA\Property(description: '옵션재고')]
    public int $cnt_product;
}
