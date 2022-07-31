<?php

namespace App\Services;

use App\Enums\GasKind;
use App\Models\ShopOilPrice;
use App\Utils\Oil;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class ShopOilPriceService
{
    /**
     * tmap 주유소 유종 정보 반환
     *
     * @param int $noShop
     * @return array
     */
    public static function getShopOilPrice(int $noShop): array
    {
        $shopOilPrices = ShopOilPrice::select([
            'at_price',
            'cd_gas_kind',
        ])->where([
            'no_shop' => $noShop
        ])->get();


        $result = [];
        if (count($shopOilPrices)) {
            $oilPrice1 = 30000;
            $oilPrice2 = 50000;
            $oilPrice3 = 70000;
            $oilPrice4 = 149900; //가득
            $oilLiter1 = 20;
            $oilLiter2 = 30;
            $oilLiter3 = 40;

            foreach ($shopOilPrices as $oilPrice) {
                $result[] = [
                    'cd_gas_kind' => $oilPrice['cd_gas_kind'],
                    'nm_gas_kind' => GasKind::from(intval($oilPrice['cd_gas_kind']))->name,
                    'at_oil_price' => $oilPrice['at_price'],
                    'at_oil_price1' => $oilPrice1,
                    'at_oil_litre1' => Oil::getLiterCalculate($oilPrice1, $oilPrice['at_price']),
                    'at_oil_price2' => $oilPrice2,
                    'at_oil_litre2' => Oil::getLiterCalculate($oilPrice2, $oilPrice['at_price']),
                    'at_oil_price3' => $oilPrice3,
                    'at_oil_litre3' => Oil::getLiterCalculate($oilPrice3, $oilPrice['at_price']),
                    'at_oil_litre4' => $oilLiter1,
                    'at_oil_price4' => Oil::getOilPriceCalculate($oilLiter1, $oilPrice['at_price']),
                    'at_oil_litre5' => $oilLiter2,
                    'at_oil_price5' => Oil::getOilPriceCalculate($oilLiter2, $oilPrice['at_price']),
                    'at_oil_litre6' => $oilLiter3,
                    'at_oil_price6' => Oil::getOilPriceCalculate($oilLiter3, $oilPrice['at_price']),
                    'at_oil_price7' => $oilPrice4,
                    'at_oil_litre7' => Oil::getLiterCalculate($oilPrice4, $oilPrice['at_price']),
                ];
            }
        }

        return [
            'list_gas_kind' => $shopOilPrices,
            'list_oil_info' => $result,
        ];
    }

    /**
     * 주유 가격 리스트
     *
     * @param int $noShop
     * @return Collection
     */
    public static function shopOilPrice(int $noShop): Collection
    {
        return ShopOilPrice::where([
            'no_shop' => $noShop
        ])->get()->map(function ($oilPrice) {
            $date = Carbon::createFromFormat('Y-m-d H:i:s', $oilPrice['dt_trade'])->format('Y-m-d');
            $time = Carbon::createFromFormat('Y-m-d H:i:s', $oilPrice['tm_trade'])->format('H:i:s');
            return [
                'cd_gas_kind' => GasKind::from(intval($oilPrice['cd_gas_kind']))->name,
                'at_oil_price' => $oilPrice['at_price'],
                'dt_trade' => $date . ' ' . $time,
                'prices' => [
                    ['price' => 30000, 'liter' => Oil::getLiterCalculate(30000, $oilPrice['at_price'])],
                    ['price' => 50000, 'liter' => Oil::getLiterCalculate(50000, $oilPrice['at_price'])],
                    ['price' => 70000, 'liter' => Oil::getLiterCalculate(70000, $oilPrice['at_price'])],
                    ['price' => 149900, 'liter' => Oil::getLiterCalculate(149900, $oilPrice['at_price'])],
                ],
                'liters' => [
                    ['price' => Oil::getOilPriceCalculate(20, $oilPrice['at_price']), 'liter' => 20],
                    ['price' => Oil::getOilPriceCalculate(30, $oilPrice['at_price']), 'liter' => 30],
                    ['price' => Oil::getOilPriceCalculate(40, $oilPrice['at_price']), 'liter' => 40],
                    ['price' => 149900, 'liter' => Oil::getLiterCalculate(149900, $oilPrice['at_price'])],
                ]
            ];
        });
    }
}
