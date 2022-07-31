<?php

declare(strict_types=1);

use App\Http\Controllers\Coupon;
use Illuminate\Support\Facades\Route;

$route = function () {
    Route::controller(Coupon::class)->name('auth')->group(function () {
        Route::get('lists', 'lists');
        Route::get('', 'detail');
    });
};

Route::group(['prefix' => 'coupon', 'middleware' => 'auth:api'], $route);
Route::group(['prefix' => 'ext/coupon', 'middleware' => 'auth:sanctum'], $route);
