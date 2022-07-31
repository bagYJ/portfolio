<?php

declare(strict_types=1);

use App\Http\Controllers\Search;
use Illuminate\Support\Facades\Route;

$route = function () {
    Route::controller(Search::class)->group(function () {
        Route::get('tag', 'tag');
    });
};

Route::group(['prefix' => 'search', 'middleware' => 'auth:api'], $route);
Route::group(['prefix' => 'ext/search', 'middleware' => 'auth:sanctum'], $route);
