<?php

declare(strict_types=1);

use App\Http\Controllers\Tmap;
use Illuminate\Support\Facades\Route;

//$route = function () {
Route::middleware('auth:sanctum')->group(function () {
    Route::controller(Tmap::class)->prefix('tmap')->name('auth')->group(function () {
        Route::get('member/get_info', 'getInfo');          // Tmap 로그아웃
        Route::get('member/get_car_list', 'userCarList');       // 회원 자동차 리스트
        Route::post('oauth/logout', 'logout');            // Tmap 로그아웃
        Route::get('card/lists', 'userCardList');      // 회원 등록 카드 리스트 반환
        Route::get('order/lists', 'userOrderList');      // 회원 주문 리스트 정보 반황
        Route::get('order/detail', 'userOrderDetail');    // 회원 주문 정보 상세

        Route::post('oauth/tmapauth', 'authorization');     // Tmap 회원인증
    });
});
Route::controller(Tmap::class)->prefix('tmap')->name('auth')->group(function () {
    Route::post('oauth/tmapauth', 'authorization');     // Tmap 회원인증
    Route::get('search/opinet_list', 'opinetList');     // Tmap 회원인증
    Route::get('search/get', 'get');     // Tmap 회원인증
});

//Route::group(['prefix' => 'ext/shop', 'middleware' => 'auth:sanctum'], $route);
