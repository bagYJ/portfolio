<?php

declare(strict_types=1);

namespace App\Abstracts;

use App\Enums\Method;
use App\Enums\OrderStatus as EnumOrderStatus;
use App\Enums\Pg;
use App\Enums\Pickup;
use App\Enums\PickupStatus;
use App\Enums\SendType;
use App\Enums\SpcAlarmEventStatus;
use App\Exceptions\CustomSpcException;
use App\Models\MemberCoupon;
use App\Models\OrderAlarmEventLog;
use App\Models\OrderList;
use App\Models\OrderProcess;
use App\Models\Product;
use App\Models\ProductIgnore;
use App\Models\ProductIgnoreHistory;
use App\Models\ProductOption;
use App\Models\ProductOptionIgnore;
use App\Models\ProductOptionIgnoreHistory;
use App\Models\Shop;
use App\Models\ShopDetail;
use App\Requests\Pg\Refund;
use App\Requests\Spc\Cancel;
use App\Requests\Spc\Order;
use App\Requests\Spc\OrderStatus;
use App\Requests\Spc\ProductStatus;
use App\Requests\Spc\ShopStatus;
use App\Requests\Spc\Stock;
use App\Requests\Spc\Uptime;
use App\Services\Production\Bizcall;
use App\Services\Production\Push;
use App\Utils\Common;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Owin\OwinCommonUtil\CodeUtil;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Throwable;

abstract class SpcAbstract
{
    protected static array $brandCodes = ['PBparis'];
    protected static array $storeCodes = ['0000007'];
    private static \App\Services\Dev\Pg|\App\Services\Production\Pg $pg;
    private static \App\Services\Dev\Push|Push $push;
    private static \App\Services\Dev\Bizcall|Bizcall $bizcall;

    /**
     * @param string $path
     * @param array $json
     * @return array
     * @throws Exception
     */
    public static function client(string $path, array $json): array
    {
        try {
            $response = json_decode(
                Common::client(
                    Method::POST, sprintf('%s%s', self::getSpcUri(data_get($json, 'brandCode'), data_get($json, 'storeCode')), $path), [
                        'headers' => [
                            'Content-Type' => 'application/json;charset=UTF-8',
                            'Accept' => 'application/json'
                        ],
                        'json' => $json
                    ]
                )->getBody()->getContents()
                , true
            );

            Log::channel('client')->info(sprintf('%s RESPONSE ', sprintf('%s%s', getenv('SPC_URI'), $path)), $response);
            return match (data_get($response, 'resultCode')) {
                getenv('SPC_RETURN_TRUE') => $response,
                default => throw new BadRequestHttpException(data_get($response, 'resultMessage') ?? data_get($response, 'resultCode'))
            };
        } catch (BadRequestHttpException $e) {
            throw new BadRequestHttpException($e->getMessage());
        } catch (Throwable $t) {
            throw new Exception($t->getMessage());
        }
    }

    /**
     * @param Order $request
     * @return array
     * @throws Exception
     */
    public static function order(Order $request): array
    {
        return self::client(getenv('SPC_PATH_ORDER'), (array)$request);
    }

    /**
     * @param Stock $request
     * @return array
     * @throws Exception
     */
    public static function stock(Stock $request): array
    {
        return self::client(getenv('SPC_PATH_STOCK'), (array)$request);
    }

    /**
     * @param Cancel $request
     * @return array
     * @throws Exception
     */
    public static function cancel(Cancel $request): array
    {
        return self::client(getenv('SPC_PATH_CANCEL'), (array)$request);
    }

    /**
     * @param Uptime $request
     * @return array
     * @throws Exception
     */
    public static function uptime(Uptime $request): array
    {
        return self::client(getenv('SPC_PATH_UPTIME'), (array)$request);
    }

