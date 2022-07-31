<?php

declare(strict_types=1);

use App\Http\Controllers\Tmap\Order;
use App\Http\Controllers\Tmap\OrderOil;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    Route::controller(Order::class)->prefix("tmap/order")->name('auth')->group(function () {
        Route::get('queue_list', 'gets');
    });

    Route::controller(OrderOil::class)->prefix("tmap/order_oil")->name('auth')->group(function () {
        Route::get('init', 'init');
        Route::post('payment', 'payment');
        Route::get('detail', 'detail');
        Route::get('oil_dp_list', 'oilDpList');
        Route::post('oil_dpno_regist', 'oilDpnoRegist');
        Route::post('cancel', 'cancel');
    });
});
