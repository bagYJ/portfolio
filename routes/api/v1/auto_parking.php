<?php

declare(strict_types=1);

use App\Http\Controllers\AutoParking;
use Illuminate\Support\Facades\Route;

$route = function () {
    Route::controller(AutoParking::class)->group(function () {
        Route::get('check_payment', 'checkPayment')->name('auth');

        Route::get('gets', 'getMyAutoParking')->name('auth');
        Route::get('test', 'test');
        Route::post('register', 'registerCar')->name('auth');
        Route::post('enter', 'carEntered');
        Route::get('check', 'checkFee')->name('auth');
        Route::post('exit', 'carExited');
    });
};

Route::group(['prefix' => 'auto_parking', 'middleware' => 'auth:api'], $route);
Route::group(['prefix' => 'ext/auto_parking', 'middleware' => 'auth:sanctum'], $route);
