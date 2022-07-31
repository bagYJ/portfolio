<?php

declare(strict_types=1);

namespace Tests\app\Http\Response\Shop;

use OpenApi\Attributes as OA;

#[OA\Schema]
class CommissionResult
{
    #[OA\Property(description: '결과')]
    public bool $result;

    #[OA\Property(description: '수수료 정보', type: 'array', items: new OA\Items('#/components/schemas/CommissionInfo'))]
    public CommissionInfo $commission_info;
}

#[OA\Schema]
class CommissionInfo
{
    #[OA\Property(description: '수수료방식')]
    public string $cd_commission_type;
    #[OA\Property(description: '수수료대상금액')]
    public float $at_commission_amount;
    #[OA\Property(description: '수수료(%-원)')]
    public float $at_commission_rate;
}
