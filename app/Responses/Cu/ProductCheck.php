<?php
declare(strict_types=1);

namespace App\Responses\Cu;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'response.cu.ProductCheck')]
class ProductCheck extends Response
{
    #[OA\Property(description: '상품별 재고수량 정보', type: 'array', items: new OA\Items(type: 'integer'))]
    public readonly array $product_list;

    public function __construct(array $response)
    {
        parent::__construct($response);
        $this->product_list = data_get($response, 'product_list');
    }
}
