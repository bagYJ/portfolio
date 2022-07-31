<?php

declare(strict_types=1);

namespace Tests\app\Http\Response\Product;

use OpenApi\Attributes as OA;

#[OA\Schema]
class GetList
{
    #[OA\Property(description: '성공 여부')]
    public bool $result;
    #[OA\Property(description: '상품 갯수', example: 100)]
    public int $product_count;
    #[OA\Property(type: 'array', items: new OA\Items(ref: '#/components/schemas/Product'))]
    public Product $products;
}
