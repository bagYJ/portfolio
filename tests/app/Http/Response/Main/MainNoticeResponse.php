<?php

declare(strict_types=1);

namespace Tests\app\Http\Response\Main;

use OpenApi\Attributes as OA;

#[OA\Schema]
class MainNoticeResponse
{
    #[OA\Property(description: '성공 여부')]
    public bool $result;
    #[OA\Property(title: 'MainNotice', description: '메인공지사항')]
    public MainNotice $notice_list;
}

#[OA\Schema]
class MainNotice
{
    #[OA\Property(description: '제목')]
    public string $ds_title;
    #[OA\Property(description: '컨텐츠')]
    public string $ds_content;
    #[OA\Property(description: '공지사항 이미지')]
    public string $ds_popup_thumb;
}
