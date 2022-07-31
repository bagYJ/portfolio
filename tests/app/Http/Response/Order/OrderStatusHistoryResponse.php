<?php

declare(strict_types=1);

namespace Tests\app\Http\Response\Order;

use OpenApi\Attributes as OA;

#[OA\Schema]
class OrderStatusHistoryResponse
{
    #[OA\Property(description: '성공 여부')]
    public bool $result;
    #[OA\Property(description: '주문 상태')]
    public string $order_status;
    #[OA\Property(description: '사용자 픽업 시각')]
    public string $dt_pickup;
    #[OA\Property(description: '예약완료 시각')]
    public string $order_reserve;
    #[OA\Property(description: '주문수락 시각')]
    public string $order_confirm;
    #[OA\Property(description: '준비완료 시각')]
    public string $order_ready;
    #[OA\Property(description: '픽업완료 시각')]
    public string $order_pickup;
    #[OA\Property(description: '도착알림 여부')]
    public bool $employee_call;
}
