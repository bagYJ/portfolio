<?php

declare(strict_types=1);

use App\Http\Controllers\Product;
use Illuminate\Support\Facades\Route;

$route = function () {
    Route::controller(Product::class)->group(function () {
        Route::get('get_list/{noShop}', 'getList');
        Route::post('set_gps', 'setGps');
        Route::get('{noShop}/{noProduct}', 'product');

        // getList와 동일한 결과값으로 routing만 추가해둠
        Route::get('get_shop_product_list/{noShop}/{noCategory?}', 'getList');
    });
};

Route::group(['prefix' => 'product', 'middleware' => 'auth:api'], $route);
Route::group(['prefix' => 'ext/product', 'middleware' => 'auth:sanctum'], $route);
