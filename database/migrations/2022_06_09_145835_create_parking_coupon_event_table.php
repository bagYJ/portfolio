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
        Schema::create('parking_coupon_event', function (Blueprint $table) {
            $table->id('no');
            $table->string('nm_event')->comment('쿠폰명');
            $table->json('no_sites')->nullable()->comment('사용가능 매장');
            $table->string('cd_cpe_status', 6)->default('121100')->comment('쿠폰상태-default:사용가능');
            $table->string('cd_disct_type', 6)->default('126100')->comment('쿠폰할인구분(126100:금액할인, 126200:할인율할인)');
            $table->integer('at_disct_money')->default(0)->comment('할인금액');
            $table->double('at_disct_rate', 8, 2)->default(0)->comment('할인율');
            $table->date('dt_start')->nullable()->comment('쿠폰사용 시작일자');
            $table->date('dt_end')->nullable()->comment('쿠폰사용 종료일자');
            $table->integer('at_expire_day')->nullable()->comment('쿠폰 만료일 (발급일로부터 n일)');

            $table->dateTime('dt_reg')->useCurrent();
            $table->dateTime('dt_upt')->nullable()->useCurrentOnUpdate();
            $table->dateTime('dt_del')->nullable();
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
