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
        Schema::create('parking_order_process', function (Blueprint $table) {
            $table->id('no')->comment('주차장 고유번호');
            $table->unsignedBigInteger('no_order');
            $table->unsignedBigInteger('no_user')->comment('회원 번호');

            $table->string('id_site', 20)->nullable()->comment('외부 사이트 정의 키값');

            $table->unsignedBigInteger('no_parking_site')->nullable()->comment('카멜레온 주차장 고유번호');
            $table->string('id_auto_parking', 100)->nullable()->comment('자동 결제 주차장 고유번호');

            $table->string('cd_order_process', 6)->comment('처리상태코드');
            $table->dateTime('dt_order_process')->useCurrent();

            $table->index('no_order');
            $table->index('no_user');
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
