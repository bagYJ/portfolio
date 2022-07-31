<?php

declare(strict_types=1);

use App\Http\Controllers\Order;
use Illuminate\Support\Facades\Route;

$route = function () {
    Route::controller(Order::class)->name('auth')->group(function () {
        Route::get('list', 'getOrderList');
        Route::get('list/{bizKind}', 'getOrderListByBizKind');
        Route::get('detail/{bizKind}/{noOrder}', 'detail');
        Route::put('gps_alarm', 'gpsAlarm');
        Route::get('history_cnt', 'historyCnt');
        Route::post('init', 'init');
        Route::post('payment', 'payment');
        Route::get('order_status_history/{noOrder}', 'orderStatusHistory');
        Route::get('incomplete/{bizKind}/{noOrder}', 'getIncompleteOrder');

    });
};

Route::group(['prefix' => 'order', 'middleware' => 'auth:api'], $route);
Route::group(['prefix' => 'ext/order', 'middleware' => 'auth:sanctum'], $route);
