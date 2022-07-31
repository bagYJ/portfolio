<?php

declare(strict_types=1);

use App\Http\Controllers\Version;
use Illuminate\Support\Facades\Route;

$route = function () {
    Route::controller(Version::class)->group(function () {
        Route::get('/', 'get');
    });
};

Route::group(['prefix' => 'version', 'middleware' => 'auth:api'], $route);
Route::group(['prefix' => 'ext/version', 'middleware' => 'auth:sanctum'], $route);
