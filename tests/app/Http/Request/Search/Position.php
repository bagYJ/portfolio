<?php

declare(strict_types=1);

namespace Tests\app\Http\Request\Search;

use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'position')]
class Position
{
    #[OA\Property(description: '위도', example: 37.563363585187396)]
    public float $x;

    #[OA\Property(description: '경도', example: 126.97464648562759)]
    public float $y;
}
