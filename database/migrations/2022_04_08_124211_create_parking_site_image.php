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
        Schema::create('parking_site_image', function (Blueprint $table) {
            $table->id('no');
            $table->unsignedBigInteger('no_parking_site')->comment('티켓 고유번호');
            $table->unsignedBigInteger('id_site')->comment('티켓 고유번호');
            $table->foreign('id_site')->references('id_site')->on('parking_site')->onUpdate(
                'cascade'
            )->onDelete('cascade');
            $table->string('ds_image_url');

            $table->unique(['id_site', 'ds_image_url']);
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
