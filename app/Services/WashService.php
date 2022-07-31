<?php

declare(strict_types=1);

namespace App\Services;

use App\Exceptions\OwinException;
use App\Models\MemberShopWashLog;
use App\Models\OrderList;
use App\Models\OrderWashCommission;
use App\Models\WashInshop;
use App\Models\WashProduct;
use App\Utils\Code;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class WashService extends Service
{

    /**
     * [주문요청] 매장 상품정보 반환
     *
     * @param int $noShop
     * @param string|null $cdCarKind
     *
     * @return Collection
     */
    public static function getWashProductList(
        int $noShop,
        ?string $cdCarKind = null
    ): Collection {
        $where = [
            'no_shop' => $noShop,
            'yn_status' => 'Y'
        ];

        if ($cdCarKind) {
            $where['cd_car_kind'] = $cdCarKind;
        }

        return WashProduct::where($where)->with(['washOptions'])->get()->map(function ($product) {
            $product->cd_car_kind = CodeService::getCode($product->cd_car_kind)->nm_code;
            return $product;
        })->sortBy('no_product');
    }

    public static function registOrderWashCommission($data)
    {
        OrderWashCommission::create([
            'no_order' => $data['no_order'],
            'no_user' => $data['no_user'],
            'no_shop' => $data['no_shop'],
            'cd_biz_kind' => $data['cd_biz_kind'],
            'cd_pg' => $data['cd_pg'],
            'no_commission' => $data['no_commission'],
            'at_commission' => $data['at_commission'],
            'at_apply_price' => $data['at_apply_price'],
            'at_pg_commission_rate' => $data['at_pg_commission_rate'],
            'at_price' => $data['at_price'],
            'at_cpn_disct' => $data['at_cpn_disct'],
            'at_price_pg' => $data['at_price_pg'],
            'at_owin_commission' => $data['at_owin_commission'],
            'at_owin_vat' => $data['at_owin_vat'],
            'at_pg_commission' => $data['at_pg_commission'],
            'at_pg_vat' => $data['at_pg_vat'],
            'at_cpn_disct_pg_commit' => $data['at_cpn_disct_pg_commit'],
            'at_cpn_disct_pg_vat' => $data['at_cpn_disct_pg_vat'],
            'yn_pg_commission_out' => $data['yn_pg_commission_out'],
            'at_price_for_shop' => $data['at_price_for_shop'],
        ]);
    }

    public static function registMemberShopWashLog($data)
    {
        MemberShopWashLog::create([
            'no_order' => $data['no_order'],
            'no_user' => $data['no_user'],
            'no_shop' => $data['no_shop'],
            'cd_alarm_event_type' => $data['cd_alarm_event_type'],
        ]);
    }


    public static function washComplete($member, $orderInfo)
    {
        try {
            DB::beginTransaction();
            ## [3] 미처리 주문일경우  도착완료처리 (2020.09.14 세차완료->도착완료(세차완료 전 단계))
            OrderList::where([
                'no_user' => $member['no_user'],
                'no_order' => $orderInfo['no_order'],
            ])->update([
                'cd_pickup_status' => '602300',
                'dt_pickup_status' => Carbon::now(),
            ]);
            ## [4] 처리완료 로그
            self::registMemberShopWashLog([
                'no_user' => $member['no_user'],
                'no_shop' => $orderInfo['no_shop'],
                'no_order' => $orderInfo['no_order'],
                'cd_alarm_event_type' => '619210'
            ]);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            Log::channel('error')->error('[P2500] washComplete', [$e->getMessage()]);
            throw new OwinException(Code::message('P2500'));
        }
    }

    public static function getWashShopInShopInfo($noShop)
    {
        return WashInshop::select([
            'wash_inshop.*',
            'shop.ds_status'
        ])->join('shop', 'wash_inshop.no_shop', '=', 'shop.no_shop')
            ->where([
                'wash_inshop.no_shop_in' => $noShop
            ])->first();
    }
}
