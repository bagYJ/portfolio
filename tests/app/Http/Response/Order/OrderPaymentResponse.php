<?php

declare(strict_types=1);

namespace Tests\app\Http\Response\Order;

use OpenApi\Attributes as OA;

#[OA\Schema]
class OrderPaymentResponse
{
    #[OA\Property(description: '성공 여부')]
    public bool $result;
    #[OA\Property(description: '주문번호')]
    public string $no_order;
    #[OA\Property(description: '결과 메시지')]
    public string $message;
    #[OA\Property(description: '상세 결과 메시지')]
    public string $detail_message;
}
