<?php

declare(strict_types=1);

namespace App\Utils;

use Carbon\Carbon;

class Common
{
    public static function generatePid()
    {
        return time() . mt_rand(10000, 99999);
    }

    /**
     * return resource image path
     * @param string|null $path
     * @param string|null $prefix
     * @return string|null
     */
    public static function getImagePath(?string $path, ?string $prefix = ''): ?string
    {
        if ($path) {
            if (str_starts_with($path, 'http')) {
                return $path;
            } else {
                return Code::conf('image_path') . $prefix . $path;
            }
        }
        return null;
    }

    /**
     * 결제번호 생성
     * @return string
     */
    public static function generateNoPayment(): string
    {
        // 키 생성  - 14자리   2자리 버전 + stemp 변환 + 랜덤 2자리
        return (time() + 3000000000) . mt_rand(1000, 9999);
    }

    /**
     * make_no_action:: 이벤트 발생번호 생성
     * @return string
     */
    public static function generateNoAction()
    {
        return time() . mt_rand(100000000, 999999999);
    }

    /**
     * shell_send_ark_server 아크서버 소켓전송 - 백그라운드 쉘
     * @param string $header
     * @param string $body
     * @param string|null $server
     * @return void
     */
    public static function shellSendArkServer(string $header, string $body, ?string $server = ''): void
    {
//        queue로 대체
    }

    /**
     * 회원에게 보여지는 주문번호 - 날짜와 매장번호를 제거
     * @param string $noOrder
     * @return string
     */
    public static function getNoOrderForUser(string $noOrder): string
    {
        return substr($noOrder, -7);
    }

    /**
     * 할인률 변환 (무조건 내림으로 처리)
     * @param float|int|null $before
     * @param float|int|null $after
     * @return float|int
     */
    public static function getSaleRatio(float|int|null $before, float|int|null $after): float|int
    {
        // 할인가격이 정상인경우
        return match ($before && $before > $after) {
            true => floor(($before - $after) / $before * 100),
            default => 0
        };
    }

    /**
     * 월 주차 구하기
     * @param string $date
     * @return int
     */
    public static function getWeekByMonth(string $date): int
    {
        $w = Carbon::createFromFormat('Y-m-d', Carbon::parse($date)->format('Y-m-d'))->startOfMonth()->format('w');
        return intval(ceil(((int)$w + (int)date('j', strtotime($date)) - 1) / 7));
    }

    /**
     * 할인율 계산
     * @param int $price
     * @param int $rate
     * @return float
     */
    public static function getDiscountRate(int $price, int $rate): float
    {
        return round($price * ($rate * 0.01));
    }

    /**
     * 14자리 회원번호 생성
     * @return int
     */
    public static function generateNoUser(): int
    {
        // timestemp(10)  + rand(4) 16자리
        return (int)((time() + 1000000000) . mt_rand(100000, 999999));
    }

    /**
     * 요일 한글로 리턴
     * @param $weekday
     *
     * @return string
     */
    public static function getWeekDay($weekday): string
    {
        return match($weekday) {
            0 => '월요일',
            1 => '화요일',
            2 => '수요일',
            3 => '목요일',
            4 => '금요일',
            5 => '토요일',
            6 => '일요일',
            default => ''
        };
    }

    public static function xmlToJson($data)
    {
        $xml = simplexml_load_string((string)$data, "SimpleXMLElement", LIBXML_NOCDATA);
        return json_decode(json_encode($xml, JSON_UNESCAPED_UNICODE), true);
    }
}
