<?php

declare(strict_types=1);

namespace Tests\app\Http\Response\Apt;

use OpenApi\Attributes as OA;

#[OA\Schema]
class AptListResponse
{
    #[OA\Property(description: '성공 여부')]
    public bool $result;
    #[OA\Property(description: '아파트 목록', type: 'array', items: new OA\Items(ref: '#/components/schemas/AptList'))]
    public AptList $apt_list;
}

#[OA\Schema]
class AptList
{
    #[OA\Property(description: '아파트 번호')]
    public string $id_apt;
    #[OA\Property(description: '아파트 이름')]
    public string $nm_apt;
}
