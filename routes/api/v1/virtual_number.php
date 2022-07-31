<?php

declare(strict_types=1);

use App\Http\Controllers\VirtualNumber;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:api')->group(function () {
    Route::controller(VirtualNumber::class)->prefix('virtual_number')->group(function () {
        Route::post('get_list', 'getList');
        Route::post('set_auto_setting', 'setAutoSetting');
        Route::post('set_vn_close', 'setVnClose');
    });
});
