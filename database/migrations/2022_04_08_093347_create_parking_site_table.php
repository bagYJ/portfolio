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
        Schema::create('parking_site', function (Blueprint $table) {
            $table->id('no_site')->comment('주차장 고유번호');

            $table->unsignedBigInteger('no_parking_site')->nullable()->comment('카멜레온 주차장 고유번호');
            $table->string('id_auto_parking', 100)->nullable()->comment('자동 결제 주차장 고유번호');

            $table->string('id_site', 20)->comment('외부 사이트 정의 키값');
            $table->enum('ds_type', ['WEB', 'AUTO'])->default('WEB')->comment('WEB: 카멜레온, AUTO: 자동결제');

            $table->string('nm_shop', 100)->comment('주차장명');
            $table->string('ds_category')->nullable()->comment('자동 결제 주차장 분류')->nullable();
            $table->string('ds_option_tag', 100)->nullable()->comment('주차장 정보 태그 (,로 구분)')->nullable();
            $table->float('at_price')->default(0)->comment('시간당 요금(참고용 정보)');
            $table->string('ds_price_info', 256)->comment('주차장 상세 요금(참고용 정보)');
            $table->string('ds_time_info', 256)->nullable()->comment('주차장 시간 정보');

            $table->float('at_basic_price')->nullable()->comment('자동결제 - 추가 시간');
            $table->integer('at_basic_time')->nullable()->comment('자동결제 - 추가 요금');

            $table->string('ds_tel', 20)->nullable()->comment('주차장 전화번호');
            $table->string('ds_info', 256)->nullable()->comment('주차장 안내정보');
            $table->double('at_lat', 10, 7)->default(0)->comment('주차장 위도');
            $table->double('at_lng', 10, 7)->default(0)->comment('주차장 경');
            $table->string('ds_address', 256)->comment('주차장 주소');
            $table->string('ds_operation_time', 256)->comment('운영 시간');
            $table->longText('ds_caution')->nullable()->comment('유의사항 (markdown)');

            $table->char('auto_biz_type', 1)->nullable()->comment('평일운영방법(1:24시간,2:시간제,3:휴무,4:정보없음)');
            $table->string('auto_biz_time')->nullable()->comment('평일운영시간');

            $table->char('auto_sat_biz_type', 1)->nullable()->comment('토요일운영방법(1:24시간,2:시간제,3:휴무,4:정보없음)');
            $table->string('auto_sat_biz_time')->nullable()->comment('토요일운영시간');

            $table->char('auto_hol_biz_type', 1)->nullable()->comment('일요일운영방법(1:24시간,2:시간제,3:휴무,4:정보없음)');
            $table->string('auto_hol_biz_time')->nullable()->comment('일요일운영시간');

            $table->double('at_pg_commission_rate', 10, 2)->default(0)->comment('PG사 수수료');
            $table->string('cd_commission_type', 6)->nullable()->comment('수수료방식');
            $table->double('at_commission_amount', 10, 2)->default(0)->comment('수수료 대상금액');
            $table->double('at_commission_rate', 10, 2)->default(0)->comment('매장수수료율');
            $table->double('at_sales_commission_rate', 10, 2)->default(0)->comment('영업대행 수수료율');

            $table->dateTime('dt_reg')->useCurrent();
            $table->dateTime('dt_upt')->useCurrent()->useCurrentOnUpdate();

            $table->index('at_lat');
            $table->index('at_lng');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    }
};
