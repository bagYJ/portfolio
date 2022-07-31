<?php

namespace App\Utils;

use App\Models\Shop;

class Oil
{
    /**
     * 주유될 리터량 계산
     * @param int $liter
     * @param int|float $literPrice
     * @return float
     */
    public static function getOilPriceCalculate(int|float $liter, int|float $literPrice): float
    {
        return $liter * $literPrice;
    }

    /**
     * 주유될 리터량 계산
     * @param int $amount
     * @param int|float $literPrice
     * @return float
     */
    public static function getLiterCalculate(int $amount, int|float $literPrice): float
    {
        return floor(($amount / $literPrice * 100)) / 100;
    }


    public static function arrivalMsg(Shop $shopInfo, array $orderInfo, bool $workingTime, bool $preCheckTime): array
    {
        $shopOptTime = $shopInfo['shopOptTime'][0] ?? null;
        $shopTxt = $shopInfo['partner']['nm_partner'] . ' ' . $shopInfo['nm_shop'];
        $workingTimeTxt = $shopOptTime ? $shopOptTime['ds_open_time'] . ':' . $shopOptTime['ds_close_time'] : '';
        $message = [
            "kr" => $shopTxt . "\nThe gas station is not open time at this time.\nOperating Hours: " . $workingTimeTxt,
            "en" => $shopTxt . "\n현재는 주유소 운영 시간이 아닙니다.\n운영 시간: " . $workingTimeTxt,
        ];
        if ($workingTime) {
            //영업시간 내
            if ($orderInfo) {
                $message['en'] = "Did you arrive at the gas station?\nPlease stop in front of the gas pump\n to start the reserved fueling.";
                $message['kr'] = "주유소에 도착하셨네요!\n주유기에 부착된 오윈번호를 입력하여 주유를 시작해 주세요.";
            } else {
                $message['en'] = "You arrived at the gas station!\nWould you like to use Smart Fueling?";
                $message['kr'] = "주유소에 도착하셨네요!\n\n스마트 주유를\n이용하시겠습니까?";
            }
        } elseif ($preCheckTime && !$orderInfo) {
            //23:45 ~ 24:00
            $message['en'] = "매일 23시 30분부터 23시 59분까지는 스마트 주유 시스템 점검 시간입니다.\n불편하시겠지만, 일반 주유 서비스를 이용해 주세요.";
            $message['kr'] = "매일 23시 30분부터 23시 59분까지는 스마트 주유 시스템 점검 시간입니다.\n불편하시겠지만, 일반 주유 서비스를 이용해 주세요.";
        } elseif ($shopInfo['shopHolidayExists']) {
            $message = match ($shopInfo['shopHolidayExists']['cd_imsi_reason']) {
                '216030' => [
                    'en' => $shopTxt . "\nThe gas station is not open time at this time.",
                    'kr' => $shopTxt . "\n임시 휴일로 서비스가 가능하지 않습니다.",
                ],
                '216990' => [
                    'en' => $shopTxt . "\nThe gas station is not open time at this time.",
                    'kr' => $shopTxt . "\n임시 휴일입니다.",
                ],
                default => [
                    'en' => $shopTxt . "\nThe gas station is not open time at this time.",
                    'kr' => $shopTxt . "\n장비 점검으로 현재 서비스 제공이 어렵습니다.",
                ]
            };
        }
        return $message;
    }
}
