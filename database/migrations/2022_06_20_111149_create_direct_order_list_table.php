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
        Schema::create('direct_order_list', function (Blueprint $table) {
            $table->comment('바로 주문');

            $table->id('no');
            $table->unsignedBigInteger('no_user')->comment('회원 번호');
            $table->string('cd_biz_kind', 6)->comment('업종구분');
            $table->string('no_order', 30)->comment('주문번호');
            $table->unsignedInteger('ct_order')->default(0)->comment('정렬순서');
            $table->dateTime('dt_reg')->useCurrent()->comment('등록일자');

            $table->unique(['no_user','cd_biz_kind', 'no_order'], 'unique_key');
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
