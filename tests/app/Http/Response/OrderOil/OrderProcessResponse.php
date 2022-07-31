<?php

declare(strict_types=1);

namespace Tests\app\Http\Response\OrderOil;

use OpenApi\Attributes as OA;

#[OA\Schema]
class OrderProcessResponse
{
    #[OA\Property(description: '메시지')]
    public string $message;

    #[OA\Property(description: '디테일 메시지')]
    public string $detail;

    #[OA\Property(description: '주문 상태 텍스트')]
    public string $nm_status;

    #[OA\Property(description: '주문 상태 코드')]
    public string $cd_order_process;

    #[OA\Property(ref: '#/components/schemas/OrderOilDetail', description: '주문 정보')]
    public OrderOilDetail $order;

    #[OA\Property(ref: '#/components/schemas/WashInShop', description: '주문 정보')]
    public WashInShop $wash_in_shop;
}

#[OA\Schema]
class WashInShop
{
    #[OA\Property(description: '매장 코드')]
    public string $no_shop;

    #[OA\Property(description: '매장명')]
    public string $nm_shop;
}

