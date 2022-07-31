<?php

declare(strict_types=1);

namespace Tests\app\Http\Response\Customer;

use OpenApi\Attributes as OA;

#[OA\Schema]
class FaqListResponse
{
    #[OA\Property]
    public bool $result;
    #[OA\Property(description: '전체 항목 개수')]
    public int $total_cnt;
    #[OA\Property(description: '페이지 당 항목 개수')]
    public int $per_page;
    #[OA\Property(description: '현재 페이지번호')]
    public int $current_page;
    #[OA\Property(description: '마지막 페이지 번호')]
    public int $last_page;
    #[OA\Property(description: 'faq 리스트', type: 'array', items: new OA\Items(ref: '#/components/schemas/FaqList'))]
    public FaqList $faq;
}

#[OA\Schema]
class FaqList
{
    #[OA\Property(description: '제목')]
    public string $ds_title;
    #[OA\Property(description: '내용')]
    public int $ds_content;
}

