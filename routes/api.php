<?php

use App\Http\Controllers\Ark;
use App\Http\Controllers\AutoParking;
use App\Http\Controllers\AutoWash;
use App\Http\Controllers\Bizcall;
use App\Http\Controllers\Fnb\Spc;
use App\Http\Controllers\Gas;
use App\Http\Controllers\Infine;
use App\Http\Controllers\Mobility;
use App\Http\Controllers\Parking;
use App\Http\Controllers\Pg;
use App\Http\Controllers\Push;
use App\Http\Controllers\Retail\Cu;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
//
//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::controller(Cu::class)->prefix('retail/cu')->group(function () {
//    owin >> cu
    Route::post('product-check', 'productCheck');
    Route::post('cancel-check', 'cancelCheck');
    Route::post('order-cancel', 'orderCancel');
    Route::post('arrival-alarm', 'arrivalAlarm');
    Route::post('delivery-alarm', 'deliveryAlarm');
    Route::get('order/{noOrder}', 'order');
//    cu >> owin
    Route::post('order-confirm', 'orderConfirm')->name('no-auth');
    Route::post('order-ready', 'orderReady')->name('no-auth');
    Route::post('arrival-confirm', 'arrivalConfirm')->name('no-auth');
    Route::post('delivery-confirm', 'deliveryConfirm')->name('no-auth');
});

Route::controller(Bizcall::class)->prefix('bizcall')->group(function () {
    Route::get('', 'getVns');
    Route::post('auto-mapping', 'autoMapping');
    Route::put('close-mapping/{virtualNumber}', 'closeMapping');
});

Route::controller(Push::class)->prefix('push')->group(function () {
    Route::post('manager', 'sendManager');
    Route::post('user', 'sendUser');
});

Route::controller(Ark::class)->prefix('ark')->group(function () {
    Route::post('payment', 'payment');
    Route::post('order', 'order');
    Route::post('call', 'call');
    Route::post('preset', 'preset');
    Route::post('wash', 'wash');
});

Route::controller(Gas::class)->prefix('gas')->group(function () {
    Route::post('coupon', 'coupon');
});

Route::controller(Parking::class)->prefix('parking')->group(function () {
    Route::prefix('bookings')->group(function () {
        Route::post('', 'order');
        Route::get('', 'orderList');
        Route::post('search', 'orderSearch');
        Route::get('{bookingUid}', 'orderDetail');
        Route::put('{bookingUid}/cancel', 'cancel');
        Route::put('{bookingUid}/used', 'used');
    });
    Route::get('{siteUid}', 'site');
});
Route::controller(Spc::class)->prefix('spc')->group(function () {
//    owin >> spc
    Route::post('stock', 'stock');
    Route::post('order/{noOrder}', 'order');
    Route::post('cancel', 'cancel');
    Route::post('uptime', 'uptime');

//    spc  >> owin
    Route::post('status/shop', 'shopStatusChange')->name('no-auth');
    Route::post('status/order', 'orderStatusChange')->name('no-auth');
    Route::post('status/product', 'productStatusChange')->name('no-auth');
});

Route::controller(AutoParking::class)->prefix('auto-parking')->group(function () {
//    owin >> mobilx
    Route::get('', 'parkingList');
    Route::post('regist-car', 'registCar');
    Route::post('check-fee', 'checkFee');
    Route::post('payment', 'payment');
    Route::post('refund', 'refund');
//    mobilx >> owin
    Route::post('enter', 'enter')->name('no-auth');
    Route::post('exit', 'exit')->name('no-auth');
});

Route::controller(AutoWash::class)->prefix('auto-wash')->group(function () {
    Route::get('{noShop}', 'info');
    Route::get('intro/{noShop}', 'intro');
//    Route::post('payment', 'payment');
//    Route::get('complete/{noOrder}', 'complete');
    Route::get('detail/{noOrder}', 'detail');
});

Route::controller(Pg::class)->prefix('pg')->group(function () {
    Route::post('regist', 'regist');
    Route::post('payment', 'payment');
    Route::post('refund', 'refund');
});

Route::controller(Mobility::class)->prefix('mobility')->group(function () {
    Route::post('prdtStatus', 'prdtStatus');
    Route::post('prdtCancel', 'prdtCancel');
});

Route::controller(Infine::class)->prefix('infine')->group(function () {
    Route::get('list', 'list');
    Route::get('{noOrder}', 'init');
    Route::post('cancel', 'cancel');
    Route::post('approval', 'approval');
    Route::post('approval-result', 'approvalResult');
//    todo mock data (테스트 데이터로 추후 삭제 예정)
    Route::prefix('mock')->name('no-auth')->group(function () {
        Route::get('list', 'mockList');
        Route::post('{noOrder}', 'mockInit');
        Route::post('cancel', 'mockCancel');
        Route::post('approval', 'mockApproval');
        Route::post('approval-result', 'mockApprovalResult');
    });
});