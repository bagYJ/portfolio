<?php

declare(strict_types=1);

namespace Tests\app\Http\Response\Product;

use OpenApi\Attributes as OA;

#[OA\Schema]
class ProductInfo
{
    #[OA\Property(description: '성공 여부')]
    public bool $result;
    #[OA\Property(description: '상점 번호')]
    public int $no_shop;
    #[OA\Property(ref: '#/components/schemas/Product')]
    public Product $product;
}
