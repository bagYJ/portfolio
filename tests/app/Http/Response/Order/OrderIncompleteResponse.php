<?php

declare(strict_types=1);

namespace Tests\app\Http\Response\Order;

use OpenApi\Attributes as OA;
use Tests\app\Http\Response\Card\CardList;
use Tests\app\Http\Response\OrderOil\Card;

#[OA\Schema]
class OrderIncompleteResponse
{


    #[OA\Property(description: '성공 여부')]
    public bool $result;

    #[OA\Property(description: '주문내역')]
    public Order $order;

    #[OA\Property(description: '카드 리스트')]
    public CardList $cards;
}

#[OA\Schema]
class Order
{
    #[OA\Property(description: '상점명')]
    public string $nm_shop;
    #[OA\Property(description: '주문번호')]
    public string $no_order;
    #[OA\Property(description: '회원 노출용 주문번호')]
    public string $no_order_user;
    #[OA\Property(description: '업종')]
    public string $biz_kind;
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
    #[OA\Property(description: '결제금액')]
    public int $at_price;
    #[OA\Property(description: 'pg결제금액')]
    public int $at_price_pg;
    #[OA\Property(description: '주문상태코드')]
    public string $cd_status;
    #[OA\Property(description: '주문상태')]
    public string $nm_status;
    #[OA\Property(description: '상점번호')]
    public int $no_shop;
    #[OA\Property(description: '주차 상점번호')]
    public int $no_site;
}
