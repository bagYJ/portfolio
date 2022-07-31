<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\OptionType;
use App\Exceptions\OwinException;
use App\Models\MemberShopRetailLog;
use App\Models\OrderList;
use App\Models\RetailExternalApiLog;
use App\Models\RetailProduct;
use App\Models\RetailShopProductStock;
use App\Models\Shop;
use App\Utils\Code;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class RetailService extends Service
{

    /**
     * 리테일 오윈 매장코드로 본사용 매장코드 조회
     * @param int|string $noPartner
     * @param int|string $noShop
     * @param array $parameter
     * @return string
     */
    public static function getRetailStoreCd(int|string $noPartner, int|string $noShop, array $parameter): string
    {
        return Shop::select([
            'shop.store_cd'
        ])->join('partner', 'shop.no_partner', '=', 'partner.no_partner')->where([
            'shop.no_partner' => $noPartner,
            'shop.no_shop' => $noShop,
            'partner.cd_biz_kind' => '201800'
        ])->get()->whenEmpty(function () use ($parameter) {
            RetailService::insertRetailExternalResultLog($parameter, [
                'result' => false,
                'result_code' => 'M1303'
            ]);
            throw new OwinException(Code::message('M1303'));
        })->first()->store_cd;
    }

    /**
     * 오윈 상품번호로 본사용 상품코드 조회
     * @param array $noProductList
     * @return Collection
     */
    public static function getRetailNoBarcode(array $noProductList): Collection
    {
        return RetailProduct::with('productOptionGroups.productOptionProducts')->whereIn(
            'no_product',
            $noProductList
        )->get();
    }

    /**
     * 리테일 본사용 매장코드로 오윈 매장코드 조회
     * @param string|int $noPartner
     * @param string $storeCd
     * @param array $parameter
     * @return Shop
     */
    public static function getRetailShop(string|int $noPartner, string $storeCd, array $parameter): Shop
    {
        return Shop::where([
            'no_partner' => $noPartner,
            'store_cd' => $storeCd
        ])->get()->whenEmpty(function () use ($parameter) {
            RetailService::insertRetailExternalResultLog($parameter, [
                'result' => false,
                'result_code' => '9910'
            ]);
            throw new OwinException(Code::message('9910'));
        })->first();
    }

    /**
     * retail api 요청 로그
     * @param string $callUrl
     * @param string $callPath
     * @param string $dtRequest
     * @param array $param
     * @param string $paramEnc
     * @param string $apiResponseEnc
     * @param array $apiResponse
     */
    public static function insertRetailExternalLog(
        string $callUrl,
        string $callPath,
        string $dtRequest,
        array $param,
        string $paramEnc,
        string $apiResponseEnc,
        array $apiResponse
    ): void {
        RetailExternalApiLog::create([
            'api_url' => $callUrl,
            'call_path' => $callPath,
            'dt_request' => $dtRequest,
            'result_code' => $apiResponse ? $apiResponse['result_code'] : null,
            'result_msg' => $apiResponse ? $apiResponse['result_msg'] : null,
            'request_param' => json_encode($param),
            'ori_request' => $paramEnc,
            'response_param' => $apiResponse ? json_encode($apiResponse) : null,
            'ori_response' => $apiResponseEnc ?: trim(preg_replace('/\r\n|\r|\n/', '', $apiResponseEnc)),
            'no_order' => $param['no_order'] ?: null,
        ]);
    }

    public static function insertRetailExternalResultLog($request, array $response, $originResponse = null)
    {
        $resultMsg = "";
        if ($response) {
            if (isset($response['result_code']) && Config("yml.message.{$response['result_code']}")) {
                $resultMsg = Config("yml.message.{$response['result_code']}");
            } else {
                if (isset($response['result_msg'])) {
                    $resultMsg = $response['result_msg'];
                }
            }
        }

        return RetailExternalApiLog::create([
            'api_url' => getenv('HTTP_HOST') . getenv('REQUEST_URI'),
            'dt_request' => date('Y-m-d H:i:s'),
            'result_code' => $response ? $response['result_code'] : null,
            'result_msg' => $resultMsg,
            'request_param' => json_encode($request),
            'ori_request' => base64_encode(json_encode($request)),
            'response_param' => $response ? json_encode($response) : null,
            'ori_response' => $originResponse,
        ]);
    }

    /**
     * 주문번호로 조회 (주문정보만 조회)
     * @param string $noOrder
     * @param array $parameter
     * @return OrderList|Model
     */
    public static function getOrderInfo(string $noOrder, array $parameter): OrderList|Model
    {
        return OrderList::with('member')->select([
            '*',
            DB::raw("(case cd_send_type when '622100' then 'DV' when '622200' then 'PU' else 'PU' end) as ds_pickup_type")
        ])
            ->addSelect([
                'ds_car_number' => function ($query) {
                    $query->select(DB::raw('GROUP_CONCAT(ds_maker, \':\', order_list.ds_car_number)'))
                        ->from('car_list')->whereColumn('seq', 'order_list.seq');
                }
            ])
            ->where('no_order', $noOrder)
            ->get()->whenEmpty(function () use ($parameter) {
                RetailService::insertRetailExternalResultLog($parameter, [
                    'result' => false,
                    'result_code' => 'P2120'
                ]);
                throw new OwinException(Code::message('P2120'));
            })->first();
    }

    /**
     * 주문번호로 주문내역 (상품, 상품옵션 포함)조회
     * @param string $noOrder
     * @return array
     */
    public static function getOrderProductInfo(string $noOrder)
    {
        //todo query 변경하기
        return DB::select(
            "(
                SELECT
                    op.no_order_product
                    , op.nm_product
                    , op.at_price_product as at_price
                    -- , op.at_price
                    , op.ct_inven
                    , p.no_barcode
                    , p.cd_discount_sale
                FROM retail_order_product op
                JOIN retail_product p on p.no_product = op.no_product
                WHERE
                    op.no_order = '{$noOrder}'
            )
            UNION ALL
            (
			   SELECT
                    opo.no_order_product
                    , opo.nm_product_opt
                    , opo.at_price_product_opt as at_price
                    , opo.ct_inven
                    , p2.no_barcode
                    , p2.cd_discount_sale
                FROM retail_order_product_option opo
                JOIN retail_order_product op2 on opo.no_order_product = op2.no_order_product
                JOIN retail_product p2 on p2.no_product = op2.no_product
                WHERE
                    opo.no_order = '{$noOrder}'
            	    AND op2.cd_discount_sale not in ('132500')
            )
            ORDER BY no_order_product ASC, at_price DESC"
        );
    }

    /**
     * 주문정보 수정
     * @param array $data
     * @return void
     */
    public static function updateOrderInfo(array $data)
    {
        $update = [];
        $insert = [
            'no_user' => $data['no_user'],
            'no_shop' => $data['no_shop'],
            'no_order' => $data['no_order'],
        ];

        if (data_get($data, 'cd_pickup_status')) {
            $update['cd_pickup_status'] = $data['cd_pickup_status'];
            $update['dt_pickup_status'] = now();
        }
        if (data_get($data, 'cd_payment_status')) {
            $update['cd_payment_status'] = $data['cd_payment_status'];
            $update['dt_payment_status'] = now();
        }
        if (data_get($data, 'cd_order_status')) {
            $update['cd_order_status'] = $data['cd_order_status'];
            $update['dt_order_status'] = now();
        }
        if (data_get($data, 'cd_alarm_event_type')) {
            $update['cd_alarm_event_type'] = $data['cd_alarm_event_type'];
            $insert['cd_alarm_event_type'] = $data['cd_alarm_event_type'];
        }
        if (data_get($data, 'id_admin')) {
            $insert['id_admin'] = $data['id_admin'];
        }
        if (data_get($data, 'retail_event_result_code')) {
            $insert['result_code'] = $data['retail_event_result_code'];
            $insert['result_msg'] = $data['retail_event_result_msg'];
        }
        if (data_get($data, 'confirm_date')) {
            $update['confirm_date'] = $data['confirm_date'];
        }
        if (data_get($data, 'ready_date')) {
            $update['ready_date'] = $data['ready_date'];
        }
        if (data_get($data, 'pickup_date')) {
            $update['pickup_date'] = $data['pickup_date'];
        }

        DB::transaction(function () use ($data, $update, $insert) {
            //주문 상태 변경
            OrderList::where('no_order', $data['no_order'])->update($update);
            //이벤트 로그 작성
            MemberShopRetailLog::create($insert);
        });
    }

    /**
     * 주문내역 상태변경 업데이트
     * @param string $noOrder
     * @param array $update
     * @return void
     */
    public static function updateOrderStatus(string $noOrder, array $update)
    {
        OrderList::where('no_order', $noOrder)->update($update);
    }

    /**
     * 해당 상품이 세팅되어있는지 체크
     * @param string $strProductList
     * @return Collection
     */
    public static function checkYnProduct(string $strProductList)
    {
        return RetailProduct::whereRaw("no_barcode IN ({$strProductList})")->get();
    }

    /**
     * 해당 상품의 재고관리가 되어 있는지 체크
     * @param string $noShop
     * @param string $strProductList
     * @return Collection
     */
    public static function checkShopStock(string $noShop, string $strProductList)
    {
        return RetailShopProductStock::where('no_shop', $noShop)
            ->whereRaw(
                "no_product IN (SELECT no_product FROM retail_product WHERE no_barcode IN ({$strProductList}))"
            )->get();
    }

    /**
     * 리테일 상품 품절 해제
     * memo : 상품품절 해제 시 무조건 1이상이라고 판단. 주문시에 재고조회를 하기 대문에 따로 수량을 받지 않도록 한다 (수량을 넘겨주게 되면 CU쪽 공수가 들어간다고 답변받음)
     * @param string $transDt
     * @param string $noPartner
     * @param string $noShop
     * @param string $strProductList
     * @return bool|int
     */
    public static function setRetailShopProductSoldOut(
        string $transDt,
        string $noPartner,
        string $noShop,
        string $strProductList
    ): bool|int {
        return RetailShopProductStock::where([
            'no_partner' => $noPartner,
            'no_shop' => $noShop
        ])->whereRaw("no_product IN (SELECT no_product FROM retail_product WHERE no_barcode IN ({$strProductList}))")
            ->update([
                'cnt_product' => '1',
                'yn_soldout' => 'N',
                'dt_soldout' => date('Y-m-d H:i:s', strtotime($transDt)),
            ]);
    }

    /**
     * 리테일 매장 운영상태 변경
     * @param string $ynStatusOpen
     * @param string $noPartner
     * @param string $noShop
     * @return void
     */
    public static function setRetailShopStatus(string $ynStatusOpen, string $noPartner, string $noShop)
    {
        Shop::where([
            'no_partner' => $noPartner,
            'no_shop' => $noShop
        ])->update([
            'ds_status' => $ynStatusOpen,
            'external_dt_status' => DB::raw("NOW()"),
            'id_upt' => 'SYSTEM',
            'dt_upt' => DB::raw("NOW()")
        ]);
    }

    public static function getOptionMinStock(?Collection $productOptionGroups, array $realStock): array
    {
        return [
            'require' => $productOptionGroups?->where('cd_option_type', OptionType::REQUIRED->value)->map(
                function ($group) use ($realStock) {
                    return $group->productOptionProducts->map(function ($option) use ($realStock) {
                        return data_get($realStock, $option->no_barcode_opt);
                    })->values();
                }
            )->flatten()->min(),
            'select' => $productOptionGroups?->where('cd_option_type', OptionType::SELECT->value)->map(
                function ($group) use ($realStock) {
                    return $group->productOptionProducts->map(function ($option) use ($realStock) {
                        return data_get($realStock, $option->no_barcode_opt);
                    })->values();
                }
            )->flatten()->min()
        ];
    }
}
