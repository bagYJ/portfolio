<?php

declare(strict_types=1);

namespace Tests\app\Http\Response\OrderOil;

use OpenApi\Attributes as OA;

#[OA\Schema]
class OrderOilIntro
{
    #[OA\Property(description: '사용자 차량 정보')]
    public MemberCarInfo $cars;
    #[OA\Property(description: '주유소 가격 정보')]
    public OilPrice $oil_prices;
    #[OA\Property(description: '사용 가능 카드 정보')]
    public Card $cards;
    #[OA\Property(description: '사용 가능 쿠폰 정보')]
    public Coupon $coupons;
    #[OA\Property(description: '포인트카드 정보')]
    public PointCard $point_card;
    #[OA\Property(description: '캐시 정보')]
    public CashInfo $cash_info;
}

#[OA\Schema]
class MemberCarInfo
{
    #[OA\Property(description: '일련번호')]
    public int $no;
    #[OA\Property(description: '회원번호')]
    public int $no_user;
    #[OA\Property(description: '차량시퀀스')]
    public int $seq;
    #[OA\Property(description: '기타차종')]
    public string $ds_etc_kind;
    #[OA\Property(description: '차량번호')]
    public string $ds_car_number;
    #[OA\Property(description: '차량색상')]
    public string $ds_car_color;
    #[OA\Property(description: '자량번호검색용')]
    public string $ds_car_search;
    #[OA\Property(description: '유종정보')]
    public string $cd_gas_kind;
    #[OA\Property(description: '사용될 RSSI 값 위치정보 - R,L,T,D')]
    public string $ds_chk_rssi_where;
    #[OA\Property(description: '기기번호')]
    public int $no_device;
    #[OA\Property(description: '어드버키-바이너리값')]
    public string $ds_adver;
    #[OA\Property(description: '메인차량여부')]
    public string $yn_main_car;
    #[OA\Property(description: '차량정보삭제여부-Y삭제N등록')]
    public string $yn_delete;
    #[OA\Property(description: '등록된 시리얼넘버(CarID)')]
    public string $ds_sn;
    #[OA\Property(description: 'Carid등록일시')]
    public string $dt_device_update;
}

#[OA\Schema]
class OilPrice
{
    #[OA\Property(description: '유종코드')]
    public string $cd_gas_kind;
    #[OA\Property(description: 'l당 가격')]
    public float $at_oil_price;
    #[OA\Property(description: '기준시간')]
    public string $dt_trade;

    #[OA\Property(description: '금액별 리터')]
    public OilPriceInfo $prices;

    #[OA\Property(description: '리터별 금액')]
    public OilPriceInfo $liters;
}

#[OA\Schema]
class OilPriceInfo
{
    #[OA\Property(description: '가격')]
    public float $price;
    #[OA\Property(description: '리터')]
    public float $liter;
}


#[OA\Schema]
class Card
{
    #[OA\Property(description: '카드순서')]
    public int $no_seq;
    #[OA\Property(description: '카드사구분코드')]
    public string $cd_card_corp;
    #[OA\Property(description: '카드사구분코드-한글')]
    public string $card_corp;
    #[OA\Property(description: '카드번호')]
    public string $no_card;
    #[OA\Property(description: '사용자카드번호(카드번호뒤4자리)')]
    public string $no_card_user;
    #[OA\Property(description: '카드명')]
    public string $nm_card;
    #[OA\Property(description: '메인카드여부')]
    public string $yn_main_card;
    #[OA\Property(description: '체크카드여부(N:신용,Y:체크)')]
    public string $yn_credit;
}

