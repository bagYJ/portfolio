<?php
declare(strict_types=1);

namespace App\Requests\Infine;

use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'request.infine.Approval', description: '')]
class Approval
{
    #[OA\Property(description: '주문번호')]
    public readonly string $noOrder;
    #[OA\Property(description: '인파인 주문번호')]
    public readonly string $infineOrder;
    #[OA\Property(description: '차량 번호')]
    public readonly string $carNumber;
    #[OA\Property(description: '승인요청금액')]
    public readonly float $atPricePg;
    #[OA\Property(description: '카드 번호')]
    public readonly int $noCard;
    #[OA\Property(description: '빌링키')]
    public readonly string $dsBillkey;
    #[OA\Property(description: '보너스카드 번호')]
    public readonly ?int $idPointcard;
    #[OA\Property(description: '쿠폰번호')]
    public readonly ?int $noCoupon;

    public function __construct(Request $request)
    {
        $valid = $request->validate(config('rules')->get(__CLASS__));

        $this->noOrder = data_get($valid, 'no_order');
        $this->infineOrder = data_get($valid, 'infine_order');
        $this->carNumber = data_get($valid, 'car_number');
        $this->atPricePg = (float)data_get($valid, 'at_price_pg');
        $this->noCard = (int)data_get($valid, 'no_card');
        $this->dsBillkey = data_get($valid, 'ds_billkey');
        $this->idPointcard = (int)data_get($valid, 'id_pointcard');
        $this->noCoupon = (int)data_get($valid, 'no_coupon');
    }
}
