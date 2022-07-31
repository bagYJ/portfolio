<?php

declare(strict_types=1);

namespace Tests\app\Http\Response\Shop;

use OpenApi\Attributes as OA;

#[OA\Schema]
class ReviewRegist
{
    #[OA\Property(description: '결과')]
    public bool $result;
}

