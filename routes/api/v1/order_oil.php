<?php

declare(strict_types=1);

use App\Http\Controllers\OrderOil;
use Illuminate\Support\Facades\Route;

$route = function () {
    Route::controller(OrderOil::class)->group(function () {
        Route::get('intro', 'intro')->name('auth');
        Route::post('payment', 'payment')->name('auth');
        Route::get('detail/{noOrder}', 'detail')->name('auth');
        Route::put('cancel/{noOrder}', 'cancel')->name('auth');
        Route::get('check/{noOrder}', 'check')->name('auth');

        Route::post('preset_check', 'presetCheck')->name('auth');
        Route::post('qr_regist', 'qrRegist')->name('auth');
        Route::get('order_process/{noOrder}', 'orderProcessOil')->name('auth');

        Route::get('oil_dp_list/{noOrder}', 'oilDpList');
        Route::get('oil_gas_list/{noShop}', 'oilGasList');
    });
};

Route::group(['prefix' => 'order_oil', 'middleware' => 'auth:api'], $route);
Route::group(['prefix' => 'ext/order_oil', 'middleware' => 'auth:sanctum'], $route);
