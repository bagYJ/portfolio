<?php
declare(strict_types=1);

namespace App\Requests\Cu;

use App\Enums\RejectReason;
use App\Exceptions\ValidationHashException;
use Illuminate\Http\Request;
use Owin\OwinCommonUtil\CodeUtil;
use OpenApi\Attributes as OA;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

#[OA\Schema(schema: 'request.cu.OrderConfirm')]
class OrderConfirm extends \App\Requests\Cu\Request
{
    #[OA\Property(description: '매장코드')]
    public readonly string $shop_code;
    #[OA\Property(description: '주문번호')]
    public readonly string $no_order;
    #[OA\Property(description: '주문거부여부')]
    public readonly string $yn_cancel;
    #[OA\Property(description: '주문거부사유')]
    public readonly ?RejectReason $cd_reject_reason;
    #[OA\Property(description: '전송일시')]
    public string $trans_dt;
    public readonly Request $request;

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface|ValidationHashException
     */
    public function __construct()
    {
        $this->request = $this->makeRequest();
        $valid = $this->request->validate(config('rules')->get(__CLASS__));
        parent::__construct();

        $this->shop_code = data_get($valid, 'shop_code');
        $this->no_order = CodeUtil::convertOrderCodeToOwin(data_get($valid, 'no_order'));
        $this->yn_cancel = data_get($valid, 'yn_cancel');
        $this->cd_reject_reason = RejectReason::tryFrom(data_get($valid, 'cd_reject_reason') ?? RejectReason::ETC->name);
        $this->trans_dt = data_get($valid, 'trans_dt');
        parent::setSign(sprintf('%s%s%s%s', $this->partner_code, $this->shop_code, data_get($valid, 'no_order'), $this->trans_dt))
            ->hashCheck(data_get($valid, 'sign'));
    }
}
