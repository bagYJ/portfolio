<?php

declare(strict_types=1);

use App\Http\Controllers\Payment;
use Illuminate\Support\Facades\Route;

$route = function () {
    Route::controller(Payment::class)->group(function () {
        Route::put('cancel', 'cancel')->name('auth');
        Route::post('cancel_admin', 'cancelAdmin')->name('admin-auth');
        Route::post('incomplete', 'incompletePayment')->name('auth');
    });
};

Route::group(['prefix' => 'payment', 'middleware' => 'auth:api'], $route);
Route::group(['prefix' => 'ext/payment', 'middleware' => 'auth:sanctum'], $route);
