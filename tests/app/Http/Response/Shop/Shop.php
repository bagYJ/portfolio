<?php

declare(strict_types=1);

namespace Tests\app\Http\Response\Shop;

use OpenApi\Attributes as OA;

#[OA\Schema]
class Shop
{
    #[OA\Property(description: '관리 번호')]
    public int $no;
    #[OA\Property(description: '매장 번호')]
    public int $no_shop;
    #[OA\Property(description: '입점사 번호')]
    public int $no_partner;
    #[OA\Property(description: '매장명')]
    public string $nm_shop;
    #[OA\Property(description: '매장 연락처')]
    public string $ds_tel;
    #[OA\Property(description: '이벤트 문구')]
    public string $ds_event_msg;
    #[OA\Property(description: '운영 시작 시간')]
    public string $ds_open_time;
    #[OA\Property(description: '운영 종료 시간')]
    public string $ds_close_time;
    #[OA\Property(description: '상태')]
    public string $ds_status;
    #[OA\Property(description: '리뷰 평점')]
    public float $at_grade;
    #[OA\Property(description: '우편번호')]
    public int $at_post;
    #[OA\Property(description: '매장주소')]
    public string $ds_address;
    #[OA\Property(description: '상세주소')]
    public string $ds_address2;
    #[OA\Property(description: '지역시도')]
    public string $ds_sido;
    #[OA\Property(description: '지역구군')]
    public string $ds_gugun;
    #[OA\Property(description: '지역동리')]
    public string $ds_dong;
    #[OA\Property(description: '픽업위치위도')]
    public float $at_lat;
    #[OA\Property(description: '픽업위치경도')]
    public float $at_lng;
    #[OA\Property(description: '매장위치위도')]
    public float $at_lat_shop;
    #[OA\Property(description: '매장위치경도')]
    public float $at_lng_shop;
    #[OA\Property(description: '매장별 판매 유의사항')]
    public string $ds_shop_notice;
    #[OA\Property(description: '조회수')]
    public int $ct_view;
    #[OA\Property(description: '수정자')]
    public string $id_upt;
    #[OA\Property(description: '수정일시')]
    public string $dt_upt;
    #[OA\Property(description: '삭제자')]
    public string $id_del;
    #[OA\Property(description: '삭제일시')]
    public string $dt_del;
    #[OA\Property(description: '등록자')]
    public string $id_reg;
    #[OA\Property(description: '등록일시')]
    public string $dt_reg;
    #[OA\Property(description: '1차알람거리')]
    public int $at_1_alarm_dst;
    #[OA\Property(description: '2차알람거리')]
    public int $at_2_alarm_dst;
    #[OA\Property(description: '알람기준신호세기')]
    public int $at_alarm_rssi;
    #[OA\Property(description: '수수료방식')]
    public string $cd_commission_type;
    #[OA\Property(description: '수수료대상금액')]
    public float $at_commission_amount;
    #[OA\Property(description: '매장수수료율')]
    public float $at_commission_rate;
    #[OA\Property(description: '수수료-일반')]
    public float $at_comm_rate_general;
    #[OA\Property(description: '메뉴최소준비시간')]
    public int $at_make_ready_time;
    #[OA\Property(description: '최소주문금액')]
    public float $at_min_order;
    #[OA\Property(description: '전달비')]
    public float $at_send_price;
    #[OA\Property(description: '매장아크상태')]
    public string $cd_inner_ark_status;
    #[OA\Property(description: '받을 수 있는 최소 신호 세기')]
    public int $at_accept_min_rssi;
    #[OA\Property(description: 'PG 구분')]
    public string $cd_pg;
    #[OA\Property(description: 'PG 계정 아이디')]
    public string $ds_pg_id;
    #[OA\Property(description: 'PG 수수료')]
    public float $at_pg_commission_rate;
    #[OA\Property(description: '지도 표시 영부')]
    public string $yn_display_map;
    #[OA\Property(description: '세차장 운영 여부')]
    public string $yn_operation;
    #[OA\Property(description: '영업 구분')]
    public int $no_sales_agency;
    #[OA\Property(description: '영업 수수료')]
    public float $at_sales_commission_rate;
    #[OA\Property(description: '기본주차시간(분)')]
    public string $at_basic_time;
    #[OA\Property(description: '기본주차요금')]
    public float $at_basic_fee;
    #[OA\Property(description: '추가주차시간(분)')]
    public string $at_over_time;
    #[OA\Property(description: '추가주차요금')]
    public float $at_over_fee;
    #[OA\Property(description: '발렛 - 카드 사용 여부 (Y, N)')]
    public string $yn_can_card;
    #[OA\Property(description: '매장 운영 상태 부가정보')]
    public string $cd_status_open;
    #[OA\Property(description: '주문가능방식 - ,로 구분')]
    public string $list_cd_booking_type;
    #[OA\Property(description: '연동업체구분 - ,로 구분')]
    public string $list_cd_third_party;
    #[OA\Property(description: '연동업체구분 - 기본(오윈)')]
    public string $cd_third_party;
    #[OA\Property(description: '본사 - 매장코드')]
    public string $store_cd;
    #[OA\Property(description: '현재 장애 기기 갯수')]
    public int $ct_device_error;
    #[OA\Property(description: '외부 연동 상태 변경 시간')]
    public string $external_dt_status;
    #[OA\Property(description: '매장오픈여부 (Y: 오픈 / N: 미오픈)', example: 'Y')]
    public string $yn_open;
    #[OA\Property(description: '매장 타입 (FNB: Fnb, OIL: 주유소, RETAIL: 편의점)')]
    public string $biz_kind;
    #[OA\Property(description: '카테고리정보', type: 'array', items: new OA\Items('#/components/schemas/ListCategory'))]
    public ListCategory $list_category;

