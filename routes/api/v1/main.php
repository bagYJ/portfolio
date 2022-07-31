<?php

declare(strict_types=1);

use App\Http\Controllers\Main;
use Illuminate\Support\Facades\Route;

$route = function () {
    Route::controller(Main::class)->group(function () {
        Route::post('/', 'main');
        Route::get('notice', 'notice');
        Route::get('header', 'header');
    });
};

Route::group(['prefix' => 'main', 'middleware' => 'auth:api'], $route);
Route::group(['prefix' => 'ext/main', 'middleware' => 'auth:sanctum'], $route);
