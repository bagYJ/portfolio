<?php

declare(strict_types=1);

namespace Tests\app\Http\Response\Search;

use OpenApi\Attributes as OA;

#[OA\Schema]
class Tag
{
    #[OA\Property(description: '성공 여부')]
    public bool $result;
    #[OA\Property(description: '태그', items: new OA\Items(type: 'string'))]
    public array $tag;
}