    #[OA\Property(description: '상점 상세', type: 'array', items: new OA\Items('#/components/schemas/ShopDetail'))]
    public ShopDetail $shop_detail;

    #[OA\Property(description: '브랜드', type: 'array', items: new OA\Items('#/components/schemas/Partner'))]
    public Partner $partner;

    #[OA\Property(description: '상점 휴일 (존재할 경우 휴일)', type: 'array', items: new OA\Items(
        '#/components/schemas/ShopHolidayExists'
    ))]
    public ShopHolidayExists $shop_holiday_exists;

    #[OA\Property(description: '상점 운영시간 (존재할 경우 휴일)', type: 'array', items: new OA\Items(
        '#/components/schemas/ShopOptTimeExists'
    ))]
    public ShopOptTimeExists $shop_opt_time_exists;

    #[OA\Property(description: '브랜드 카테고리 정보', type: 'array', items: new OA\Items(
        '#/components/schemas/PartnerCategory'
    ))]
    public PartnerCategory $partner_category;

    #[OA\Property(description: '편의점 카테고리 정보', type: 'array', items: new OA\Items(
        '#/components/schemas/RetailCategory'
    ))]
    public RetailCategory $retail_category;

    #[OA\Property(description: '유종별 가격 정보', type: 'array', items: new OA\Items('#/components/schemas/ShopOilPrice'))]
    public ShopOilPrice $shop_oil_price;

    #[OA\Property(description: '주유소 정보', type: 'array', items: new OA\Items('#/components/schemas/ShopOil'))]
    public ShopOil $shop_oil;

    #[OA\Property(description: '주유소별 사용 불가 카드', type: 'array', items: new OA\Items(
        '#/components/schemas/ShopOilUnUseCard'
    ))]
    public ShopOilUnUseCard $shop_oil_un_use_card;
}

#[OA\Schema]
class ListCategory
{
    #[OA\Property(description: '카테고리명')]
    public string $nm_category;
    #[OA\Property(description: '상단노출여부')]
    public ?string $yn_top;
    #[OA\Property(description: '카테고리번호')]
    public string $no_category;
    #[OA\Property(description: '서브카테고리번호')]
    public ?string $no_sub_category;
}

#[OA\Schema]
class ShopDetail
{
    #[OA\Property(description: '관리 번호')]
    public int $no;
    #[OA\Property(description: '매장 번호')]
    public int $no_shop;

    #[OA\Property(description: '배경이미지')]
    public string $ds_image_bg;

