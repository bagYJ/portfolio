<?php
declare(strict_types=1);

namespace App\Requests\Cu;

use App\Exceptions\ValidationHashException;
use Owin\OwinCommonUtil\CodeUtil;
use OpenApi\Attributes as OA;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

#[OA\Schema(schema: 'request.cu.OrderReady')]
class OrderReady extends Request
{
    #[OA\Property(description: '매장코드')]
    public readonly string $shop_code;
    #[OA\Property(description: '주문번호')]
    public readonly string $no_order;
    #[OA\Property(description: '전송일시')]
    public string $trans_dt;

    /**
     * @throws ContainerExceptionInterface
     * @throws ValidationHashException
     * @throws NotFoundExceptionInterface
     */
    public function __construct()
    {
        $valid = $this->makeRequest()->validate(config('rules')->get(__CLASS__));
        parent::__construct();

        $this->shop_code = data_get($valid, 'shop_code');
        $this->no_order = CodeUtil::convertOrderCodeToOwin(data_get($valid, 'no_order'));
        $this->trans_dt = data_get($valid, 'trans_dt');
        parent::setSign(sprintf('%s%s%s%s', $this->partner_code, $this->shop_code, data_get($valid, 'no_order'), $this->trans_dt))
            ->hashCheck(data_get($valid, 'sign'));
    }
}
