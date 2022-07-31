<?php

declare(strict_types=1);

namespace Tests\app\Http\Response\OrderOil;

use OpenApi\Attributes as OA;

#[OA\Schema]
class OrderOilPresetCheck
{
    #[OA\Property(description: '상태 업데이트 여부')]
    public string $yn_update;
    #[OA\Property(description: '노즐 상태')]
    public string $nizzle_status;
    #[OA\Property(description: '주문 상태')]
    public string $cd_order_process;
    #[OA\Property(description: '셀프 주유소 여부')]
    public string $yn_self;
    #[OA\Property(description: '프리셋 여부')]
    public string $yn_preset;
    #[OA\Property(description: '오류 메시지')]
    public string $message;
}
