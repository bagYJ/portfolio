<?php

declare(strict_types=1);

use App\Http\Controllers\Partner;
use Illuminate\Support\Facades\Route;

$route = function () {
    Route::controller(Partner::class)->group(function () {
        Route::post('lists', 'gets');
    });
};

Route::group(['prefix' => 'partner', 'middleware' => 'auth:api'], $route);
Route::group(['prefix' => 'ext/partner', 'middleware' => 'auth:sanctum'], $route);
