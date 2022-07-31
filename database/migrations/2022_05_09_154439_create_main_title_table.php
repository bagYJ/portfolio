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
        Schema::create('main_title', function (Blueprint $table) {
            $table->string('cd_biz_kind')->primary();
            $table->string('text', 28);
            $table->string('app_route');
            $table->enum('yn_use', ['Y', 'N'])->default('Y')->comment('사용여부');
            $table->string('id_reg', 20)->nullable();
            $table->dateTime('dt_reg')->useCurrent();
            $table->string('id_upt', 20)->nullable();
            $table->dateTime('dt_reg')->nullable()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
//        Schema::dropIfExists('main_title');
    }
};
