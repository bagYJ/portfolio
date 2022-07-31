<?php

declare(strict_types=1);

namespace App\Utils;

use Illuminate\Support\Arr;

/**
 * yml 코드값 전달
 * 하위 노드의 경우 .으로 표현
 * ex) Code::message('0000.123');
 * env 로드시 ${env name} 사용
 */
class Code
{
    public static function card(?string $string = null): mixed
    {
        return Arr::get(getYml('card'), $string);
    }

    public static function conf(?string $string = null): mixed
    {
        return Arr::get(getYml(), $string);
    }

    public static function packet(?string $string = null): mixed
    {
        return Arr::get(getYml('packet'), $string);
    }

    public static function message(?string $string = null): mixed
    {
        return Arr::get(getYml('message'), $string);
    }

    public static function fcm(?string $string = null): mixed
    {
        return Arr::get(getYml('fcm'), $string);
    }

    public static function operate(?string $string = null): mixed
    {
        return Arr::get(getYml('operate'), $string);
    }

    public static function evcharger(?string $string = null): mixed
    {
        return Arr::get(getYml('ev_charger'), $string);
    }
}
