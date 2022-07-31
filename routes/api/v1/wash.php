<?php

declare(strict_types=1);

use App\Http\Controllers\Wash;
use Illuminate\Support\Facades\Route;

$route = function () {
    Route::controller(Wash::class)->name('auth')->group(function () {
        Route::get('intro', 'intro');
        Route::post('payment', 'payment');
        Route::post('order_complete', 'orderComplete');
    });
};

Route::group(['prefix' => 'wash', 'middleware' => 'auth:api'], $route);
Route::group(['prefix' => 'ext/wash', 'middleware' => 'auth:sanctum'], $route);
