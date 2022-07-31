<?php

namespace App\Services;

use App\Exceptions\OwinException;
use App\Models\MemberShopEnterLog;
use App\Models\OrderList;
use App\Models\OrderQrReader;
use App\Models\ShopOilPrice;
use App\Models\ShopOilUnit;
use App\Utils\Code;
use App\Utils\Oil;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Throwable;

class OilService
{

    /**
     * 오늘 주문내역 카운트 반환
     * @param int $noUser
     * @param int $noShop
     *
     * @return int
     */
    public static function getTodayOrderCnt(int $noUser, int $noShop): int
    {
        return OrderList::where([
            ['no_user', '=', $noUser],
            ['no_shop', '=', $noShop],
            ['cd_pickup_status', '<', '602400'],
            ['dt_reg', '>', DB::raw("CURRENT_DATE")]
        ])->count();
    }

    /**
     * 주유소 유가정보 반환
     * @param string $noShop
     * @param string $cdGasKind
     *
     * @return Collection
     */
    public static function getOilPrice(string $noShop, string $cdGasKind = '')
    {
        $where = [
            'no_shop' => $noShop
        ];
        if ($cdGasKind) {
            $where['cd_gas_kind'] = $cdGasKind;
        }
        return ShopOilPrice::where($where)->get();
    }

    /**
     * 주유 리터 정보 반환
     *
     * @param string $noShop
     * @param int $price
     * @param string $cdGasKind
     *
     * @return float|int
     */
    public static function getProductPriceLiter(string $noShop, int $price, string $cdGasKind = '')
    {
        $priceInfo = self::getOilPrice($noShop, $cdGasKind)->first();
        if ($priceInfo) {
            return Oil::getLiterCalculate($price, $priceInfo['at_price']);
        }
        return 0;
    }

    /**
     * 기기 노즐번호 반환
     *
     * @param int $noShop
     * @param string|null $arkId
     *
     * @return Collection
     * @throws OwinException
     */
    public static function getUnitInfo(int $noShop, ?string $arkId = null): Collection
    {
        $where = [
            'no_shop' => $noShop
        ];
        if ($arkId) {
            $where['ds_display_ark_id'] = $arkId;
        }
        $rows = ShopOilUnit::where($where)->get();
        if (count($rows)) {
            return $rows;
        } else {
            if ($arkId) {
                throw new OwinException(Code::message('P2405'));
            }
            throw new OwinException(Code::message('P2900'));
        }
    }

    /**
     *  주유번호 입력  [DP번호]
     * @param array $data
     *
     * @return int
     */
    public static function registQrReader(array $data): int
    {
        return OrderQrReader::create($data)->no;
    }

    /**
     *  QR리드후 주문,회원정보 업데이트 - qr리더 등록시퀀스 조건 [QR 개발]
     * @param int $lastId
     * @param int $noUser
     * @param string $noOrder
     * @param string $dsUni
     *
     * @return void
     */
    public static function updateQrReader(int $lastId, int $noUser, string $noOrder, string $dsUni): void
    {
        OrderQrReader::where('no', $lastId)->update([
            'no_user' => $noUser,
            'no_order' => $noOrder,
            'ds_uni' => $dsUni,
            'dt_upt' => now()
        ]);
    }

    public static function checkOilDpArk(int $noShop, string $displayArkId): Collection
    {
        return ShopOilUnit::where([
            'no_shop' => $noShop,
            'ds_display_ark_id' => $displayArkId
        ])->get();
    }

    /**
     * @throws Throwable
     */
    public static function createMemberShopEnterLog($data): bool
    {
        return (new MemberShopEnterLog($data))->saveOrFail();
    }
}