    /**
     * @param OrderStatus $request
     * @return string[]
     * @throws Exception
     */
    public static function updateOrderStatus(OrderStatus $request): array
    {
        self::$pg = Common::getService('Pg');
        self::$push = Common::getService('Push');
        self::$bizcall = Common::getService('Bizcall');
        try {
            $no_order = CodeUtil::convertOrderCodeToOwin($request->orderId);
            $serviceSchemaEnum = CodeUtil::getServiceSchemaEnumFromOrderCode($no_order);
            DB::statement('use ' . $serviceSchemaEnum->value);

            $order = (new OrderList)->findOrFail($no_order);
            (new OrderAlarmEventLog([
                'cd_alarm_event_type' => SpcAlarmEventStatus::case($request->status)->value,
                'no_order' => $order->no_order,
                'no_shop' => $order->no_shop,
                'no_user' => $order->no_user
            ]))->saveOrFail();

            $parameter = [
                'cd_pickup_status' => match ($request->status) {
                    'IC' => PickupStatus::TRY_ORDER->value,
                    'DR' => PickupStatus::ACCEPT_ORDER->value,
                    'PW' => PickupStatus::READY_PICKUP->value,
                    default => PickupStatus::PROCESSED->value
                },
                'dt_pickup_status' => now(),
            ];

            if ($request->status == 'IC'
                && $order->cd_send_type == SendType::PU->value
                && !empty(data_get($request, 'deliveryTime'))
            ) {
                $parameter['dt_pickup'] = $order->dt_reg->addMinutes($request->deliveryTime);
            } elseif ($request->status == 'DR') {
                $parameter['confirm_date'] = now();
            } elseif ($request->status == 'PW') {
                $parameter['ready_date'] = now();
            } elseif ($request->status == 'PC') {
                $parameter['pickup_date'] = now();
            } elseif ($request->status == 'CC') {
                $refund = match ($order->at_price_pg) {
                    0 => (new \App\Responses\Pg\Refund())->kcp(env('RETURN_TRUE'), '정상처리'),
                    default => self::$pg::refund(new Refund((new Request())->merge([
                        'nmPg' => match ($order->cd_pg) {
                            '500600' => Pg::incarpayment_kcp->name,
                            default => Pg::from((int)$order->cd_pg)->name
                        },
                        'dsResOrderNo' => $order->orderPayment->ds_res_order_no,
                        'price' => $order->at_price_pg,
                        'reason' => 'SPC 주문취소',
                        'noOrder' => $order->no_order,
                        'dsServerReg' => $order->orderPayment->ds_server_reg,
                        'nmOrder' => $order->nm_order,
                    ])))
                };
                if ($refund->res_cd != env('RETURN_TRUE')) {
                    return [
                        'resultCode' => '9999',
                        'resultMessage' => $refund->res_msg
                    ];
                }
                $parameter['cd_order_status'] = EnumOrderStatus::MANAGER_CANCEL->value;
                $parameter['cd_payment_status'] = '603900';
                $order->orderPayment->update([
                    'ds_res_code_refund' => $refund->res_cd,
                    'cd_reject_reason' => 607902,
                    'dt_req_refund' => now(),
                    'dt_res_refund' => now()
                ]);
                (new OrderProcess([
                    'no_order' => $order->no_order,
                    'no_user' => $order->no_user,
                    'no_shop' => $order->no_shop,
                    'cd_order_process' => '616991',
                ]))->saveOrFail();

                if (empty($order->ds_cpn_no) === false) {
                    MemberCoupon::where([
                        'no_user' => $order->no_user,
                        'no_order' => $order->no_order
                    ])->update([
                        'cd_mcp_status' => '122100',
                        'dt_upt' => null,
                        'no_order' => '',
                        'ds_etc' => ''
                    ]);
                }

                if ($order->ds_safe_number != env('BIZCALL_DEFAULT_NUMBER')) {
                    self::$bizcall::closeMapping($order->ds_safe_number);
                    $parameter['ds_safe_number'] = env('BIZCALL_DEFAULT_NUMBER');
                }
            }
            $order->updateOrFail($parameter);

            if ($request->status != SpcAlarmEventStatus::IC->name) {
                self::$push::send(
                    $order,
                    sprintf('%s%s', env(sprintf('API_URI_%s', strtoupper(CodeUtil::getServiceSchemaEnumFromOrderCode($order->no_order)->value))), env('API_PATH_PUSH')),
                    'FNB',
                    strtolower(sprintf('%s_%s', SpcAlarmEventStatus::alarmStep($request->status), Pickup::tryFrom((int)$order->cd_pickup_type)->name)),
                    match ($request->status) {
                        SpcAlarmEventStatus::IC->name => 'N',
                        default => 'Y'
                    }
                );
            }

            return [
                'resultCode' => 'S000',
                'resultMessage' => '성공'
            ];
        } catch (Throwable $t) {
            throw new Exception($t->getMessage(), $t->getCode());
        }
    }

    /**
     * @param ProductStatus $request
     * @return string[]
     * @throws Exception
     */
    public static function updateProductStatus(ProductStatus $request): array
    {
        $shop = self::getSpcShops($request->brandCode, [$request->storeCode])->first();

        $noProducts = Product::whereIn('cd_spc', $request->menuCodes)->where('no_partner', $shop->no_partner)->get()->pluck('no_product')->all();
        $noOptions = ProductOption::whereIn('cd_spc', $request->menuCodes)->where('no_partner', $shop->no_partner)->get()->pluck('no_option')->all();

        if ($request->soldoutType == 'soldout') {
            self::createProductIgnore($shop, $noProducts, $noOptions, data_get($request, 'resetDate'));
        } elseif ($request->soldoutType == 'instock') {
            self::removeProductIgnore($shop, $noProducts, $noOptions);
        }

        return [
            'resultCode' => 'S000',
            'resultMessage' => '성공'
        ];
    }

