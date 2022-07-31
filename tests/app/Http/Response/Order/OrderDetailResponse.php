<?php

declare(strict_types=1);

namespace Tests\app\Http\Response\Order;

use OpenApi\Attributes as OA;

#[OA\Schema]
class OrderDetailResponse
{
    #[OA\Property(description: '성공 여부')]
    public bool $result;
    #[OA\Property(description: '상점명')]
    public string $nm_shop;
    #[OA\Property(description: '주문번호')]
    public string $no_order;
    #[OA\Property(description: '회원 노출용 주문번호')]
    public string $no_order_user;
    #[OA\Property(description: '업종')]
    public string $biz_kind;
    #[OA\Property(description: '주문명')]
    public string $nm_order;
    #[OA\Property(description: '주문일시')]
    public string $dt_reg;
    #[OA\Property(description: '매장수수료율')]
    public int $at_commission_rate;
    #[OA\Property(description: '전달비')]
    public int $at_send_price;
    #[OA\Property(description: '오윈상시할인금액')]
    public int $at_disct;
    #[OA\Property(description: '쿠폰할인금액')]
    public int $at_cpn_disct;
    #[OA\Property(description: '결제금액')]
    public int $at_price;
    #[OA\Property(description: 'pg결제금액')]
    public int $at_price_pg;
    #[OA\Property(description: '주문상태코드')]
    public string $cd_status;
    #[OA\Property(description: '주문상태')]
    public string $nm_status;
    #[OA\Property(description: '바로주문 등록가능 여부')]
    public bool $is_direct_order;
    #[OA\Property(description: '상점번호')]
    public int $no_shop;
    #[OA\Property(description: '주차 상점번호')]
    public int $no_site;
    #[OA\Property(description: '카드회사코드')]
    public string $cd_card_corp;
    #[OA\Property(description: '카드회사')]
    public string $card_corp;
    #[OA\Property(description: '카드 뒷4자리')]
    public string $no_card_user;
    #[OA\Property(description: '차량번호')]
    public string $ds_car_number;
    #[OA\Property(description: '자동결제 입차시간')]
    public string $dt_entry_time;
    #[OA\Property(description: '자동결제 출차시간')]
    public string $dt_exit_time;
    #[OA\Property(description: '카드 승인번호')]
    public string $ds_res_order_no;
    #[OA\Property(description: '승인시각')]
    public string $dt_res;
    #[OA\Property(description: 'pg 결과 코드값')]
    public string $pg_bill_result;
    #[OA\Property(description: 'pg 결과 상세값')]
    public string $ds_res_msg;
    #[OA\Property(description: '리뷰등록 가능 여부')]
    public bool $is_review;
    #[OA\Property(description: '주문상품', type: 'array', items: new OA\Items(ref: '#/components/schemas/ListProduct'))]
    public ListProduct $list_product;
}

#[OA\Schema]
class ListProduct
{
    #[OA\Property(description: '상품번호')]
    public int $nm_status;
    #[OA\Property(description: '상품명')]
    public string $nm_product;
    #[OA\Property(description: '주문갯수')]
    public string $ct_inven;
    #[OA\Property(description: '상품금액')]
    public int $at_price_product;
    #[OA\Property(description: '상품옵션금액')]
    public int $at_price_option;
    #[OA\Property(description: '주문상품옵션', type: 'array', items: new OA\Items(
        ref: '#/components/schemas/ListProductOption'
    ))]
    public ListProductOption $option;
}

#[OA\Schema]
class ListProductOption
{
    #[OA\Property(description: '옵션번호')]
    public int $no_option;
    #[OA\Property(description: '옵션금액')]
    public int $add_price;
    #[OA\Property(description: '옵션그룹명')]
    public string $nm_option_group;
    #[OA\Property(description: '옵션명')]
    public string $nm_option;
}