    #[OA\Property(description: '이미지1')]
    public string $ds_image1;
    #[OA\Property(description: '이미지2')]
    public string $ds_image2;
    #[OA\Property(description: '이미지3')]
    public string $ds_image3;
    #[OA\Property(description: '이미지4')]
    public string $ds_image4;
    #[OA\Property(description: '이미지5')]
    public string $ds_image5;
    #[OA\Property(description: '이미지6')]
    public string $ds_image6;
    #[OA\Property(description: '이미지7')]
    public string $ds_image7;
    #[OA\Property(description: '이미지8')]
    public string $ds_image8;
    #[OA\Property(description: '이미지9')]
    public string $ds_image9;
    #[OA\Property(description: '이미지10')]
    public string $ds_image10;

    #[OA\Property(description: '미리보기텍스트')]
    public string $ds_priview;

    #[OA\Property(description: '텍스트1')]
    public string $ds_text1;
    #[OA\Property(description: '텍스트2')]
    public string $ds_text2;
    #[OA\Property(description: '텍스트3')]
    public string $ds_text3;
    #[OA\Property(description: '텍스트4')]
    public string $ds_text4;
    #[OA\Property(description: '텍스트5')]
    public string $ds_text5;
    #[OA\Property(description: '텍스트6')]
    public string $ds_text6;
    #[OA\Property(description: '텍스트7')]
    public string $ds_text7;
    #[OA\Property(description: '텍스트8')]
    public string $ds_text8;
    #[OA\Property(description: '텍스트9')]
    public string $ds_text9;
    #[OA\Property(description: '텍스트10')]
    public string $ds_text10;

    #[OA\Property(description: '픽업이미지1')]
    public string $ds_image_pick1;
    #[OA\Property(description: '픽업이미지2')]
    public string $ds_image_pick2;
    #[OA\Property(description: '픽업이미지3')]
    public string $ds_image_pick3;
    #[OA\Property(description: '픽업이미지4')]
    public string $ds_image_pick4;
    #[OA\Property(description: '픽업이미지5')]
    public string $ds_image_pick5;

    #[OA\Property(description: '매장앱주차이미지')]
    public string $ds_image_parking;

    #[OA\Property(description: '운영_월요일')]
    public string $yn_open_mon;
    #[OA\Property(description: '운영_화요일')]
    public string $yn_open_tue;
    #[OA\Property(description: '운영_수요일')]
    public string $yn_open_wed;
    #[OA\Property(description: '운영_목요일')]
    public string $yn_open_thu;
    #[OA\Property(description: '운영_금요일')]
    public string $yn_open_fri;
    #[OA\Property(description: '운영_토요일')]
    public string $yn_open_sat;
    #[OA\Property(description: '운영_일요일')]
    public string $yn_open_sun;
    #[OA\Property(description: '운영_공휴일')]
    public string $yn_open_rest;

    #[OA\Property(description: '수정자')]
    public string $id_upt;
    #[OA\Property(description: '수정일시')]
    public string $dt_upt;
    #[OA\Property(description: '삭제자')]
    public string $id_del;
    #[OA\Property(description: '삭제일시')]
    public string $dt_del;
    #[OA\Property(description: '등록자')]
    public string $id_reg;
    #[OA\Property(description: '등록일시')]
    public string $dt_reg;

    #[OA\Property(description: '가맹점명')]
    public string $nm_shop_franchise;
    #[OA\Property(description: '대표자명')]
    public string $nm_owner;
    #[OA\Property(description: '사업자번호')]
    public string $ds_biz_num;
    #[OA\Property(description: '가맹점번호')]
    public string $ds_franchise_num;
    #[OA\Property(description: '점주명')]
    public string $nm_admin;
    #[OA\Property(description: '점주 연락처')]
    public string $ds_admin_tel;
    #[OA\Property(description: '매장 관리자명')]
    public string $nm_sub_adm;
    #[OA\Property(description: '관리자연락처')]
    public string $ds_sub_adm_tel;
    #[OA\Property(description: '계약서')]
    public string $ds_contract_url;
    #[OA\Property(description: '계약상태')]
    public string $cd_contract_status;
    #[OA\Property(description: '매장정지타입')]
    public string $cd_pause_type;
    #[OA\Property(description: '매장상태노출페이지')]
    public string $ds_btn_notice;
    #[OA\Property(description: '오윈셀프주유소여부')]
    public string $yn_self;

