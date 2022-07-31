<?php

declare(strict_types=1);

namespace Tests\app\Http\Response\Order;

use OpenApi\Attributes as OA;

#[OA\Schema]
class OrderInitResponse
{
    #[OA\Property(description: '성공 여부')]
    public bool $result;
    #[OA\Property(description: '메뉴최소준비시간')]
    public int $at_make_ready_time;
    #[OA\Property(description: '매장수수료')]
    public int $at_commission_rate;
    #[OA\Property(description: '전달료')]
    public int $at_send_price;
    #[OA\Property(description: '차안픽업')]
    public bool $is_car_pickup;
    #[OA\Property(description: '매장픽업')]
    public bool $is_shop_pickup;
    #[OA\Property(description: '예약픽업')]
    public bool $is_booking_pickup;
    #[OA\Property(description: '차번')]
    public string $ds_car_number;
    #[OA\Property(description: '상점 운영시간 정보', type: 'array', items: new OA\Items(
        ref: '#/components/schemas/ShopOptInfo'
    ))]
    public ShopOptInfo $shop_opt_info;
    #[OA\Property(description: '상점 류일 정보', type: 'array', items: new OA\Items(
        ref: '#/components/schemas/ShopHolidayInfo'
    ))]
    public ShopHolidayInfo $shop_holiday_info;
    #[OA\Property(description: '쿠폰 정보', type: 'array', items: new OA\Items(ref: '#/components/schemas/CouponInfo'))]
    public CouponInfo $coupon_info;
}

#[OA\Schema]
class ShopOptInfo
{
    #[OA\Property(description: '요일(숫자)')]
    public int $day_of_week;
    #[OA\Property(description: '요일')]
    public string $day_text;
    #[OA\Property(description: '오픈시간')]
    public string $ds_open_time;
    #[OA\Property(description: '종료시간')]
    public string $ds_close_time;
    #[OA\Property(description: '휴게시간1')]
    public BreakTime $break1;
    #[OA\Property(description: '휴게시간2')]
    public BreakTime $break2;
}

#[OA\Schema]
class BreakTime
{
    #[OA\Property(description: '휴식타입')]
    public string $type;
    #[OA\Property(description: '휴식타입(텍스트)')]
    public string $text;
    #[OA\Property(description: '휴게타임 시작시간')]
    public string $start_time;
    #[OA\Property(description: '휴식타입 종료시간')]
    public string $end_time;
}

#[OA\Schema]
class ShopHolidayInfo
{
    #[OA\Property(description: '휴일')]
    public string $holiday;
    #[OA\Property(description: '휴일 시작시간')]
    public string $break_start_time;
    #[OA\Property(description: '퓨일 종료시간')]
    public string $break_end_time;
}

#[OA\Schema]
class CouponInfo
{
    #[OA\Property(description: '쿠폰번호')]
    public int $no;
    #[OA\Property(description: '쿠폰명')]
    public string $nm_event;
    #[OA\Property(description: '쿠폰타입(DISCOUNT: 할인, GIFT: 사은품)')]
    public string $coupon_type;
    #[OA\Property(description: '쿠폰할인금액')]
    public int $at_discount;
    #[OA\Property(description: '사용가능카드회사')]
    public string $required_card;
    #[OA\Property(description: '사은품')]
    public string $gift;
}

#[OA\Schema]
class CardInfo
{
    #[OA\Property(description: '카드번호')]
    public string $no_card;
    #[OA\Property(description: '사용자카드번호(카드번호뒤4자리)')]
    public string $no_card_user;
    #[OA\Property(description: '카드회사코드')]
    public string $cd_card_corp;
    #[OA\Property(description: '카드회사명')]
    public string $card_corp;
    #[OA\Property(description: '메인카드여부')]
    public string $yn_main_card;
//    #[OA\Property(description: '사은품')]
//    public string $cd_payment_method;
}
