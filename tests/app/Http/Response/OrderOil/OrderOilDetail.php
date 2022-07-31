<?php

declare(strict_types=1);

namespace Tests\app\Http\Response\OrderOil;

use OpenApi\Attributes as OA;
use Tests\app\Http\Response\Order\ListProduct;

#[OA\Schema]
class OrderOilDetail
{
    #[OA\Property(description: '성공 여부')]
    public bool $result;
    #[OA\Property(description: '상점 코드')]
    public int $no_shop;
    #[OA\Property(description: '상점명')]
    public string $nm_shop;
    #[OA\Property(description: '주문번호')]
    public string $no_order;
    #[OA\Property(description: '회원 노출용 주문번호')]
    public string $no_order_user;
    #[OA\Property(description: '주문명')]
    public string $nm_order;
    #[OA\Property(description: '주문일시')]
    public string $dt_reg;
    #[OA\Property(description: '매장수수료율')]
    public int $at_commission_rate;
    #[OA\Property(description: '전달비')]
    public int $at_send_price;
    #[OA\Property(description: '오윈상시할인금액')]
    public int $at_disct;
    #[OA\Property(description: '쿠폰할인금액')]
    public int $at_cpn_disct;
    #[OA\Property(description: '유종')]
    public string $cd_gas_kind;
    #[OA\Property(description: '리터당 금액')]
    public int $at_gas_price;
    #[OA\Property(description: '실제 주유 리터')]
    public int $at_liter_real;
    #[OA\Property(description: '리터 주유 여부')]
    public string $yn_gas_order_liter;
    #[OA\Property(description: '결제금액')]
    public int $at_price;
    #[OA\Property(description: 'pg결제금액')]
    public int $at_price_pg;
    #[OA\Property(description: '주문상태코드')]
    public string $cd_status;
    #[OA\Property(description: '주문상태')]
    public string $nm_status;
    #[OA\Property(description: '사용자 메시지')]
    public string $current_msg;
    #[OA\Property(description: '프리셋여부')]
    public string $yn_preset;
    #[OA\Property(description: '주문상품', type: 'array', items: new OA\Items(ref: '#/components/schemas/ListProduct'))]
    public ListProduct $list_product;
}

