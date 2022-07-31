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
        Schema::create('parking_site_ticket', function (Blueprint $table) {
            $table->unsignedBigInteger('no_product')->primary()->comment('티켓 고유번호');
            $table->unsignedBigInteger('no_parking_site')->comment('티켓 고유번호');

            $table->unsignedBigInteger('id_site')->comment('티켓 고유번호');
            $table->foreign('id_site')->references('id_site')->on('parking_site')->onUpdate(
                'cascade'
            )->onDelete('cascade');
            $table->string('nm_product', 100)->comment('할인권명');
            $table->unsignedInteger('cd_ticket_type')->comment('할인권 종류 코드');
            $table->unsignedInteger('cd_ticket_day_type')->comment('할인권 요일 코드');
            $table->string('ds_parking_start_time', 4)->comment('주차 가능 시간(시작)');
            $table->string('ds_parking_end_time', 4)->comment('주차 종료 시간(종료)');
            $table->string('ds_selling_days', 15)->comment('구매 가능 요일 (,로 구분)');
            $table->string('ds_selling_start_time', 4)->comment('구매 가능 시간(시작)');
            $table->string('ds_selling_end_time', 4)->comment('구매 가능 시간(종료)');
            $table->float('at_price')->default(0);
            $table->enum('cd_selling_status', Config('meta.parking.sellingStatus'))->default(
                Config('meta.parking.defaultSellingStatus')
            );
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
