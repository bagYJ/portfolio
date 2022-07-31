<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('member_parking_coupon', function (Blueprint $table) {
            $table->id('no');
            $table->unsignedBigInteger('no_user')->comment('회원 번호');
            $table->unsignedBigInteger('no_event')->comment('쿠폰 번호');
            $table->string('nm_event', 50)->comment('쿠폰명');

            $table->unsignedBigInteger('no_order')->nullable()->comment('주문 번호');
            $table->unsignedInteger('at_price')->nullable()->comment('주문 금액');

            $table->string('use_coupon_yn', 1)->default('N')->comment('쿠폰사용가능여부-default:불가');
            $table->string('cd_mcp_status', 6)->default('122100')->comment('쿠폰상태-default:미사용');

            $table->json('no_sites')->nullable()->comment('사용가능 매장');

            $table->string('cd_disct_type', 6)->default(126100)->comment('쿠폰할인구분(126100:금액할인, 126200:할인율할인)');
            $table->unsignedInteger('at_disct_money')->default(0)->comment('할인금액');
            $table->double('at_disc_rate', 8, 2)->default(0)->comment('할인율');

            $table->date('dt_use_start')->nullable()->comment('쿠폰사용 시작일자');
            $table->date('dt_use_end')->nullable()->comment('쿠폰사용 종료일자');
            $table->integer('at_expire_day')->nullable()->comment('쿠폰 만료일 (발급일로부터 n일)');

            $table->string('cd_issue_kind', 6)->default('131100')->comment('쿠폰발급종류-default:자동');
            $table->string('cd_calculate_main', 6)->default('618200')->comment('쿠폰상태-default:오윈');

            $table->dateTime('dt_reg')->useCurrent();
            $table->dateTime('dt_upt')->nullable()->useCurrentOnUpdate();
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
