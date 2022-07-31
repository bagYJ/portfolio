<?php

declare(strict_types=1);

use App\Http\Controllers\Oauth;
use Illuminate\Support\Facades\Route;

$route = function () {
    Route::controller(Oauth::class)->group(function () {
        /** 회원인증(앱에서 6자리 인증코드 발급 후 차량에서 인증) **/
        Route::post('authorization', 'authorization');
        /** 토큰 발급 **/
        Route::post('token', 'token');
        /** 토큰 재발급(리프레시 토큰 사용) **/
        Route::post('refresh_token', 'refreshToken');

        Route::name('auth')->group(function () {
            /** 인증 코드 발급 **/
            Route::get('get_regist_code', 'registCode');
            /** 발급 토큰 전체 삭제**/
            Route::delete('token', 'deleteTokens');
            Route::get('get_access_check', 'accessCheck');
            Route::put('access_disconnect', 'accessDisconnect');
        });
    });
};

Route::group(['prefix' => 'oauth', 'middleware' => 'auth:api'], $route);
Route::group(['prefix' => 'ext/oauth', 'middleware' => 'auth:sanctum'], $route);