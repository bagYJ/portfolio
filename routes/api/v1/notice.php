<?php

declare(strict_types=1);

use App\Http\Controllers\Notice;
use Illuminate\Support\Facades\Route;

$route = function () {
    Route::controller(Notice::class)->group(function () {
        Route::get('/gets', 'gets');
        Route::get('/get/{no}', 'get');
    });
};

Route::group(['prefix' => 'notice', 'middleware' => 'auth:api'], $route);
Route::group(['prefix' => 'ext/notice', 'middleware' => 'auth:sanctum'], $route);
