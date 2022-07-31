<?php

declare(strict_types=1);

use App\Http\Controllers\Card;
use Illuminate\Support\Facades\Route;

$route = function () {
    Route::controller(Card::class)->name('auth')->group(function () {
        Route::post('regist', 'regist');
        Route::get('lists', 'lists');
        Route::get('get_card_cnt', 'cardCnt');
        Route::delete('remove/{noCard}', 'remove');
        Route::put('main/{noCard}', 'mainCard');
    });
};

Route::group(['prefix' => 'card', 'middleware' => 'auth:api'], $route);
Route::group(['prefix' => 'ext/card', 'middleware' => 'auth:sanctum'], $route);