    #[OA\Property(description: '차안픽업')]
    public bool $is_car_pickup;
    #[OA\Property(description: '매장픽업')]
    public bool $is_shop_pickup;
    #[OA\Property(description: '예약픽업')]
    public bool $is_booking_pickup;
}

#[OA\Schema]
class Partner
{
    #[OA\Property(description: '관리 번호')]
    public int $no;
    #[OA\Property(description: '제휴사번호')]
    public int $no_partner;
    #[OA\Property(description: '제휴사명')]
    public string $nm_partner;

    #[OA\Property(description: '업종타입')]
    public string $cd_biz_kind;
    #[OA\Property(description: '업종타입상세')]
    public string $cd_biz_kind_detail;

    #[OA\Property(description: '판매구분코드')]
    public string $cd_sale_kind;
    #[OA\Property(description: '브랜드BI경로')]
    public string $ds_bi;
    #[OA\Property(description: '브랜드PIN경로')]
    public string $ds_pin;
    #[OA\Property(description: '설명레이어배경')]
    public string $ds_info_bg;
    #[OA\Property(description: '상태')]
    public string $yn_status;
    #[OA\Property(description: '등록일시')]
    public string $dt_reg;
    #[OA\Property(description: '서비스구분')]
    public string $cd_service;
    #[OA\Property(description: '주소')]
    public string $ds_address;
    #[OA\Property(description: '담당자이름')]
    public string $ds_partner_admin;
    #[OA\Property(description: '연락처')]
    public string $ds_tel;
    #[OA\Property(description: '이메일')]
    public string $ds_email;
    #[OA\Property(description: '배경이미지')]
    public string $ds_image_bg;
    #[OA\Property(description: 'CI이미지')]
    public string $ds_image_ci;
    #[OA\Property(description: '계약시작일')]
    public string $dt_contract_start;
    #[OA\Property(description: '계약종료일')]
    public string $dt_contract_end;
    #[OA\Property(description: '자동연장여부')]
    public string $yn_auto_extend;
    #[OA\Property(description: '계약서URL')]
    public string $ds_contract_url;
    #[OA\Property(description: '수수료방식')]
    public string $cd_commission_type;
    #[OA\Property(description: '수수료대상금액')]
    public float $at_commission_amount;
    #[OA\Property(description: '수수료(%-원)')]
    public float $at_commission_rate;
    #[OA\Property(description: '수수료-일반')]
    public float $at_comm_rate_general;
    #[OA\Property(description: '수수료건수')]
    public int $ct_commission_sales;
    #[OA\Property(description: '수정일시')]
    public string $dt_upt;
    #[OA\Property(description: '제휴사고유번호')]
    public int $no_company;
    #[OA\Property(description: '메뉴원산지')]
    public string $ds_menu_origin;
    #[OA\Property(description: '영업구분')]
    public int $no_sales_agency;
    #[OA\Property(description: '영업수수료')]
    public float $at_sales_commission_rate;
    #[OA\Property(description: 'PG구분')]
    public string $cd_pg;
    #[OA\Property(description: 'PG계정아이디')]
    public string $ds_pg_id;
    #[OA\Property(description: 'PG수수료')]
    public float $at_pg_commission_rate;
    #[OA\Property(description: '입금계좌은행')]
    public string $cd_bank;
    #[OA\Property(description: '입금계좌번호')]
    public string $ds_bank_acct;
    #[OA\Property(description: '입금계좌명')]
    public string $nm_acct_name;
    #[OA\Property(description: '계약상태')]
    public string $cd_contract_status;
    #[OA\Property(description: '사업자등록번호')]
    public string $ds_biz_num;
    #[OA\Property(description: '정산주체')]
    public string $cd_calculate_main;
    #[OA\Property(description: '정산담당자 이메일')]
    public string $ds_calc_email;
    #[OA\Property(description: '정산담당자 이메일 최종수정일자')]
    public string $dt_calc_email_upt;
    #[OA\Property(description: '정산담당자이메일 수정어드민 ')]
    public string $id_admin;
    #[OA\Property(description: '외부연동용 코드')]
    public string $ds_external_company;
}

