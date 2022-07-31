<?php

declare(strict_types=1);

use App\Http\Controllers\Car;
use Illuminate\Support\Facades\Route;

$route = function () {
    Route::controller(Car::class)->group(function () {
        Route::get('maker', 'makerList');
        Route::get('kind_by_car/{noMaker}', 'kindByCarList');
    });
};

Route::group(['prefix' => 'car', 'middleware' => 'auth:api'], $route);
Route::group(['prefix' => 'ext/car', 'middleware' => 'auth:sanctum'], $route);
