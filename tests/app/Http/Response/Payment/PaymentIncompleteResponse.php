<?php

declare(strict_types=1);

namespace Tests\app\Http\Response\Payment;

use OpenApi\Attributes as OA;

#[OA\Schema]
class PaymentIncompleteResponse
{
    #[OA\Property(description: '성공 여부')]
    public bool $result;
    #[OA\Property(description: '결과 메시지')]
    public string $message;
}
