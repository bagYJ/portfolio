<?php

use App\Http\Controllers\Retail;
use Illuminate\Support\Facades\Route;

Route::controller(Retail::class)->prefix('pickup/retail')->group(function () {
    Route::get('info', 'info');
    Route::get('category', 'category');
    Route::get('info_detail', 'infoDetail');
    Route::get('review', 'review');
    Route::get('product_list', 'productList');
    Route::get('search_product', 'searchProduct');
    Route::get('product_info', 'productInfo');
});

$route = function () {
    Route::controller(Retail::class)->group(function () {
        Route::put('product_check', 'productCheck');
        Route::post('order_confirm', 'orderConfirm');
        Route::post('cancel_check', 'cancelCheck');
        Route::post('order_cancel', 'orderCancel');
        Route::post('cancel', 'orderCancel')->name('admin-auth');
        Route::post('order_ready', 'orderReady');
        Route::get('arrival_alarm/{noOrder}', 'arrivalAlarm')->name('auth');
        Route::post('arrival_confirm', 'arrivalConfirm');
        Route::post('delivery_alarm', 'deliveryAlarm');
        Route::post('delivery_confirm', 'deliveryConfirm');
        Route::post('shop_status_change', 'shopStatusChange');
    });
};

Route::group(['prefix' => 'retail', 'middleware' => 'auth:api'], $route);
Route::group(['prefix' => 'order_retail', 'middleware' => 'auth:api'], $route);
Route::group(['prefix' => 'ext/retail', 'middleware' => 'auth:sanctum'], $route);
