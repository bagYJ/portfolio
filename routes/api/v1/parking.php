<?php

declare(strict_types=1);

use App\Http\Controllers\Parking;
use Illuminate\Support\Facades\Route;

$route = function () {
    Route::controller(Parking::class)->group(function () {
        Route::get('intro', 'intro')->name('auth'); //주문 정보 조회
        Route::get('get_my_tickets', 'getMyTickets')->name('auth'); // 나의 주문정보 조회
        Route::get('get_ticket', 'getTicket')->name('auth'); // 나의 주문정보 단일 조회
        Route::post('order_ticket', 'orderTicket')->name('auth'); // 주차 웹 할인권 구매
        Route::post('cancel_ticket', 'cancelTicket')->name('auth'); // 주차 웹 할인권 취소

        Route::post('gets', 'gets'); // 주차장 정보 조회
        Route::get('get/{no_site}', 'get'); // 주차장 정보 단일 조회
        Route::post('auto_cancel', 'autoCancelTicket')->name('admin-auth'); // 주차권 자동 취소 배치
        Route::get('admin_auto_cancel/{noOrder}', 'adminCancelTicket')->name('admin-auth');
    });
};


Route::group(['prefix' => 'parking', 'middleware' => 'auth:api'], $route);
Route::group(['prefix' => 'ext/parking', 'middleware' => 'auth:sanctum'], $route);