#[OA\Schema]
class ShopHolidayExists
{
    #[OA\Property(description: '고유번호')]
    public int $no;
    #[OA\Property(description: '매장번호')]
    public int $no_shop;
    #[OA\Property(description: '휴일코드 임시휴일')]
    public string $cd_holiday;
    #[OA\Property(description: '휴일')]
    public int $nt_weekday;
    #[OA\Property(description: '임시휴일 시작일')]
    public string $dt_imsi_start;
    #[OA\Property(description: '임시휴일 종료일')]
    public string $dt_imsi_end;
    #[OA\Property(description: '등록일')]
    public string $dt_reg;
    #[OA\Property(description: '수정일')]
    public string $dt_upt;
    #[OA\Property(description: '임시휴일사유코드')]
    public string $cd_imsi_reason;

}

#[OA\Schema]
class ShopOptTimeExists
{
    #[OA\Property(description: '매장번호')]
    public int $no_shop;
    #[OA\Property(description: '요일번호')]
    public int $nt_weekday;
    #[OA\Property(description: '시작시간')]
    public string $ds_open_time;
    #[OA\Property(description: '종료시간')]
    public int $ds_close_time;
    #[OA\Property(description: '주문 시작시간')]
    public string $ds_open_order_time;
    #[OA\Property(description: '주문 종료시간')]
    public string $ds_close_order_time;
    #[OA\Property(description: '등록시간')]
    public string $dt_reg;
    #[OA\Property(description: '수정시간')]
    public string $dt_upt;
    #[OA\Property(description: '휴게설정:217100:브레이크타임-217200:PICK불가')]
    public string $cd_break_time;
    #[OA\Property(description: '블레이크타임 시작시간')]
    public string $ds_break_start_time;
    #[OA\Property(description: '블레이크타임 종료시간')]
    public string $ds_break_end_time;
    #[OA\Property(description: '휴게설정:217100:브레이크타임-217200:PICK불가')]
    public string $cd_break_time2;
    #[OA\Property(description: '블레이크타임 시작시간')]
    public string $ds_break_start_time2;
    #[OA\Property(description: '블레이크타임 종료시간')]
    public string $ds_break_end_time2;
}

#[OA\Schema]
class PartnerCategory
{
    #[OA\Property(description: '관리번호')]
    public int $no;
    #[OA\Property(description: '상품카테고리번호')]
    public int $no_partner_category;
    #[OA\Property(description: '카테고리 번호')]
    public string $no_category;
    #[OA\Property(description: '입점사번호')]
    public int $no_partner;
    #[OA\Property(description: '상품카테고리명')]
    public string $nm_category;
    #[OA\Property(description: '노출순서')]
    public int $ct_order;
    #[OA\Property(description: '주문 종료시간')]
    public string $ds_close_order_time;
    #[OA\Property(description: '등록시간')]
    public string $dt_reg;
    #[OA\Property(description: '수정시간')]
    public string $dt_upt;
    #[OA\Property(description: '수수료사용여부')]
    public string $yn_commission;
}

#[OA\Schema]
class RetailCategory
{
    #[OA\Property(description: '관리번호')]
    public int $no;
    #[OA\Property(description: '입점사번호')]
    public int $no_partner;
    #[OA\Property(description: '카테고리 번호')]
    public string $no_category;
    #[OA\Property(description: '카테고리명')]
    public string $nm_category;
    #[OA\Property(description: '사용시작일 (00:00:00)')]
    public string $dt_use_st;
    #[OA\Property(description: '사용종료일 (00:00:00)')]
    public string $dt_use_end;
    #[OA\Property(description: '상단노출여부')]
    public string $yn_top;
    #[OA\Property(description: '노출순서')]
    public int $at_view;
    #[OA\Property(description: '노출여부 (N:미노출 Y;노출)')]
    public string $yn_shop;
    #[OA\Property(description: '상태')]
    public string $ds_status;
    #[OA\Property(description: '르노 노출여부')]
    public string $ds_avn_status;

