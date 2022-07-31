<?php

use App\Http\Controllers\Address;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});

//Route::prefix('address')->group(function () {
//    Route::get('lists', [Address::class, 'lists']);
//});

//Route::prefix('oauth')->group(function () {
//
//    Route::post('authorization', [Oauth::class, 'authorization']);
//
//    Route::post('login', [Oauth::class, 'login']);
//    /** 토큰 발급 **/
//    Route::post('token', [Oauth::class, 'token']);
//    /** 토큰 재발급(리프레시 토큰 사용) **/
//    Route::post('refresh_token', [Oauth::class, 'refreshToken']);
//
//    Route::middleware('auth:api')->group(function () {
//        /** 인증 코드 발급 **/
//        Route::get('get_regist_code', [Oauth::class, 'registCode']);
//        /** 발급 토큰 전체 삭제**/
//        Route::delete('tokens', [Oauth::class, 'deleteTokens']);
//    });
//});
//
//Route::prefix('nav')->group(function () {
//    Route::prefix('oauth')->group(function () {
//        Route::post('token', [NavOauth::class, 'token']);
//
//        Route::get('user', [NavOauth::class, 'user'])->middleware('auth:sanctum');
//    });
//
//    Route::middleware('auth:sanctum')->group(function () {
//        Route::prefix('address')->group(function () {
//            Route::get('lists', [Address::class, 'lists']);
//            Route::post('regist', [Address::class, 'regist']);
//        });
//
//        Route::prefix('config')->group(function () {
//            Route::get('profile', [Config::class, 'profile']);
//            Route::post('profile_edit', [Config::class, 'profileEdit']);
//        });
//
//        Route::prefix('card')->group(function () {
//            Route::post('regist', [Card::class, 'regist']);
//        });
//    });
//});
//
//Route::get('info', function () {
//    phpinfo();
//});

//Route::prefix('nav/oauth')->group(function () {
//    Route::post('token', [NavOauth::class, 'token']);
//
//    Route::middleware('auth:sanctum')->group(function () {
//        Route::get('user', [NavOauth::class, 'user']);
//
//        Route::prefix('address')->group(function () {
//            Route::get('lists', [Address::class, 'lists']);
//            Route::post('regist', [Address::class, 'regist']);
//        });
//
//        Route::prefix('config')->group(function () {
//            Route::get('profile')
//        });
//    });
//});

//Route::prefix('member')->group(function () {
//    Route::post('login', [Member::class, 'login']);
//});
//
///**
// * 인증 토큰 발급
// */
//Route::prefix('dummy')->group(function () {
//    Route::post('refresh_token', [Dummy::class, 'refreshToken']);
//
//    Route::middleware('auth:api')->group(function () {
//        Route::post('login_nav', [Dummy::class, 'loginNav']);
//    });
//});
