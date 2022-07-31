<?php

declare(strict_types=1);

use App\Http\Controllers\Apt;
use Illuminate\Support\Facades\Route;

$route = function () {
    Route::controller(Apt::class)->name('auth')->group(function () {
        Route::get('member', 'getMemberAptList');
        Route::post('{idApt}', 'register');
        Route::delete('{idApt}', 'remove');
        Route::get('', 'list');
    });
};

Route::group(['prefix' => 'apt', 'middleware' => 'auth:api'], $route);
Route::group(['prefix' => 'ext/apt', 'middleware' => 'auth:sanctum'], $route);
