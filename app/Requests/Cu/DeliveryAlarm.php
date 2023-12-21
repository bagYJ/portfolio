<?php
declare(strict_types=1);

namespace App\Requests\Cu;

use Illuminate\Http\Request;
use Owin\OwinCommonUtil\CodeUtil;
use OpenApi\Attributes as OA;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

#[OA\Schema(schema: 'request.cu.DeliveryAlarm')]
class DeliveryAlarm extends \App\Requests\Cu\Request
{
    #[OA\Property(description: '매장코드')]
    public readonly string $shop_code;
    #[OA\Property(description: '주문번호')]
    public readonly string $no_order;
    #[OA\Property(description: '픽업일시')]
    public readonly string $dt_pickup;

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __construct(Request $request)
    {
        $valid = $request->validate(config('rules')->get(__CLASS__));
        parent::__construct();

        $this->shop_code = data_get($valid, 'shop_code');
        $this->no_order = CodeUtil::convertOrderCodeToCuSpc(data_get($valid, 'no_order'));
        $this->dt_pickup = data_get($valid, 'dt_pickup');
        parent::setSign(sprintf('%s%s%s%s', $this->partner_code, $this->shop_code, $this->no_order, $this->trans_dt));
    }
}
