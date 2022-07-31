<?php

declare(strict_types=1);

namespace Tests\app\Http\Request\Payment;

use OpenApi\Attributes as OA;

#[OA\Schema]
class PaymentCancelRequest
{
    #[OA\Property(description: '주문번호')]
    public string $no_order;
    #[OA\Property(description: '주문 취소 코드')]
    public string $cd_reject_reason;
}
