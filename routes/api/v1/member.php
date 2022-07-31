<?php

declare(strict_types=1);

use App\Http\Controllers\Member;
use Illuminate\Support\Facades\Route;

$route = function () {
    Route::controller(Member::class)->name('auth')->group(function () {
        Route::get('/', 'getUser');
        Route::get('order_list', 'getOrderList');
        Route::get('car', 'getCar');
        Route::post('car', 'registCar');
        Route::put('car', 'modifyCar');
        Route::get('car/{no}', 'getCarInfo');
        Route::delete('car/{no}', 'deleteCar');
        Route::put('withdrawal', 'withdrawal');
        Route::put('car/main/{no}', 'mainCar');
        Route::get('qna', 'getQnaList');
        Route::post('qna', 'registerQna');
    });

    Route::controller(Member::class)->group(function () {
        Route::post('/', 'regist');
        Route::put('/', 'modify');
    });
};

Route::group(['prefix' => 'member', 'middleware' => 'auth:api'], $route);
Route::group(['prefix' => 'ext/member', 'middleware' => 'auth:sanctum'], $route);

