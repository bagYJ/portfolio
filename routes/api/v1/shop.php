<?php

declare(strict_types=1);

use App\Http\Controllers\Shop;
use Illuminate\Support\Facades\Route;

$route = function () {
    Route::controller(Shop::class)->group(function () {
        Route::name('auth')->group(function () {
            Route::post('review_regist', 'reviewRegist');
            Route::delete('review_remove/{noReview}', 'reviewRemove');
            Route::post('review_siren/{noReview}', 'reviewSiren');
        });

        Route::get('info/{noShop}', 'info');
        Route::get('review', 'review');
        Route::get('main_info', 'mainInfo');
        Route::get('commission_info', 'commissionInfo');
        Route::put('review', 'getReviewGrade');

        //main_info와 동일한 데이터이기 때문에 라우팅만 추가
        Route::get('wash_info', 'mainInfo');
    });
};

Route::group(['prefix' => 'shop', 'middleware' => 'auth:api'], $route);
Route::group(['prefix' => 'ext/shop', 'middleware' => 'auth:sanctum'], $route);
