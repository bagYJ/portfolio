<?php

declare(strict_types=1);

namespace Tests\app\Http\Response\Notice;

use OpenApi\Attributes as OA;

#[OA\Schema]
class NoticeGets
{
    #[OA\Property(description: '성공 여부', example: true)]
    public bool $result;
    #[OA\Property(description: '전체 항목 개수')]
    public int $total_cnt;
    #[OA\Property(description: '페이지 당 항목 개수')]
    public int $per_page;
    #[OA\Property(description: '현재 페이지번호')]
    public int $current_page;
    #[OA\Property(description: '마지막 페이지 번호')]
    public int $last_page;
    #[OA\Property(description: '공지사항 리스트', type: 'array', items: new OA\Items('#/components/schemas/BbsNotice'))]
    public BbsNotice $rows;
}

#[OA\Schema]
class NoticeGet
{
    #[OA\Property(description: '제목')]
    public string $ds_title;
    #[OA\Property(description: '내용')]
    public string $ds_content;
    #[OA\Property(description: '배너 이미지')]
    public string $ds_popup_thumb;
    #[OA\Property(description: '공지사항 등록일자')]
    public string $dt_reg;
}

#[OA\Schema]
class BbsNotice
{
    #[OA\Property(description: '공지사항 번호')]
    public int $no;
    #[OA\Property(description: '제목')]
    public string $ds_title;
    #[OA\Property(description: '내용')]
    public string $ds_content;
    #[OA\Property(description: '배너 이미지')]
    public string $ds_popup_thumb;
    #[OA\Property(description: '공지사항 등록일자')]
    public string $dt_reg;
}
