<?php

declare(strict_types=1);

namespace Tests\app\Http\Response\AutoParking;

use OpenApi\Attributes as OA;
use Tests\app\Http\Response\Order\CardInfo;

#[OA\Schema]
class AutoParkingCheckPaymentResponse
{
    #[OA\Property(description: '상태')]
    public bool $result;

    #[OA\Property(description: '주문정보')]
    public AutoParkingOrder $order;

    #[OA\Property(description: '카드 정보', type: 'array', items: new OA\Items(ref: '#/components/schemas/CardInfo'))]
    public CardInfo $cards;
}

#[OA\Schema]
class AutoParkingOrder
{
    #[OA\Property(description: '주문번호')]
    public string $no_order;
    #[OA\Property(description: '주문명')]
    public string $nm_order;
    #[OA\Property(description: '매장번호')]
    public int $no_site;
    #[OA\Property(description: '차량번호')]
    public string $ds_car_number;
    #[OA\Property(description: '입차일시')]
    public string $dt_entry_time;
    #[OA\Property(description: '출차일시')]
    public string $dt_exit_time;
    #[OA\Property(description: '주차시간')]
    public string $parking_time;
    #[OA\Property(description: '결제금액')]
    public string $at_price;
    #[OA\Property(description: '카드회사코드')]
    public string $cd_card_corp;
    #[OA\Property(description: '카드회사')]
    public string $card_corp;
    #[OA\Property(description: '카드 뒷4자리')]
    public string $no_card_user;
}

