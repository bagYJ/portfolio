<?php

declare(strict_types=1);

namespace Tests\app\Http\Response\OrderOil;

use OpenApi\Attributes as OA;

#[OA\Schema]
class OrderOilDpListResponse
{
    #[OA\Property(description: '성공 여부')]
    public bool $result;

    #[OA\Property(description: 'DP 리스트', items: new OA\Items(type: 'string'))]
    public array $list;
}