    #[OA\Property(description: '편의점 서브 카테고리 정보', type: 'array', items: new OA\Items(
        '#/components/schemas/RetailSubCategory'
    ))]
    public RetailSubCategory $retail_sub_category;
}

#[OA\Schema]
class RetailSubCategory
{
    #[OA\Property(description: '관리번호')]
    public int $no;
    #[OA\Property(description: '입점사번호')]
    public int $no_partner;
    #[OA\Property(description: '카테고리 번호')]
    public string $no_category;
    #[OA\Property(description: '서브 카테고리 번호')]
    public string $no_sub_category;
    #[OA\Property(description: '서브 카테고리명')]
    public string $nm_sub_category;
    #[OA\Property(description: '노출순서')]
    public int $at_view;
    #[OA\Property(description: '상태')]
    public string $ds_status;
    #[OA\Property(description: '르노 노출여부')]
    public string $ds_avn_status;
}

#[OA\Schema]
class ShopOilPrice
{
    #[OA\Property(description: '매장번호')]
    public int $no_shop;
    #[OA\Property(description: '유종종류')]
    public string $cd_gas_kind;
    #[OA\Property(description: '주유소코드')]
    public string $ds_uni;
    #[OA\Property(description: '제품구분')]
    public string $ds_prod;
    #[OA\Property(description: '판매가격')]
    public string $at_price;
    #[OA\Property(description: '기준일자')]
    public int $dt_trade;
    #[OA\Property(description: '기준시간')]
    public string $tm_trade;
    #[OA\Property(description: '리터당할인금액')]
    public float $at_discnt_liter;
}

#[OA\Schema]
class ShopOil
{
    #[OA\Property(description: '매장번호')]
    public int $no_shop;
    #[OA\Property(description: '주유소코드')]
    public string $ds_uni;
    #[OA\Property(description: '주유소상표')]
    public string $ds_poll_div;
    #[OA\Property(description: '충전소상표')]
    public string $ds_gpoll_div;
    #[OA\Property(description: '상호')]
    public string $nm_os;
    #[OA\Property(description: '지번주소')]
    public int $ds_van_adr;
    #[OA\Property(description: '도로명주소')]
    public string $ds_new_adr;
    #[OA\Property(description: '전화번호')]
    public string $ds_tel;
    #[OA\Property(description: 'X좌표')]
    public float $at_gis_x;
    #[OA\Property(description: 'Y좌표')]
    public float $at_gis_y;
    #[OA\Property(description: '경정비시설유무')]
    public string $yn_maint;
    #[OA\Property(description: '편의점 유무')]
    public string $yn_cvs;
    #[OA\Property(description: '세차장 유무')]
    public string $yn_car_wash;
    #[OA\Property(description: '셀프주유소여부')]
    public string $yn_self;
    #[OA\Property(description: '시도코드')]
    public string $cd_sido;
    #[OA\Property(description: '시군코드')]
    public string $cd_sigun;
    #[OA\Property(description: '업종구분')]
    public string $ds_lpg;
    #[OA\Property(description: '최종수정일자')]
    public string $dt_mofy;
    #[OA\Property(description: '배치작업일자')]
    public string $dt_update;
    #[OA\Property(description: '결재용 매장고유번호')]
    public string $ds_id_for_bill;
    #[OA\Property(description: '결재용 매장단말기 고유번호')]
    public string $ds_unit_key_for_bill;
    #[OA\Property(description: 'NICE 결재용 매장단말기 고유번호')]
    public string $ds_unit_key_for_bill_nice;
    #[OA\Property(description: 'DP2.0설치유무')]
    public string $yn_dp2;

}

#[OA\Schema]
class ShopOilUnUseCard
{
    #[OA\Property(description: '일련번호')]
    public int $seq;
    #[OA\Property(description: '매장번호')]
    public int $no_shop;
    #[OA\Property(description: '카드사 코드')]
    public string $cd_card_corp;
    #[OA\Property(description: '화면단에 보여진 카드사 (카드사코드에 설정된 카드사명과 다름. )')]
    public string $nm_card_corp_show;
    #[OA\Property(description: '상태 (Y:불가카드. N:사용가능카드)')]
    public string $yn_unuse_status;
    #[OA\Property(description: '사용불가 사유')]
    public string $unuse_reason;
}
