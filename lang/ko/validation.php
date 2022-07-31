<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted' => 'The :attribute must be accepted.',
    'accepted_if' => 'The :attribute must be accepted when :other is :value.',
    'active_url' => 'The :attribute is not a valid URL.',
    'after' => 'The :attribute must be a date after :date.',
    'after_or_equal' => 'The :attribute must be a date after or equal to :date.',
    'alpha' => 'The :attribute must only contain letters.',
    'alpha_dash' => 'The :attribute must only contain letters, numbers, dashes and underscores.',
    'alpha_num' => 'The :attribute must only contain letters and numbers.',
    'array' => ':attribute 은(는) 배열이어야 합니다.',
    'before' => 'The :attribute must be a date before :date.',
    'before_or_equal' => 'The :attribute must be a date before or equal to :date.',
    'between' => [
        'numeric' => ':attribute 은(는) :min와 :max 사이에 있어야합니다.',
        'file' => 'The :attribute must be between :min and :max kilobytes.',
        'string' => ':attribute 은(는) :min와 :max 사이에 있어야합니다.',
        'array' => 'The :attribute must have between :min and :max items.',
    ],
    'boolean' => ':attribute 은(는) true나 false 이어야합니다.',
    'confirmed' => 'The :attribute confirmation does not match.',
    'current_password' => 'The password is incorrect.',
    'date' => ':attribute 은(는) 유효한 날짜가 아닙니다.',
    'date_equals' => 'The :attribute must be a date equal to :date.',
    'date_format' => ':attribute 은(는) 해당 포맷과 동일해야합니다.(:format)',
    'declined' => 'The :attribute must be declined.',
    'declined_if' => 'The :attribute must be declined when :other is :value.',
    'different' => 'The :attribute and :other must be different.',
    'digits' => ':attribute 은(는) :digits 자리 숫자이어야합니다.',
    'digits_between' => ':attribute 은(는) :min와 :max 사이에 있어야합니다.',
    'dimensions' => 'The :attribute has invalid image dimensions.',
    'distinct' => 'The :attribute field has a duplicate value.',
    'email' => ':attribute 은(는) 이메일주소이어야합니다.',
    'ends_with' => 'The :attribute must end with one of the following: :values.',
    'enum' => '선택한 :attribute 이(가) 잘못되었습니다.',
    'exists' => '선택한 :attribute 이(가) 잘못되었습니다.',
    'file' => 'The :attribute must be a file.',
    'filled' => 'The :attribute field must have a value.',
    'gt' => [
        'numeric' => 'The :attribute must be greater than :value.',
        'file' => 'The :attribute must be greater than :value kilobytes.',
        'string' => 'The :attribute must be greater than :value characters.',
        'array' => 'The :attribute must have more than :value items.',
    ],
    'gte' => [
        'numeric' => 'The :attribute must be greater than or equal to :value.',
        'file' => 'The :attribute must be greater than or equal to :value kilobytes.',
        'string' => 'The :attribute must be greater than or equal to :value characters.',
        'array' => 'The :attribute must have :value items or more.',
    ],
    'image' => 'The :attribute must be an image.',
    'in' => '선택한 :attribute 이(가) 잘못되었습니다.',
    'in_array' => 'The :attribute field does not exist in :other.',
    'integer' => ':attribute 은(는) 숫자이어야합니다.',
    'ip' => 'The :attribute must be a valid IP address.',
    'ipv4' => 'The :attribute must be a valid IPv4 address.',
    'ipv6' => 'The :attribute must be a valid IPv6 address.',
    'json' => 'The :attribute must be a valid JSON string.',
    'lt' => [
        'numeric' => 'The :attribute must be less than :value.',
        'file' => 'The :attribute must be less than :value kilobytes.',
        'string' => 'The :attribute must be less than :value characters.',
        'array' => 'The :attribute must have less than :value items.',
    ],
    'lte' => [
        'numeric' => 'The :attribute must be less than or equal to :value.',
        'file' => 'The :attribute must be less than or equal to :value kilobytes.',
        'string' => 'The :attribute must be less than or equal to :value characters.',
        'array' => 'The :attribute must not have more than :value items.',
    ],
    'mac_address' => 'The :attribute must be a valid MAC address.',
    'max' => [
        'numeric' => ':attribute 은(는) :max보다 작아야합니다.',
        'file' => 'The :attribute must not be greater than :max kilobytes.',
        'string' => ':attribute 은(는) :max보다 작아야합니다.',
        'array' => 'The :attribute must not have more than :max items.',
    ],
    'mimes' => 'The :attribute must be a file of type: :values.',
    'mimetypes' => 'The :attribute must be a file of type: :values.',
    'min' => [
        'numeric' => ':attribute 은(는) :max보다 커야합니다.',
        'file' => 'The :attribute must be at least :min kilobytes.',
        'string' => ':attribute 은(는) :max보다 커야합니다.',
        'array' => 'The :attribute must have at least :min items.',
    ],
    'multiple_of' => 'The :attribute must be a multiple of :value.',
    'not_in' => 'The selected :attribute is invalid.',
    'not_regex' => 'The :attribute format is invalid.',
    'numeric' => ':attribute 은(는) 숫자이어야합니다.',
    'password' => 'The password is incorrect.',
    'present' => 'The :attribute field must be present.',
    'prohibited' => 'The :attribute field is prohibited.',
    'prohibited_if' => 'The :attribute field is prohibited when :other is :value.',
    'prohibited_unless' => 'The :attribute field is prohibited unless :other is in :values.',
    'prohibits' => 'The :attribute field prohibits :other from being present.',
    'regex' => ':attribute의 포맷이 잘못되었습니다.',
    'required' => ':attribute 은(는) 필수입니다.',
    'required_array_keys' => 'The :attribute field must contain entries for: :values.',
    'required_if' => 'The :attribute field is required when :other is :value.',
    'required_unless' => 'The :attribute field is required unless :other is in :values.',
    'required_with' => 'The :attribute field is required when :values is present.',
    'required_with_all' => 'The :attribute field is required when :values are present.',
    'required_without' => 'The :attribute field is required when :values is not present.',
    'required_without_all' => 'The :attribute field is required when none of :values are present.',
    'same' => 'The :attribute and :other must match.',
    'size' => [
        'numeric' => 'The :attribute must be :size.',
        'file' => 'The :attribute must be :size kilobytes.',
        'string' => 'The :attribute must be :size characters.',
        'array' => 'The :attribute must contain :size items.',
    ],
    'starts_with' => 'The :attribute must start with one of the following: :values.',
    'string' => 'The :attribute must be a string.',
    'timezone' => 'The :attribute must be a valid timezone.',
    'unique' => 'The :attribute has already been taken.',
    'uploaded' => 'The :attribute failed to upload.',
    'url' => 'The :attribute must be a valid URL.',
    'uuid' => 'The :attribute must be a valid UUID.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [
        'cd_service' => '서비스타입',
        'no_shop' => '상점번호',
        'no_vin' => 'vin 번호',
        'cd_service_pay' => '결제기능구분',
        'cd_payment' => '결제방식',
        'cd_payment_kind' => '결제수단',
        'cd_payment_method' => '결제방법',
        'cd_gas_kind' => '유종',
        'no_card' => '카드번호',
        'list_no_event' => '사용가능한 쿠폰번호',
        'at_gas_price' => '유종단가',
        'at_liter_gas' => '결제리터',
        'yn_gas_order_liter' => '결제주유타입',
        'at_price' => '결제금액',
        'at_cpn_disct' => '쿠폰금액',
        'at_disct' => '할인금액',
        'at_owin_cash' => '오윈캐시',
        'at_point_disct' => '정유사 포인트 사용금액',
        'yn_gps_status' => 'GPS 활성화 상태',
        'no_order' => '주문번호',
        'ds_display_ark_id' => 'DP Ark 번호',
        'no_oil_company' => '주유소 브랜드 번호',
        'id_apt' => '아파트 고유번호',
        'no_cardnum' => '카드번호',
        'no_expyea' => '카드 유효기간(년)',
        'no_expmon' => '카드 유효기간(월)',
        'no_pin' => '비밀번호 앞 2자리',
        'pm_name' => '이름',
        'pm_birth' => '생년월일',
        'pm_agency' => '통신사',
        'pm_phone' => '핸드폰번호',
        'pm_nation' => '내/외국인',
        'pm_sex' => '성별',
        'no_auth_seq' => '요청식별번호',
        'sms_num' => '인증번호',
        'use_coupon_yn' => '쿠폰 사용여부',
        'page' => '페이지',
        'offset' => '페이지당 출력수',
        'status' => '이벤트 상태',
        'radius' => '반경',
        'position' => '위치',
        'oauth_code' => '인증번호',
        'ds_udid' => '휴대폰 식별값',
        'id_user' => '아이디',
        'ds_passwd' => '비밀번호',
        'nm_change_nick' => '변경할닉네임',
        'at_price_total' => '총결제금액',
        'list_product' => '주문상품 리스트',
        'pickup_type' => '픽업 타입',
        'at_commission_rate' => '매장-고객부담 수수료금액 [FnB] 수수료',
        'car_number' => '차량번호',
        'arrived_time' => '도착시간',
        'discount_info' => '할인정보',
        'agree_result' => '약관동의 정보',
        'agree_result.0' => '오윈약관',
        'agree_result.1' => 'GS약관 1',
        'agree_result.2' => 'GS약관 2',
        'agree_result.3' => 'GS약관 3',
        'agree_result.4' => 'GS약관 4',
        'id_pointcard' => '포인트카드번호',
        'yn_sale_pointcard' => '현장할인카드여부',
        'no_site' => '주차장 고유번호',
        'no_product' => '상품번호',
        'cd_reject_reason' => '주문취소사유',
        'cd_biz_kind_detail' => '업종상세구분',
        'noCategory' => '카테고리번호',
        'at_lat' => '위도',
        'at_lng' => '경도',
        'at_distance' => '거리',
        'yn_cancel' => '주문거부여부',
        'no_user' => '유저고유번호',
        'no_category' => '카테고리 번호',
        'no_sub_category' => '서브카테고리번호',
        'search_word' => '검색어',
        'ds_content' => '내용(필수값 제외)',
        'at_grade' => '평점',
        'no_event' => '이벤트번호(쿠폰번호)',
        'list_no_option' => '주문상품 옵션리스트',
        'id_admin' => '관리자ID',
    ],

];
