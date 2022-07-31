<?php

declare(strict_types=1);

use App\Http\Controllers\EvCharger;
use Illuminate\Support\Facades\Route;

$route = function () {
    Route::controller(EvCharger::class)->group(function () {
        Route::get('filter', 'filter');
        Route::get('info/{idStat}', 'info');
    });
};

Route::group(['prefix' => 'ev_charger', 'middleware' => 'auth:api'], $route);
Route::group(['prefix' => 'ext/ev_charger', 'middleware' => 'auth:sanctum'], $route);
