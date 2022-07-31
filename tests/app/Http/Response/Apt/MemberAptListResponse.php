<?php

declare(strict_types=1);

namespace Tests\app\Http\Response\Apt;

use OpenApi\Attributes as OA;

#[OA\Schema]
class MemberAptListResponse
{
    #[OA\Property(description: '성공 여부')]
    public bool $result;
    #[OA\Property(title: 'MemberAptList', description: '아파트 목록')]
    public MemberAptList $apt_list;
}

#[OA\Schema]
class MemberAptList
{
    #[OA\Property(description: '아파트 번호')]
    public string $id_apt;
    #[OA\Property(description: '아파트 이름')]
    public string $nm_apt;
    #[OA\Property(description: '등록여부(Y:등록 N:미등록)')]
    public string $yn_regist;
}
