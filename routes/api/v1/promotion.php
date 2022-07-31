<?php

declare(strict_types=1);

use App\Http\Controllers\Promotion;
use Illuminate\Support\Facades\Route;

$route = function () {
    Route::controller(Promotion::class)->name('auth')->group(function () {
        Route::post('coupon', 'couponRegist');
        Route::post('coupon/gs', 'gsCouponRegist');
        Route::get('coupon/gs/{noEvent}', 'gsCouponDetail');
        Route::get('point_card', 'pointCardList');
        Route::post('point_card', 'pointCardRegist');
        Route::get('point_card/point/{idPointcard}', 'cardPoint');
        Route::delete('point_card/{idPointcard}', 'removePointCard');
    });
};

Route::group(['prefix' => 'promotion', 'middleware' => 'auth:api'], $route);
Route::group(['prefix' => 'ext/promotion', 'middleware' => 'auth:sanctum'], $route);
