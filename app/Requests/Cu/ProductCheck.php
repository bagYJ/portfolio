<?php
declare(strict_types=1);

namespace App\Requests\Cu;

use Illuminate\Http\Request;
use OpenApi\Attributes as OA;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

#[OA\Schema(schema: 'request.cu.ProductCheck')]
class ProductCheck extends \App\Requests\Cu\Request
{
    #[OA\Property(description: '매장코드')]
    public readonly string $shop_code;
    #[OA\Property(description: '상품별 재고수량 정보', type: 'array', items: new OA\Items(type: 'integer'))]
    public readonly array $product_list;

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __construct(Request $request)
    {
        $valid = $request->validate(config('rules')->get(__CLASS__));
        parent::__construct();


        $this->shop_code = data_get($valid, 'shop_code');
        $this->product_list = data_get($valid, 'product_list');
        parent::setSign(sprintf('%s%s%s', $this->partner_code, $this->shop_code, $this->trans_dt));
    }
}
