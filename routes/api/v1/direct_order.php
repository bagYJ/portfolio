<?php

declare(strict_types=1);

use App\Http\Controllers\DirectOrder;
use Illuminate\Support\Facades\Route;

$route = function () {
    Route::controller(DirectOrder::class)->name('auth')->group(function () {
        Route::get('', 'list');
        Route::post('', 'create');
        Route::delete('{no}', 'remove');
    });
};

Route::group(['prefix' => 'direct_order', 'middleware' => 'auth:api'], $route);
Route::group(['prefix' => 'ext/direct_order', 'middleware' => 'auth:sanctum'], $route);