<?php

declare(strict_types=1);

use App\Http\Controllers\Customer;
use Illuminate\Support\Facades\Route;

$route = function () {
    Route::controller(Customer::class)->group(function () {
        Route::get('list', 'getEventList');
        Route::get('{no}', 'getEvent')->where('no', '[0-9]+');
    });

    Route::controller(Customer::class)->name('auth')->group(function () {
        Route::get('faq', 'getFaqList');
        Route::put('event_push_msg', 'modifyEventPushYn');
        Route::get('push_msg', 'getEventPush');
    });
};

Route::group(['prefix' => 'customer', 'middleware' => 'auth:api'], $route);
Route::group(['prefix' => 'ext/customer', 'middleware' => 'auth:sanctum'], $route);
