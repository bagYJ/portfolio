<?php

declare(strict_types=1);

namespace Tests\app\Http\Response\OrderOil;

use OpenApi\Attributes as OA;

#[OA\Schema]
class OrderOilCancel
{
    #[OA\Property(description: '성공 여부')]
    public bool $result;
}

