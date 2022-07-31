<?php


declare(strict_types=1);

namespace Tests\app\Http\Response\Parking;

use OpenApi\Attributes as OA;

#[OA\Schema]
class ParkingOrderList
{
    #[OA\Property(description: '주문 리스트', type: 'array', items: new OA\Items('#/components/schemas/ParkingOrder'))]
    public ParkingOrder $rows;
}

#[OA\Schema]
class OrderResult
{
    #[OA\Property(description: '성공 여부', example: true)]
    public bool $result;
    #[OA\Property(description: '예약번호', example: 1)]
    public int $bookingUid;
    #[OA\Property(description: '등록일자', example: 'yyyy-MM-dd HH:mm:ss')]
    public string $createdAt;
}


#[OA\Schema]
class ParkingOrder
{
    #[OA\Property(description: '주문번호', example: 0)]
    public int $no_order;
    #[OA\Property(description: '카멜레온 예약 번호', example: 0)]
    public int $booking_uid;
    #[OA\Property(description: '주문명', example: '당일권(평일)')]
    public string $nm_order;
    #[OA\Property(description: '회원 번호', example: 12345678)]
    public int $no_user;
    #[OA\Property(description: '회원 차량 no', example: 1)]
    public int $no_member_carinfo;
    #[OA\Property(description: '주차 가능 시간(시작)', example: '0000')]
    public string $parking_start_time;
    #[OA\Property(description: '주차 종료 시간(종료)', example: '2359')]
    public string $parking_end_time;
    #[OA\Property(description: '할인권 종류 코드', example: 3)]
    public int $ticket_type;
    #[OA\Property(description: '할인권 요일 코드', example: 1)]
    public int $ticket_day_type;
    #[OA\Property(description: '입차예정시간', example: '0000')]
    public string $user_parking_reserve_time;
    #[OA\Property(description: '할인권이 적용된 일시 (WAIT > USED)', example: 'yyyy-MM-dd HH:mm:ss')]
    public string $dt_user_parking_used;
    #[OA\Property(description: '할인권이 취소된 일시 (WAIT > CANCELED)', example: 'yyyy-MM-dd HH:mm:ss')]
    public string $dt_user_parking_canceled;
    #[OA\Property(description: '할인권 만료 예정 일시', example: 'yyyy-MM-dd HH:mm:ss')]
    public string $dt_user_parking_expired;
    #[OA\Property(description: '사용자 주차 상태', example: 'WAIT')]
    public string $parking_status;
    #[OA\Property(description: '주차장 uid', example: 1)]
    public int $no_site;
    #[OA\Property(description: '주차장 티켓 uid', example: 2)]
    public int $no_product;
    #[OA\Property(description: '주문상태', example: '601100')]
    public string $cd_order_status;
    #[OA\Property(description: '결제서비스 방식구분', example: '901100')]
    public string $cd_service_pay;
    #[OA\Property(description: 'PG 구분', example: '500100')]
    public string $cd_pg;
    #[OA\Property(description: '결제방식', example: '501100')]
    public string $cd_payment;
    #[OA\Property(description: '결제수단', example: '502100')]
    public string $cd_payment_kind;
    #[OA\Property(description: '결제상태', example: '603100')]
    public string $cd_payment_status;
    #[OA\Property(description: '빌키결제:카드번호', example: 123123)]
    public int $no_card;
    #[OA\Property(description: '빌키결제:카드사구분코드', example: '')]
    public string $cd_card_corp;
    #[OA\Property(description: '빌키결제:회원카드번호', example: '')]
    public string $no_card_user;
    #[OA\Property(description: '결제금액', example: 0.0)]
    public float $at_price;
    #[OA\Property(description: 'PG결제금액', example: 0.0)]
    public float $at_price_pg;
    #[OA\Property(description: '결제요청일시', example: 'yyyy-MM-dd HH:mm:ss')]
    public string $dt_req;
    #[OA\Property(description: '결제응답일시', example: 'yyyy-MM-dd HH:mm:ss')]
    public string $dt_res;
    #[OA\Property(description: 'PG 결과코드', example: '604050')]
    public string $cd_pg_result;
    #[OA\Property(description: 'PG결과코드', example: '902000')]
    public string $cd_pg_bill_result;
    #[OA\Property(description: 'PG결과코드', example: '')]
    public string $ds_res_code;
    #[OA\Property(description: 'PG결과메시지', example: '')]
    public string $ds_res_msg;
    #[OA\Property(description: 'PG결과주문번호', example: '')]
    public string $ds_res_order_no;

