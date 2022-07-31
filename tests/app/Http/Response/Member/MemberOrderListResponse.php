<?php

declare(strict_types=1);

namespace Tests\app\Http\Response\Member;

use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'MemberOrderListResponse')]
class MemberOrderListResponse
{
    #[OA\Property(description: '성공 여부')]
    public bool $result;
    #[OA\Property(description: '전체 항목 개수')]
    public int $total_cnt;
    #[OA\Property(description: '페이지 당 항목 개수')]
    public int $per_page;
    #[OA\Property(description: '현재 페이지번호')]
    public int $current_page;
    #[OA\Property(description: '마지막 페이지 번호')]
    public int $last_page;
    #[OA\Property(description: '회원 주문 목록', type: 'array', items: new OA\Items(
        ref: '#/components/schemas/MemberOrderList'
    ))]
    public MemberOrderList $order_list;
}

#[OA\Schema]
class MemberOrderList
{
    #[OA\Property(description: '주문번호')]
    public string $no_order;
    #[OA\Property(description: '주문명')]
    public string $nm_order;
    #[OA\Property(description: '주문상태 코드')]
    public string $cd_order_status;
    #[OA\Property(description: '주문상태')]
    public string $order_status;
    #[OA\Property(description: '주차 상태 코드')]
    public string $cd_parking_status;
    #[OA\Property(description: '주차상태')]
    public string $parking_status;
    #[OA\Property(description: '결제일시')]
    public string $dt_reg;
    #[OA\Property(description: '제휴사명')]
    public string $nm_partner;
    #[OA\Property(description: '매장번호')]
    public int $no_shop;
    #[OA\Property(description: '매장명')]
    public string $nm_shop;
    #[OA\Property(description: '업종코드')]
    public string $cd_biz_kind;
    #[OA\Property(description: '업종명')]
    public string $biz_kind;
}
