<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('parking_order_list', function (Blueprint $table) {
            $table->unsignedBigInteger('no_order')->primary();
            $table->string('nm_order', 100)->comment('주문명');

            $table->unsignedBigInteger('no_user')->comment('회원 번호');
            $table->string('ds_car_number')->comment('회원 차량번호');
            $table->bigInteger('seq')->comment('차량시퀀스');

            $table->unsignedBigInteger('no_site')->comment('OWIN 내부 uid');

            $table->string('id_site', 20)->comment('외부 키값');

            $table->unsignedBigInteger('no_parking_site')->nullable()->comment('카메레온 주차장 uid');

            $table->unsignedBigInteger('no_product')->nullable()->comment('카멜레온 주차장 티켓 uid');
            $table->unsignedBigInteger('no_booking_uid')->nullable()->comment('카멜레온 예약 번호');

            $table->string('id_auto_parking', 100)->nullable()->comment('자동 결제 주차장 고유번호');

            $table->string('ds_parking_start_time', 4)->nullable()->comment('카멜레온 - 주차 가능 시간(시작)');
            $table->string('ds_parking_end_time', 4)->nullable()->comment('카멜레온 - 주차 종료 시간(종료)');

            $table->integer('cd_ticket_type')->nullable()->comment('카멜레온 - 할인권 종류 코드');
            $table->integer('cd_ticket_day_type')->nullable()->comment('카멜레온 - 할인권 요일 코드');

            $table->string('ds_user_parking_reserve_time', 5)->nullable()->comment('카멜레온 - 입차예정시간');
            $table->dateTime('dt_user_parking_used')->nullable()->comment('카멜레온 - 할인권이 적용된 일시 (WAIT > USED)');
            $table->dateTime('dt_user_parking_canceled')->nullable()->comment('카멜레온 - 할인권이 취소된 일시 (WAIT > CANCELED)');
            $table->dateTime('dt_user_parking_expired')->nullable()->comment('카멜레온 - 할인권 만료 예정 일시');

            $table->enum('cd_parking_status', Config('meta.parking.parkingStatus'))->nullable()->comment('카멜레온 - 사용자 주차 상태');

            $table->float('at_basic_price')->nullable()->comment('자동결제 - 추가 시간');
            $table->integer('at_basic_time')->nullable()->comment('자동결제 - 추가 요금');

            $table->dateTime('dt_entry_time')->nullable()->comment('자동결제 - 입차시간');
            $table->dateTime('dt_exit_time')->nullable()->comment('자동결제 - 출차시간');

            $table->string('cd_service', 6)->comment('서비스구분');
            $table->string('cd_service_pay', 6)->default('901100')->comment('결제서비스 방식구분');
            $table->string('cd_order_status', 6)->default('601100')->comment('주문상태');

            $table->string('cd_pg', 6)->comment('PG 구분');
            $table->string('cd_payment', 6)->comment('결제방식');
            $table->string('cd_payment_kind', 6)->comment('결제수단');
            $table->string('cd_payment_method', 6)->comment('결제방법(Billkey/OTC)');
            $table->string('cd_payment_status', 6)->default('603100')->comment('결제상태');

            $table->bigInteger('no_card')->comment('빌키결제:카드번호');
            $table->string('cd_card_corp', 6)->comment('빌키결제:카드사구분코드');
            $table->integer('no_card_user')->comment('빌키결제:회원카드번호');

            $table->float('at_price')->default(0)->comment('결제금액');
            $table->float('at_price_pg')->default(0)->comment('PG결제금액');

            $table->integer('at_disct')->default(0)->comment('오윈상시할인금액');
            $table->integer('at_cpn_disct')->default(0)->comment('쿠폰할인금액');

            $table->dateTime('dt_req')->nullable()->comment('결제시도일시');
            $table->dateTime('dt_res')->nullable()->comment('결제응답일시');

            $table->string('cd_pg_result', 6)->default('604050')->comment('내부결과코드');
            $table->string('cd_pg_bill_result', 6)->nullable()->comment('PG 결과코드');

            $table->string('ds_res_code', 50)->nullable()->comment('PG결과코드');
            $table->string('ds_res_msg', 100)->nullable()->comment('PG결과메시지');
            $table->string('ds_res_order_no', 30)->nullable()->comment('PG결과주문번호');

            $table->mediumText('ds_req_param')->comment('요청파라미터');
            $table->mediumText('ds_res_param')->nullable()->comment('응답파라미터');

            $table->dateTime('dt_req_refund')->nullable()->comment('취소시도일시');
            $table->dateTime('dt_res_refund')->nullable()->comment('취소완료일시');

            $table->mediumText('ds_req_refund')->nullable()->comment('취소요청파라미터');
            $table->mediumText('ds_res_refund')->nullable()->comment('취소응답파라미터');

            $table->string('ds_res_code_refund', 50)->nullable()->comment('취소응답코드');

            $table->string('cd_reject_reason', 6)->nullable()->comment('거절 이유');
            $table->string('ds_server_reg', 14)->nullable()->comment('웹서버기준 보낸시간');
            $table->string('ds_pg_id', 50)->nullable()->comment('결제PG계정 보낸시간');

            $table->string('tid', 30)->nullable()->comment('OTC거래아이디');
            $table->integer('product_num')->nullable()->comment('OTC상품개수');
            $table->string('cancel_id', 10)->nullable()->comment('OTC취소요청자ID');
            $table->string('cancel_pw', 50)->nullable()->comment('결제PG계정 보낸시간');

            $table->float('at_pg_commission_rate')->default(0)->comment('PG사 수수료');
            $table->string('cd_commission_type', 6)->nullable()->comment('수수료방식');
            $table->float('at_commission_amount')->default(0)->comment('수수료 대상금액');
            $table->float('at_commission_rate')->default(0)->comment('매장수수료율');
            $table->float('at_sales_commission_rate')->default(0)->comment('영업대행 수수료율');

            $table->dateTime('dt_order_status')->nullable()->comment('주문상태변경일시');
            $table->dateTime('dt_payment_status')->nullable()->comment('결제상태변경일시');
            $table->dateTime('dt_booking')->nullable()->comment('카멜레온 예약응답일시');
            $table->dateTime('dt_check_cancel')->nullable()->comment('취소일시');
            $table->dateTime('dt_reg')->useCurrent()->comment('등록일자');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('parking_order_list');
    }
};
