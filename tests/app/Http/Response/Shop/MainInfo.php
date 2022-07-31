<?php

declare(strict_types=1);

namespace Tests\app\Http\Response\Shop;

use OpenApi\Attributes as OA;

#[OA\Schema]
class MainInfo
{
    #[OA\Property(description: '결과')]
    public bool $result;

    #[OA\Property(description: '상점 상세', type: 'array', items: new OA\Items('#/components/schemas/Shop'))]
    public Shop $shop_info;

    #[OA\Property(description: '영업시간', type: 'array', items: new OA\Items('#/components/schemas/ShopOptTime'))]
    public ShopOptTime $shop_opt_time;

    #[OA\Property(description: '휴일', type: 'array', items: new OA\Items('#/components/schemas/ShopHoliday'))]
    public ShopHoliday $shop_holiday;

    #[OA\Property(description: '리뷰 정보', type: 'array', items: new OA\Items('#/components/schemas/ReviewTotal'))]
    public ReviewTotal $review_total;

    #[OA\Property(description: '주문주문예약방식')]
    public object $list_cd_booking_type;

    #[OA\Property(description: '오픈 여부')]
    public string $yn_open;

}

#[OA\Schema]
class ShopOptTime
{

    #[OA\Property(description: '매장번호')]
    public int $no_shop;

    #[OA\Property(description: '요일번호 (월:0 ~ 일:6)')]
    public int $nt_weekday;

    #[OA\Property(description: '영업시작시간')]
    public string $ds_open_time;

    #[OA\Property(description: '영업종료시간')]
    public string $ds_close_time;

    #[OA\Property(description: '주문시작시간')]
    public string $ds_open_order_time;

    #[OA\Property(description: '주문종료시간')]
    public string $ds_close_order_time;

    #[OA\Property(description: '휴게설정:217100:브레이크타임-217200:PICK불가')]
    public string $cd_break_time;

    #[OA\Property(description: '브레이크타임 시작시간')]
    public string $ds_break_start_time;

    #[OA\Property(description: '브레이크타임 종료시간')]
    public string $ds_break_end_time;

    #[OA\Property(description: '브레이크타임2 시작시간')]
    public string $ds_break_start_time2;

    #[OA\Property(description: '영업종료시간')]
    public string $ds_break_end_time2;

}

#[OA\Schema]
class ShopHoliday
{
    #[OA\Property(description: '정기휴무일')]
    public object $regular;

    #[OA\Property(description: '정기휴무일')]
    public Holiday $temp;

    #[OA\Property(description: '오픈여부 (Y: 매장 open, N: 매장 close, T: 임시휴일)')]
    public object $yn_open;
}

#[OA\Schema]
class Holiday
{

    #[OA\Property(description: '관리번호')]
    public int $no;

    #[OA\Property(description: '매장번호')]
    public int $no_shop;

    #[OA\Property(description: '휴일코드 임시휴일')]
    public string $cd_holiday;

    #[OA\Property(description: '시작일')]
    public string $dt_imsi_start;

    #[OA\Property(description: '종료일')]
    public string $dt_imsi_end;

    #[OA\Property(description: '임시휴일사유코드')]
    public string $cd_imsi_reason;

}

#[OA\Schema]
class ReviewTotal
{
    #[OA\Property(description: '평점평균')]
    public float $at_grade;
    #[OA\Property(description: '리뷰수')]
    public int $ct_review;
}