    #[OA\Property(description: '취소요청파라미터')]
    public string $ds_req_param;
    #[OA\Property(description: '취소응답파라미터')]
    public string $ds_res_param;

    #[OA\Property(description: '취소시도일시', example: 'yyyy-MM-dd HH:mm:ss')]
    public string $dt_req_refund;
    #[OA\Property(description: '취소완료일시', example: 'yyyy-MM-dd HH:mm:ss')]
    public string $dt_res_refund;

    #[OA\Property(description: '취소요청파라미터', example: '')]
    public string $ds_req_refund;
    #[OA\Property(description: '취소응답파라미터', example: '')]
    public string $ds_res_refund;

    #[OA\Property(description: '취소응답코드', example: '')]
    public string $ds_res_code_refund;

    #[OA\Property(description: '거절이유', example: '606300')]
    public string $cd_reject_reason;
    #[OA\Property(description: '웹서버기준 보낸시간', example: 'yyyyMMddHHmmss')]
    public string $ds_server_reg;
    #[OA\Property(description: '결제PG계정 보낸시간', example: 'yyyy-MM-dd HH:mm:ss')]
    public string $ds_pg_id;

    #[OA\Property(description: 'OTC거래아이디', example: '')]
    public string $tid;
    #[OA\Property(description: 'OTC상품개수', example: null)]
    public int $product_num;
    #[OA\Property(description: 'OTC취소요청자ID', example: '')]
    public string $cancel_id;
    #[OA\Property(description: 'OTC취소요청자PW', example: '')]
    public string $cancel_pw;

    #[OA\Property(description: 'PG사 수수료', example: 0.0)]
    public float $at_pg_commission_rate;
    #[OA\Property(description: '수수료방식', example: '205100')]
    public string $cd_commission_type;
    #[OA\Property(description: '수수료 대상금액', example: 0.0)]
    public float $at_commission_amount;
    #[OA\Property(description: '매장수수료율', example: 0.0)]
    public float $at_commission_rate;
    #[OA\Property(description: '영업대행 수수료율', example: 0.0)]
    public float $at_sales_commission_rate;
    #[OA\Property(description: '주문상태변경일시', example: 'yyyy-MM-dd HH:mm:ss')]
    public string $dt_order_status;
    #[OA\Property(description: '결제상태변경일시', example: 'yyyy-MM-dd HH:mm:ss')]
    public string $dt_payment_status;
    #[OA\Property(description: '카멜레온 예약응답일시', example: 'yyyy-MM-dd HH:mm:ss')]
    public string $dt_booking;
    #[OA\Property(description: '취소일시', example: 'yyyy-MM-dd HH:mm:ss')]
    public string $dt_check_cancel;
    #[OA\Property(description: '등록일자', example: 'yyyy-MM-dd HH:mm:ss')]
    public string $dt_reg;

    #[OA\Property(description: '주차장 정보', type: 'array', items: new OA\Items('#/components/schemas/ParkingSite'))]
    public ParkingSite $parkingSite;
}

#[OA\Schema]
class CancelResult
{
    #[OA\Property(description: '성공 여부', example: true)]
    public bool $result;
    #[OA\Property(description: '예약번호', example: 1)]
    public int $bookingUid;
    #[OA\Property(description: '취소일자', example: 'yyyy-MM-dd HH:mm:ss')]
    public string $canceledAt;
}