    /**
     * @param ShopStatus $request
     * @return string[]
     * @throws Exception
     */
    public static function updateShopStatus(ShopStatus $request): array
    {
        try {
            $shops = self::getSpcShops($request->brandCode, $request->storeCodes);
            DB::beginTransaction();
            $update = [
                'id_upt' => 'HAPPY_ORDER',
                'dt_upt' => now(),
            ];

            $state = $request->storeStatus == 'open' ? 'Y' : 'N';
            if ($request->orderType == 'DRIVETHRU') {
                $update['yn_car_pickup'] = $state;
            } elseif ($request->orderType == 'PICKUP') {
                $update['yn_shop_pickup'] = $state;
            } else {
                $update['yn_car_pickup'] = $state;
                $update['yn_shop_pickup'] = $state;
            }

            ShopDetail::whereIn('no_shop', $shops->pluck('no_shop')->all())->update($update);
            //elastic search 반영을 위하여 shop 테이블의 dt_upt, id_upt도 업데이트
            Shop::whereIn('shop.no_shop', $shops->pluck('no_shop')->all())
                ->update([
                    'shop.id_upt' => 'HAPPY_ORDER',
                    'shop.dt_upt' => now()
                ]);
            DB::commit();

            return [
                'resultCode' => 'S000',
                'resultMessage' => '성공'
            ];
        } catch (Throwable $t) {
            throw new Exception($t->getMessage(), $t->getCode());
        }
    }

    /**
     * @param string $brandCode
     * @param array $storeCodes
     * @return Collection|array
     */
    public static function getSpcShops(string $brandCode, array $storeCodes): Collection|array
    {
        return Shop::select(['shop.*'])->join('partner', 'shop.no_partner', '=', 'partner.no_partner')
            ->whereIn('shop.cd_spc_store', $storeCodes)
            ->where('partner.cd_spc_brand', $brandCode)
            ->get();
    }

    /**
     * @param Shop $shop
     * @param array $noProducts
     * @param array $noOptions
     * @param string|null $resetDate
     * @return void
     * @throws Exception
     */
    public static function createProductIgnore(Shop $shop, array $noProducts, array $noOptions, ?string $resetDate): void {
        $productBody = match (count($noProducts) > 0) {
            true => collect($noProducts)->map(function ($noProduct) use ($shop, $resetDate) {
                return [
                    'no_shop' => $shop->no_shop,
                    'no_product' => $noProduct,
                    'id_start' => 'SYSTEM',
                    'dt_start' => $resetDate,
                    'id_stop' => 'SYSTEM',
                    'dt_stop' => now(),
                    'dt_reg' => now(),
                ];
            }),
            default => []
        };

        $optionBody = match (count($noOptions) > 0) {
            true => collect($noOptions)->map(function ($noOption) use ($shop, $resetDate) {
                return [
                    'no_shop' => $shop->no_shop,
                    'no_product' => $noOption,
                    'id_start' => 'SYSTEM',
                    'dt_start' => $resetDate,
                    'id_stop' => 'SYSTEM',
                    'dt_stop' => now(),
                    'dt_reg' => now(),
                ];
            }),
            default => []
        };

        try {
            DB::beginTransaction();
            if (count($productBody)) {
                ProductIgnore::insertOrIgnore($productBody->toArray());
                ProductIgnoreHistory::insertOrIgnore($productBody->toArray());
            }
            if (count($optionBody)) {
                ProductOptionIgnore::insertOrIgnore($optionBody->toArray());
                ProductOptionIgnoreHistory::insertOrIgnore($optionBody->toArray());
            }
            DB::commit();
        } catch (Throwable $t) {
            throw new CustomSpcException($t->getMessage());
        }
    }

    /**
     * @param Shop $shop
     * @param array $noProducts
     * @param array $noOptions
     * @return void
     * @throws Exception
     */
    public static function removeProductIgnore(Shop $shop, array $noProducts, array $noOptions): void
    {
        $productBody = match (count($noProducts) > 0) {
            true => collect($noProducts)->map(function ($noProduct) use ($shop) {
                return [
                    'no_shop' => $shop->no_shop,
                    'no_product' => $noProduct,
                    'id_start' => 'SYSTEM',
                    'dt_start' => now(),
                    'dt_reg' => now(),
                ];
            }),
            default => []
        };

        $optionBody = match (count($noOptions) > 0) {
            true => collect($noOptions)->map(function ($noOption) use ($shop) {
                return [
                    'no_shop' => $shop->no_shop,
                    'no_option' => $noOption,
                    'id_start' => 'SYSTEM',
                    'dt_start' => now(),
                    'dt_reg' => now(),
                ];
            }),
            default => []
        };

        try {
            DB::beginTransaction();
            if (count($productBody)) {
                ProductIgnore::whereIn('no_product', $noProducts)->where('no_shop', $shop->no_shop)->delete();
                ProductIgnoreHistory::insertOrIgnore($productBody->toArray());
            }

            if (count($optionBody)) {
                ProductOptionIgnore::whereIn('no_option', $noOptions)->where('no_shop', $shop->no_shop)->delete();
                ProductOptionIgnoreHistory::insertOrIgnore($optionBody->toArray());
            }
            DB::commit();
        } catch (Throwable $t) {
            throw new CustomSpcException($t->getMessage());
        }
    }

    private static function getSpcUri(string $brandCode, string $storeCode): string
    {
        return match (getenv('APP_ENV') == 'production' && in_array($brandCode, self::$brandCodes) && in_array($storeCode, self::$storeCodes)) {
            true => getenv('SPC_QA_URI'),
            default => getenv('SPC_URI')
        };
    }
}