<?php

declare(strict_types=1);

use App\Http\Controllers\Action;
use Illuminate\Support\Facades\Route;

$route = function () {
    Route::controller(Action::class)->group(function () {
        Route::get('uptime_check/{article}', 'uptimeCheck')->name('auth');
        Route::post('location_save', 'locationSave');
        Route::delete('cache_clear/{key}', 'cacheClear');
    });
};

Route::group(['prefix' => 'action', 'middleware' => 'auth:api'], $route);
Route::group(['prefix' => 'ext/action', 'middleware' => 'auth:sanctum'], $route);
