<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        User::whereNotNull('ds_passwd')->get()->map(function ($user) {
            $user->update([
                'ds_passwd_api' => bcrypt($user->ds_passwd)
            ]);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        User::update([
            'ds_passwd_api' => null
        ]);
    }
};
