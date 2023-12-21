<?php
declare(strict_types=1);

namespace App\Requests\Cu;

use App\Enums\CancelType;
use Illuminate\Http\Request;
use Owin\OwinCommonUtil\CodeUtil;
use OpenApi\Attributes as OA;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

#[OA\Schema(schema: 'request.cu.OrderCancel')]
class OrderCancel extends \App\Requests\Cu\Request
{
    #[OA\Property(description: '매장코드')]
    public readonly string $shop_code;
    #[OA\Property(description: '주문번호')]
    public readonly string $no_order;
    #[OA\Property(description: '주문명')]
    public readonly string $nm_order;
    #[OA\Property(description: '취소타입')]
    public readonly CancelType $cd_cancel_type;
    #[OA\Property(description: '주문일시')]
    public readonly string $dt_order;
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
        $this->nm_order = data_get($valid, 'nm_order');
        $this->cd_cancel_type = CancelType::tryFrom(data_get($valid, 'cd_cancel_type') ?? CancelType::MANAGER_CANCEL->value);
        $this->dt_order = data_get($valid, 'dt_order');
        $this->dt_pickup = data_get($valid, 'dt_pickup');
        parent::setSign(sprintf('%s%s%s%s', $this->partner_code, $this->shop_code, $this->no_order, $this->trans_dt));
    }
}
