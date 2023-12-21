<?php

use App\Http\Controllers\AutoParking;
use App\Http\Controllers\Fnb\Spc;
use App\Http\Controllers\Mobility;
use App\Http\Controllers\Retail\Cu;
use Illuminate\Support\Facades\Route;

/** CU START **/
Route::controller(Cu::class)->prefix('retail')->group(function () {
    //    cu >> owin
    Route::post('order_confirm', 'orderConfirm')->name('no-auth');
    Route::post('order_ready', 'orderReady')->name('no-auth');
    Route::post('arrival_confirm', 'arrivalConfirm')->name('no-auth');
    Route::post('delivery_confirm', 'deliveryConfirm')->name('no-auth');
});
/** CU END **/

/** SPC START **/
Route::controller(Spc::class)->prefix('spc')->group(function () {
//    spc  >> owin
    Route::post('status/shop', 'shopStatusChange')->name('no-auth');
    Route::post('status/order', 'orderStatusChange')->name('no-auth');
    Route::post('status/product', 'productStatusChange')->name('no-auth');
});

Route::controller(Spc::class)->prefix('happy_order')->group(function () {
//    spc  >> owin
    Route::post('shop_status_change', 'shopStatusChange')->name('no-auth');
    Route::post('order_status_change', 'orderStatusChange')->name('no-auth');
    Route::post('product_status_change', 'productStatusChange')->name('no-auth');
});
/** SPC END **/

/** AUTO-PARKING START **/
Route::controller(AutoParking::class)->prefix('auto_parking')->group(function () {
//    mobilx >> owin
    Route::post('enter', 'enter')->name('no-auth');
    Route::post('exit', 'exit')->name('no-auth');
});
/** AUTO-PARKING END **/

Route::controller(Mobility::class)->prefix('mobility')->group(function () {
    Route::post('prdtStatus', 'prdtStatus');
    Route::post('prdtCancel', 'prdtCancel');
});