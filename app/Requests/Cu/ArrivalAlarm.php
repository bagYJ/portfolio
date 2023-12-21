<?php
declare(strict_types=1);

namespace App\Requests\Cu;

use Illuminate\Http\Request;
use Owin\OwinCommonUtil\CodeUtil;
use OpenApi\Attributes as OA;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

#[OA\Schema(schema: 'request.cu.ArrivalAlarm')]
class ArrivalAlarm extends \App\Requests\Cu\Request
{
    #[OA\Property(description: '매장코드')]
    public readonly string $shop_code;
    #[OA\Property(description: '주문번호')]
    public readonly string $no_order;
    #[OA\Property(description: '주문명')]
    public readonly string $nm_order;
    #[OA\Property(description: '매장도착완료 여부')]
    public readonly string $yn_complete;
    #[OA\Property(description: '도착지점간의 거리')]
    public readonly float $at_distance;
    #[OA\Property(description: '주문일시')]
    public readonly string $dt_order;
    #[OA\Property(description: '픽업일시')]
    public readonly string $dt_pickup;
    #[OA\Property(description: '주문건 픽업방식: PU(pickup), DV(delivery)')]
    public readonly string $ds_pickup_type;
    #[OA\Property(description: '회원 닉네임')]
    public readonly ?string $nm_nick;
    #[OA\Property(description: '회원 연락처')]
    public readonly ?string $ds_phone;
    #[OA\Property(description: '회원차량정보')]
    public readonly ?string $ds_car_number;

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
        $this->yn_complete = data_get($valid, 'yn_complete');
        $this->at_distance = (float)data_get($valid, 'at_distance');
        $this->dt_order = data_get($valid, 'dt_order');
        $this->dt_pickup = data_get($valid, 'dt_pickup');
        $this->ds_pickup_type = data_get($valid, 'ds_pickup_type');
        $this->nm_nick = data_get($valid, 'nm_nick');
        $this->ds_phone = data_get($valid, 'ds_phone');
        $this->ds_car_number = data_get($valid, 'ds_car_number');
        parent::setSign(sprintf('%s%s%s%s', $this->partner_code, $this->shop_code, $this->no_order, $this->trans_dt));
    }
}
