<?php
declare(strict_types=1);

namespace Tests\app\Http\Response\Wash;

use OpenApi\Attributes as OA;

#[OA\Schema]
class WashOrderCompleteResponse
{
    #[OA\Property(description: '성공 여부')]
    public bool $result;
}