#[OA\Schema]
class Coupon
{
    #[OA\Property(description: '카드순서')]
    public int $no;
    #[OA\Property(description: '내부쿠폰번호')]
    public string $ds_cpn_no_internal;
    #[OA\Property(description: '제휴쿠폰번호')]
    public string $ds_cpn_no;
    #[OA\Property(description: '유저번호')]
    public int $no_user;
    #[OA\Property(description: '제휴사브렌드')]
    public int $no_partner;
    #[OA\Property(description: '쿠폰사용가능여부')]
    public string $use_coupon_yn;
    #[OA\Property(description: '쿠폰명')]
    public string $ds_cpn_nm;
    #[OA\Property(description: '쿠폰사용구분 (0: 단독, 1: 조건부)')]
    public string $use_disc_type;
    #[OA\Property(description: '쿠폰금액')]
    public float $at_disct_money;
    #[OA\Property(description: '사용조건-금액')]
    public float $at_limit_money;
    #[OA\Property(description: '사용조건-제휴카드사')]
    public int $cd_payment_card;
    #[OA\Property(description: '사용조건 주유리터-실주유리터적용')]
    public int $at_condi_liter;
    #[OA\Property(description: '쿠폰상태')]
    public string $cd_mcp_status;
    #[OA\Property(description: '쿠폰상태')]
    public string $cd_cpe_status;
    #[OA\Property(description: '사용시작일')]
    public string $dt_use_start;
    #[OA\Property(description: '사용종료일')]
    public string $dt_use_end;
    #[OA\Property(description: '주문번호')]
    public int $no_event;
}

#[OA\Schema]
class PointCard
{
    #[OA\Property(description: '포인트카드고유아이디')]
    public string $id_pointcard;
    #[OA\Property(description: '등록일자')]
    public string $dt_reg;
    #[OA\Property(description: '삭제여부')]
    public string $yn_delete;
    #[OA\Property(description: '현장할인카드여부-Y:할인,N:아님')]
    public string $yn_sale_card;
    #[OA\Property(description: '프로모션코드')]
    public int $no_deal;

    #[OA\Property(ref: '#/components/schemas/PromotionDeal', description: '프로모션 딜 정보')]
    public PromotionDeal $promotion_deal;

    #[OA\Property(ref: '#/components/schemas/CashInfo', description: '회원 캐시 정보')]
    public CashInfo $cash_info;

    #[OA\Property(ref: '#/components/schemas/GsInfo',description: 'gs할인카드 정보')]
    public GsInfo $gs_info;
}

#[OA\Schema]
class PromotionDeal
{
    #[OA\Property(description: '프로모션코드')]
    public int $no_deal;
    #[OA\Property(description: '프로모션 명')]
    public string $nm_deal;
    #[OA\Property(description: '발급pin수량')]
    public int $at_pin_total;
    #[OA\Property(description: '할인금액')]
    public int $at_disct_price;
    #[OA\Property(description: '대상리터')]
    public int $at_taget_liter;
    #[OA\Property(description: '할인한도금액')]
    public int $at_disct_limit;
    #[OA\Property(description: '프로모션혜택타입')]
    public string $cd_deal_type;
    #[OA\Property(description: '쿠폰금액코드')]
    public string $cdn_cpn_amt;
    #[OA\Property(description: '딜 시작일')]
    public string $dt_deal_use_st;
    #[OA\Property(description: '딜 종료일')]
    public string $dt_deal_use_end;
    #[OA\Property(description: '적용 시작일')]
    public string $dt_deal_apply_st;
    #[OA\Property(description: '적용 종료일')]
    public string $dt_deal_apply_end;
    #[OA\Property(description: 'GS카드발급코드')]
    public string $ds_gs_sale_code;
    #[OA\Property(description: '대역폭 시작정보')]
    public string $ds_bandwidth_st;
    #[OA\Property(description: '대역폭 종료정보')]
    public string $ds_bandwidth_end;
}

#[OA\Schema]
class CashInfo
{
    #[OA\Property(description: '보유총캐쉬')]
    public int $at_cash_use;
    #[OA\Property(description: '사용 포인트')]
    public int $at_use_point;
}

#[OA\Schema]
class GsInfo
{
    #[OA\Property(description: '리터당 할인금액')]
    public int $gs_sale_mount;
    #[OA\Property(description: '현장할인 월 잔여한도')]
    public int $at_can_save_amt;
    #[OA\Property(description: '현장할인 월한도')]
    public int $at_can_save_total;
    #[OA\Property(description: '현장할인 월 누적할인금액')]
    public int $at_save_amt;
}
