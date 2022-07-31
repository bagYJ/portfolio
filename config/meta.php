<?php

return [
    'defaultSize' => 10,
    'imageUrl' => 'http://app.owin.kr',
    'time' => [
        'am' => '오전',
        'pm' => '오후',
    ],
    'cu' => [
        'errCode' => [
            '9000' => '권한이 없습니다.',
            '9200' => '통신오류 재시도 바람(접속불가)',
            '9300' => '시스템에러(DB INSERT/SELECT 불가)',
            '9910' => '해당 매장정보가 없습니다.',
            '9920' => '해당 상품정보가 없습니다.',
            '9970' => '취소된 주문정보입니다.',
            '9971' => '주문취소가 정상처리 되지 않았습니다.',
            '9980' => '주문정보가 올바르지 않습니다.',
            '9990' => '요청처리에 실패했습니다.',
            '9998' => 'hash data 확인에 실패했습니다.',
            '9999' => '필수 파라미터가 없습니다.'
        ],
        'cdEventSell' => [
            '132100' => '금액할인',
            '132200' => '1+1',
            '132300' => '2+1',
            '132400' => '단품증정',
            '132500' => '세트상품',
        ],
        'cdRejectReason' => [
            '606100' => '재고 부족',
            '606200' => '매장 혼잠',
            '606300' => '자동취소',
            '606500' => '매장 휴무',
            '606600' => '상품 불일치',
            '606610' => '요청사항 처리 불가',
            '606620' => '도착 시간 내 조리 불가',
            '606630' => '주문불가상품',
            '606900' => '기타',
        ],
        'cdCancelType' => [
            '620100' => '회원취소',
            '620200' => '매장거부취소',
            '620300' => '자동취소',
            '620400' => '관리자취소',
        ],
    ],
    'parking' => [
        'sellingStatus' => ['AVAILABLE', 'NOT_YET_TIME', 'SOLD_OUT'],
        'defaultSellingStatus' => 'NOT_YET_TIME',
        'parkingStatus' => ['WAIT', 'USED', 'CANCELED', 'EXPIRED'],
    ],
    'push' => [
        'templates' => [
            'orderConfirm' => "[주문수락] :NM_SHOP:\n고객님의 주문이 수락되었습니다.\n픽업시간(:DT_PICKUP_TIME:)에 맞춰 매장 앞에 도착해 주세요.",
            'orderReady' => "[준비완료] :NM_SHOP:\n주문하신 상품 준비가 완료되었습니다.\n매장 앞에 도착 후 '도착알림' 버튼을 눌러주세요.",
            'deliveryConfirm' => "[전달완료] :NM_SHOP:\n주문하신 상품을 전달 완료하였습니다.\n이용해 주셔서 감사합니다"
        ]
    ],
    'common' => [
        'dsCommonNotice' => '유의 사항
● 본 상품은 당일 예약 픽업이 가능한 상품입니다.
● 당일 예약한 시간에 매장에 방문하셔서 제품을 픽업해 주세요.
● 제품별, 픽업매장별로 주문마감 시간 및 픽업가능시간이 다를 수 있습니다.
● 결제 완료 이후 상품준비 중 상태인 경우 주문 취소가 불가능합니다.
● 결제완료 후 주문한 제품이 매장에 제고가 없을 경우 매장에서 주문취소가 가능합니다.
● 예약한 시간에 방문을 못할 시 해당 매장으로 문의해 주세요.
● 예약된 시간에 물건을 픽업하지 않아 발생된 상품 변질에 대해서는 책임지지 않습니다.
● 상호명 : 주식회사 오윈
● 대표자 : 신성철
● 사업자번호 : 220-88-96700
● 주소 : 서울특별시 용산구 한남대로 98, 1층(한남동, 일신빌딩)
● 전화번호 : 02-3482-4155
',
        'dsCommonNoticeEn' => 'NOTICE
This product is available for same day reservation pickup.
● Please visit the store at the appointed time of the day to pick up the product.​
● Order finish time/ pickup time may vary depending on product and store.
● If the product is in preparation after the payment is completed, you can not cancel the order.
● If the product you ordered is out of stock, the store may cancel your order after payment is completed.
● If you are unable to visit at the time you made your reservation, please contact the store.
● We are not responsible for product deterioration caused by items not being picked up at the scheduled time.
● Company Name : OWiN Co.
● Representative : Sung Chul Shin
● Business license number : 220-88-96700
● Address : 2F Ilshin Bldg, 98 Hannam-daero, Yongsan-gu, Seoul, Korea
● Phone number : 02-3482-4155​',
        'dsCareNotice' => '유의 사항
※ 예약 시 결제되는 금액은 없으며, 정비소 방문 시
엔지니어를 통해 최종 결제금액을 확인해 주시기
바랍니다.
※ 정비소 사정에 따라 예약이 취소될 수 있습니다.
※ 예약취소는 예약당일을 제외한 48시간전까지 가능합니다.',
        'dsWashNotice' => '유의사항
· 앱 내 등록된 차량번호와 차종이 세차할 차량과 동일한 경우만 세차가 가능합니다.
· 결제할 금액보다 할임금액이 더 큰 경우 남은 차액은 환불되지 않습니다.
· 주문 1건 당 1장의 할인 쿠폰만 사용할 수 있습니다.',
        'dsRetailCommonNotice' => '유의사항 안내
1. 결제 완료 주문하신 내용에 관하여 변경은 불가능 합니다.
2. 실제 도로교통 사황에 따라 안내되는 도착 예정 시간이 다를 수 있습니다.
3. 결제 완료 후 매장에 사정에 따라 주문이 취소될 수 있습니다.
4. 주문 접수 후 매장에 사정에 따라 주문을 취소 요청할 수 있습니다.
5. 제품 수령 후 고객님의 책임있는 사유로 상품의 멸실 또는 훼손된 경우 교환, 반품이 불가능 합니다.
6. 상품 수령 시간이 경과하여 상품의 변질에 대해서는 책임지지 않습니다.
7. 미수령한 제품에 대해서는 환불이 가능하지 않습니다.
',
        'dsInfoProvisionNotice' => '개인정보 제 3자 제공 안내

㈜오윈은 원활한 서비스 제공을 위해 최소한의 범위 내에서 아래와 같이 제3자에게 개인정보를 제공합니다.

[제공 받는 자]
  - 주문매장
[제공 받는 자의 개인정보 이용목적]
  - 주문 상품 준비 및 상품 전달 서비스 제공
    (서비스 계약 이행, 수령인 확인, 주문정보 확인, 주문관리, 교환/반품/취소 관리, 문의 및 상담)
[제공하는 개인정보 항목]
  - 차종, 차량번호, 전화번호(안심번호)
[제공받는 자의 보유기간]
  - 서비스 제공 완료 후 1개월 후 파기

[제공받는 자]
  -델피콤㈜
[제공받는자의 개인정보 이용목적]
  - 안심번호 착신전환 서비스
[제공하는 개인정보 항목]
  - 전화번호
[제공받는 자의 보유기간]
  - 서비스 제공 완료 후 2시간 후 파기

결제 완료 시 개인정보 제 3자 제공에 동의한 것으로 간주합니다.

안심번호란.
고객님의 개인정보 보호를 위해 안심번호 서비스를 제공하고 있습니다.
안심번호 서비스는 상품 수취인의 개인정보인 휴대폰번호 및 전화번호를 1회성 임시번호(0507-xxxx-xxxx)로 자동 변환해드리는 서비스입니다.
픽업완료 후 일정 기간 후 안심번호는 자동 해지되며, 별도의 비용이 발생되지 않습니다.',

    ],
];
