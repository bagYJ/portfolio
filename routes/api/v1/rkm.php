<?php
declare(strict_types=1);

use App\Http\Controllers\Rkm\Card;
use App\Http\Controllers\Rkm\Coupon;
use App\Http\Controllers\Rkm\Member;
use App\Http\Controllers\Rkm\Oauth;
use App\Http\Controllers\Rkm\Order;
use App\Http\Controllers\Rkm\Promotion;
use Illuminate\Support\Facades\Route;

Route::prefix('rkm')->middleware('auth:sanctum')->group(function () {
    Route::prefix('member')->controller(Member::class)->group(function () {
        Route::name('auth')->group(function () {
            Route::get('/', 'getUser');
            Route::get('order_list', 'getOrderList');
            Route::get('car', 'getCar');
            Route::post('car', 'registCar');
            Route::put('car', 'modifyCar');
            Route::get('car/{no}', 'getCarInfo');
            Route::delete('car/{no}', 'deleteCar');
            Route::put('car/main/{no}', 'mainCar');
            Route::put('withdrawal', 'withdrawal');
        });

        Route::post('', 'regist');
        Route::put('check_regist', 'checkRegist');
    });

    Route::prefix('card')->controller(Card::class)->name('auth')->group(function () {
        Route::post('regist', 'regist');
        Route::get('lists', 'lists');
        Route::get('get_card_cnt', 'cardCnt');
        Route::delete('remove/{noCard}', 'remove');
        Route::put('main/{noCard}', 'mainCard');
    });

    Route::prefix('coupon')->controller(Coupon::class)->name('auth')->group(function () {
        Route::get('lists', 'lists');
        Route::get('', 'detail');
    });

    Route::prefix('oauth')->controller(Oauth::class)->group(function () {
        Route::name('auth')->group(function () {
            Route::get('get_regist_code', 'registCode');
            Route::get('get_access_check', 'accessCheck');
            Route::put('access_disconnect', 'accessDisconnect');
        });

        Route::put('refresh_token', 'refreshToken');
    });

    Route::prefix('promotion')->controller(Promotion::class)->name('auth')->group(function () {
        Route::get('point_card', 'pointCardList');
        Route::post('point_card', 'pointCardRegist');
        Route::get('point_card/point/{idPointcard}', 'cardPoint');
        Route::delete('point_card/{idPointcard}', 'removePointCard');
    });

    Route::prefix('order')->controller(Order::class)->name('auth')->group(function () {
        Route::get('list', 'getOrderList');
        Route::get('list/{bizKind}', 'getOrderListByBizKind');
        Route::get('detail/{bizKind}/{noOrder}', 'detail');
    });
});