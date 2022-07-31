<?php

declare(strict_types=1);

namespace Tests\app\Http\Response\Customer;

use OpenApi\Attributes as OA;

#[OA\Schema]
class EventListResponse
{
    #[OA\Property]
    public bool $result;

    #[OA\Property(description: '이벤트 리스트', type: 'array', items: new OA\Items(ref: '#/components/schemas/EventList'))]
    public EventList $event_list;
}

#[OA\Schema]
class EventList
{
    #[OA\Property(description: '이벤트 번호')]
    public int $no;
    #[OA\Property(description: '제목')]
    public string $ds_title;
    #[OA\Property(description: '내용')]
    public string $ds_content;
    #[OA\Property(description: '배너이미지')]
    public string $ds_thumb;
    #[OA\Property(description: '이벤트 노출 시작일')]
    public string $ds_start;
    #[OA\Property(description: '이벤트 노출 종료일')]
    public string $ds_end;
    #[OA\Property(description: '버튼 사용 여부')]
    public string $yn_move_button;
    #[OA\Property(description: '이벤트 시작일시')]
    public string $dt_event_start;
    #[OA\Property(description: '이벤트 상세페이지')]
    public string $ds_detail_url;
    #[OA\Property(description: 'banner,detail 일 경우 no_shop 상세페이지로 이동')]
    public string $link_act;
    #[OA\Property(description: '상점번호')]
    public int $no_shop;
    #[OA\Property(description: '상품번호')]
    public int $no_product;
    #[OA\Property(description: '업종코드')]
    public string $cd_biz_kind;
}
